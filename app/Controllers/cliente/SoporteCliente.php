<?php

namespace App\Controllers\cliente;

use App\Controllers\BaseController;

class SoporteCliente extends BaseController
{
public function enviar_duda()
    {
        $session = session();
        $db = \Config\Database::connect();

        $producto_id = $this->request->getPost('producto_id');
        $mensaje = $this->request->getPost('mensaje');
        $usuario_id = $session->get('id');

        $producto = $db->table('productos')->where('id', $producto_id)->get()->getRowArray();
        $nombreCelular = $producto ? $producto['nombre'] : 'un equipo';

        // Crear la Sala de Chat 
        $dataSala = [
            'cliente_id'  => $usuario_id,   
            'estado'      => 'nuevo',
            'fecha_inicio'=> date('Y-m-d H:i:s')
        ];
        
        $db->table('salas_chat')->insert($dataSala);
        $sala_id = $db->insertID(); 

        // Armar un mensaje elegante 
        $mensajeConContexto = "📱 Consulta sobre: " . $nombreCelular . "\n\n" . $mensaje;

        $dataMensaje = [
            'sala_chat_id' => $sala_id,       
            'remitente_id' => $usuario_id,    
            'mensaje'      => $mensajeConContexto,
            'fecha_envio'  => date('Y-m-d H:i:s')
        ];
        
        $db->table('mensajes_chat')->insert($dataMensaje);

        // Confirmar a la vista que todo salió bien
        return $this->response->setJSON(['success' => true]);
    }

    public function mis_preguntas()
    {
        $db = \Config\Database::connect();
        $usuario_id = session('id');

        $salas = $db->table('salas_chat')
                    ->where('cliente_id', $usuario_id)
                    ->orderBy('fecha_inicio', 'DESC')
                    ->get()
                    ->getResultArray();

        return view('cliente/mis_preguntas', ['salas' => $salas]);
    }
    public function ver_chat($sala_id)
    {
        $db = \Config\Database::connect();
        $usuario_id = session('id');

        // Verificamos que la sala exista y le pertenezca a este cliente
        $sala = $db->table('salas_chat')->where('id', $sala_id)->where('cliente_id', $usuario_id)->get()->getRowArray();
        
        if (!$sala) {
            return redirect()->to(base_url('mis-preguntas'));
        }

        // Traemos todos los mensajes de esa sala ordenados del más viejo al más nuevo
        $mensajes = $db->table('mensajes_chat')->where('sala_chat_id', $sala_id)->orderBy('fecha_envio', 'ASC')->get()->getResultArray();

        return view('cliente/ver_chat', ['sala' => $sala, 'mensajes' => $mensajes]);
    }

    public function responder_chat()
    {
        $db = \Config\Database::connect();
        $sala_id = $this->request->getPost('sala_id');
        $mensaje = $this->request->getPost('mensaje');
        $usuario_id = session('id');

        // Insertamos la respuesta del cliente
        $db->table('mensajes_chat')->insert([
            'sala_chat_id' => $sala_id,
            'remitente_id' => $usuario_id,
            'mensaje'      => $mensaje,
            'fecha_envio'  => date('Y-m-d H:i:s')
        ]);

        // Cambiamos el estado de nuevo a "nuevo"
        $db->table('salas_chat')->where('id', $sala_id)->update(['estado' => 'nuevo']);

        return redirect()->to(base_url('mis-preguntas/chat/' . $sala_id));
    }
}