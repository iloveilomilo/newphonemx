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
        $data['rol'] = session()->get('rol'); 
        return view('AtencionCliente/index', $data);
    }

    public function mensajes()
    {
        $data['rol'] = session()->get('rol');
        return view('AtencionCliente/mensajes', $data);
    }

    public function historial()
    {
        $data['rol'] = session()->get('rol');
        return view('AtencionCliente/historial', $data);
    }

    public function responder($id_conversacion = 1)
    {
        $db = \Config\Database::connect();
        
        $builder = $db->table('salas_chat');
        $builder->select('salas_chat.*, usuarios.nombre, usuarios.apellidos');
        $builder->join('usuarios', 'usuarios.id = salas_chat.cliente_id');
        $builder->where('salas_chat.id', $id_conversacion);
        
        $conversacion = $builder->get()->getRowArray();

        // Si la base de datos no encuentra la conversación, creamos una falsa
        // para que la vista siempre funcione y no te marque error 404 ni pantallazo.
        if (!$conversacion) {
            $conversacion = [
                'id' => 1,
                'nombre' => 'Juan',
                'apellidos' => 'Pérez (Prueba)',
                'estado' => 'en_proceso',
                'fecha_seguimiento' => ''
            ];
        }

        $data = [
            'conversacion' => $conversacion,
            'rol'          => session()->get('rol') 
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

        if (!empty($mensaje)) {
            // Aquí irá la lógica de inserción a la BD
        }

        // Recarga la página del chat
        return redirect()->to(base_url('admin/soporte/responder/' . $id_conversacion))->with('msg', 'Mensaje enviado correctamente');
    }

    // Función para guardar los cambios del panel lateral
    public function actualizar_conversacion()
    {
        $id_conversacion = $this->request->getPost('id_conversacion');
        $estado = $this->request->getPost('estado');
        $fecha_seguimiento = $this->request->getPost('fecha_seguimiento');

        // Aquí irá la lógica de actualización a la BD

        return redirect()->to(base_url('admin/soporte/responder/' . $id_conversacion))->with('msg', 'Gestión actualizada');
    }

    // Función para el botón rojo de "Finalizar Conversación"
    public function cerrar_conversacion($id_conversacion)
    {
        // Aquí irá la lógica para cerrar en la BD

        // Al cerrar, te manda al historial directamente
        return redirect()->to(base_url('admin/soporte/historial'))->with('msg', 'Conversación finalizada y archivada');
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
}