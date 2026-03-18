<?php

namespace App\Controllers\Administrador;

use App\Controllers\BaseController;

class Soporte extends BaseController
{
    // ==========================================
    // 1. FUNCIONES PARA MOSTRAR LAS VISTAS
    // ==========================================
    
 public function index()
    {
        $db = \Config\Database::connect();
        
        // Agregamos el conteo de "espera_cliente"
        $data['totales'] = [
            'nuevos'         => $db->table('salas_chat')->where('estado', 'nuevo')->countAllResults(),
            'en_proceso'     => $db->table('salas_chat')->where('estado', 'en_proceso')->countAllResults(),
            'espera_cliente' => $db->table('salas_chat')->where('estado', 'espera_cliente')->countAllResults(),
            'resueltos'      => $db->table('salas_chat')->where('estado', 'cerrado')->countAllResults()
        ];
        
        $builder = $db->table('salas_chat');
        $builder->select('salas_chat.id, salas_chat.asunto, salas_chat.estado, salas_chat.fecha_inicio, usuarios.nombre, usuarios.apellidos');
        $builder->join('usuarios', 'usuarios.id = salas_chat.cliente_id', 'left');
        $builder->where('salas_chat.estado !=', 'cerrado');
        $builder->orderBy('salas_chat.fecha_inicio', 'DESC');
        $builder->limit(5); 
        
        $data['ultimos_tickets'] = $builder->get()->getResultArray();

        $data['rol'] = session()->get('rol') ?? 'atencion_cliente'; 
        
        return view('AtencionCliente/index', $data);
    }

    public function mensajes()
    {
        $db = \Config\Database::connect();
        
        // Traemos todas las conversaciones reales ordenadas por fecha
        $builder = $db->table('salas_chat');
        $builder->select('salas_chat.*, usuarios.nombre, usuarios.apellidos');
        $builder->join('usuarios', 'usuarios.id = salas_chat.cliente_id', 'left');
        $builder->where('salas_chat.estado !=', 'cerrada'); // No mostramos los archivados
        $builder->orderBy('salas_chat.fecha_inicio', 'DESC');
        
        $conversaciones = $builder->get()->getResultArray();

        $data = [
            'conversaciones' => $conversaciones,
            'rol'            => session()->get('rol') ?? 'atencion_cliente'
        ];

        return view('AtencionCliente/mensajes', $data);
    }

    public function historial()
    {
        $db = \Config\Database::connect();
        
        // Traemos SOLO las conversaciones que ya están cerradas
        $builder = $db->table('salas_chat');
        $builder->select('salas_chat.*, usuarios.nombre, usuarios.apellidos');
        $builder->join('usuarios', 'usuarios.id = salas_chat.cliente_id', 'left');
        $builder->where('salas_chat.estado', 'cerrado');
        $builder->orderBy('salas_chat.fecha_cierre', 'DESC'); 
        
        $historial = $builder->get()->getResultArray();

        // AQUÍ METEMOS EL HISTORIAL Y TU ROL EN EL MISMO PAQUETE
        $data = [
            'historial' => $historial,
            'rol'       => session()->get('rol') ?? 'atencion_cliente'
        ];

        return view('AtencionCliente/historial', $data);
    }

    public function responder($id_conversacion = null)
    {
        $db = \Config\Database::connect();

        // 1. Obtenemos TODOS los tickets activos para la barra lateral (como el diseño de tu compañero)
        $conversaciones = $db->table('salas_chat')
            ->select('salas_chat.id, salas_chat.fecha_inicio, usuarios.nombre, usuarios.apellidos')
            ->join('usuarios', 'usuarios.id = salas_chat.cliente_id', 'left')
            ->where('salas_chat.estado !=', 'cerrado')
            ->orderBy('salas_chat.fecha_inicio', 'DESC')
            ->get()->getResultArray();

        // Si entramos sin número, abrimos el primer ticket automáticamente
        if (!$id_conversacion && !empty($conversaciones)) {
            $id_conversacion = $conversaciones[0]['id'];
        }

        // 2. Obtenemos los detalles específicos del ticket seleccionado
        $conversacion = $db->table('salas_chat')
            ->select('salas_chat.*, usuarios.nombre, usuarios.apellidos')
            ->join('usuarios', 'usuarios.id = salas_chat.cliente_id', 'left')
            ->where('salas_chat.id', $id_conversacion)
            ->get()->getRowArray();

        // Por si no encuentra nada
        if (!$conversacion) {
            $conversacion = [
                'id' => 1, 'nombre' => 'Juan', 'apellidos' => 'Pérez (Prueba)',
                'estado' => 'en_proceso', 'fecha_seguimiento' => ''
            ];
        }

        $data = [
            'conversaciones' => $conversaciones, // Mandamos la lista
            'conversacion'   => $conversacion,   // Mandamos el chat activo
            'rol'            => session()->get('rol') ?? 'atencion_cliente'
        ];

        return view('AtencionCliente/responder', $data);
    }
    // ==========================================
    // 2. FUNCIONES PARA PROCESAR LOS DATOS (NUEVAS)
    // ==========================================

    // Función para guardar el mensaje del chat
    public function enviar_mensaje()
    {
        $id_conversacion = $this->request->getPost('id_conversacion');
        $mensaje = $this->request->getPost('mensaje');

        // Si el mensaje no está vacío, lo guardamos en la base de datos
        if (!empty($mensaje)) {
            $db = \Config\Database::connect();
            
            // Guardamos el mensaje en la tabla mensajes_chat.
            // Si por algo tu sesión caducó, forzamos el ID 3 (que es tu usuario Paola).
            $db->table('mensajes_chat')->insert([
                'sala_chat_id' => $id_conversacion,
                'remitente_id' => session()->get('id') ?? 3, 
                'mensaje'      => $mensaje,
                'fecha_envio'  => date('Y-m-d H:i:s')
            ]);
        }

        // Al terminar de guardar, recargamos la página en el mismo chat
        return redirect()->to(base_url('admin/soporte/responder/' . $id_conversacion));
    }

    // Función para guardar los cambios del panel lateral
    public function actualizar_conversacion()
    {
        $id_conversacion = $this->request->getPost('id_conversacion');
        $estado = $this->request->getPost('estado'); // Aquí atrapamos si elegiste 'cerrado'
        $fecha_seguimiento = $this->request->getPost('fecha_seguimiento');

        $db = \Config\Database::connect();
        
        $datos = [
            'estado'              => $estado,
            'fecha_cambio_estado' => date('Y-m-d H:i:s')
        ];

        // Si elegiste "Finalizar" (cerrado) en el combo, le ponemos su fecha de cierre
        if ($estado == 'cerrado') {
            $datos['fecha_cierre'] = date('Y-m-d H:i:s');
        }

        if (!empty($fecha_seguimiento)) {
            $datos['fecha_seguimiento'] = $fecha_seguimiento;
        } else {
            $datos['fecha_seguimiento'] = null;
        }

        // Actualizamos en la base de datos
        $db->table('salas_chat')->where('id', $id_conversacion)->update($datos);

        // ¡EL TRUCO! Si lo pasaste a "Finalizar", te manda directo al historial
        if ($estado == 'cerrado') {
            return redirect()->to(base_url('admin/soporte/historial'))->with('msg', 'Conversación finalizada y archivada');
        }

        // Si solo lo pusiste en "En Proceso", se queda en el chat
        return redirect()->to(base_url('admin/soporte/responder/' . $id_conversacion))->with('msg', 'Gestión actualizada');
    }

    // Función para el botón gris de "Archivar"
    public function archivar_conversacion($id_conversacion)
    {
        $db = \Config\Database::connect();
        
        // Cambiamos el estado a 'cerrado' y registramos la fecha exacta
        $db->table('salas_chat')->where('id', $id_conversacion)->update([
            'estado'       => 'cerrado',
            'fecha_cierre' => date('Y-m-d H:i:s')
        ]);

        // Al archivar, te manda a tu bandeja de Archivados (historial)
        return redirect()->to(base_url('admin/soporte/historial'))->with('msg', 'Ticket archivado exitosamente');
    }

    // ==========================================
    // 3. FUNCIÓN PARA EL TIEMPO REAL (AJAX)
    // ==========================================
    public function obtener_mensajes_nuevos($id_sala)
    {
        $db = \Config\Database::connect();
        
        $builder = $db->table('mensajes_chat');
        $builder->select('mensajes_chat.*, usuarios.nombre, usuarios.rol');
        $builder->join('usuarios', 'usuarios.id = mensajes_chat.remitente_id');
        $builder->where('sala_chat_id', $id_sala);
        $builder->orderBy('fecha_envio', 'ASC');
        
        $mensajes = $builder->get()->getResultArray();

        return $this->response->setJSON(['mensajes' => $mensajes]);
    }
    // Función para Retomar/Reabrir un ticket cerrado
    public function reabrir_conversacion($id_conversacion)
    {
        $db = \Config\Database::connect();
        
        // Lo regresamos a "En proceso" y le quitamos la fecha de cierre
        $db->table('salas_chat')->where('id', $id_conversacion)->update([
            'estado'              => 'en_proceso',
            'fecha_cierre'        => null,
            'fecha_cambio_estado' => date('Y-m-d H:i:s')
        ]);

        // Recargamos el chat para que ya puedas escribir
        return redirect()->to(base_url('admin/soporte/responder/' . $id_conversacion))->with('msg', 'Ticket reabierto correctamente');
    }
}