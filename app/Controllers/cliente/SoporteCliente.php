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

        // 1. Crear la Sala de Chat 
        $dataSala = [
            'cliente_id'  => $usuario_id,   
            'estado'      => 'nuevo',
            'fecha_inicio'=> date('Y-m-d H:i:s') 
        ];
        
        $db->table('salas_chat')->insert($dataSala);
        $sala_id = $db->insertID(); 

        // Guardar el mensaje en la tabla mensajes_chat
        $mensajeConContexto = "[Duda sobre producto ID: " . $producto_id . "] - " . $mensaje;

        $dataMensaje = [
            'sala_chat_id' => $sala_id,       
            'remitente_id' => $usuario_id,    
            'mensaje'      => $mensajeConContexto,
            'fecha_envio'  => date('Y-m-d H:i:s')
        ];
        
        $db->table('mensajes_chat')->insert($dataMensaje);

        //Confirmar a la vista que todo salió bien
        return $this->response->setJSON(['success' => true]);
    }
}