<?php

namespace App\Controllers\Administrador;

use App\Controllers\BaseController;
use App\Models\AtencionModel;

class SoporteAdmin extends BaseController
{
    protected $atencionModel;

    public function __construct() {
        $this->atencionModel = new AtencionModel();
    }

    // 1. Responder Dudas
    public function index() {
        $data['chats'] = $this->atencionModel->obtenerTickets();
        return view('AtencionCliente/index', $data);
    }

    // 2. Ver Mensajes (LA QUE TE DA ERROR)
    public function mensajes() {
        $data['chats'] = $this->atencionModel->obtenerTickets('pendiente');
        return view('AtencionCliente/mensajes', $data);
    }

    // 3. Ver Historial
    public function historial() {
        $data['chats'] = $this->atencionModel->obtenerTickets('resuelto');
        return view('AtencionCliente/historial', $data);
    }

    // 4. Cargar vista de responder
    public function responder() {
        return view('AtencionCliente/responder');
    }

    // Función para ver un chat específico
    public function ver_chat($id) {
        $data['sala'] = $this->atencionModel->find($id);
        $data['mensajes'] = $this->atencionModel->obtenerConversacion($id);
        return view('AtencionCliente/responder', $data);
    }

    // Función que procesa el envío
    public function responder_post() {
        $sala_id = $this->request->getPost('sala_chat_id');
        $mensaje = $this->request->getPost('mensaje');
        
        if ($mensaje) {
            $this->atencionModel->enviarRespuesta([
                'sala_chat_id' => $sala_id,
                'remitente_id' => session('id'),
                'mensaje'      => $mensaje,
                'fecha_envio'  => date('Y-m-d H:i:s')
            ]);
            $this->atencionModel->update($sala_id, ['estado' => 'en_proceso']);
        }
        return redirect()->to('/admin/soporte/chat/' . $sala_id);
    }
} // <--- ESTA LLAVE ES EL CIERRE DE TODO. NO ESCRIBAS NADA DESPUÉS DE ELLA.