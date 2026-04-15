<?php

namespace App\Controllers\Administrador;

use App\Controllers\BaseController;

class SoporteAdmin extends BaseController // O Soporte, según se llame tu archivo
{
    // 1. Vista principal
    public function index()
    {
        $atencionModel = new \App\Models\AtencionModel();
        $data['chats'] = $atencionModel->obtenerTickets();
        return view('AtencionCliente/index', $data);
    }

    // 2. LA QUE FALLA: Ver Mensajes
   public function mensajes() {
    $model = new \App\Models\AtencionModel();
    
    // Obtenemos los datos de la base
    $res = $model->obtenerConversaciones();
    
    // Si la base está vacía, mandamos un array vacío para que no marque el error de count()
    $data['conversaciones'] = $res ? $res : []; 
    
    return view('AtencionCliente/mensajes', $data);
}

    // 3. Ver Historial
    public function historial()
    {
        $atencionModel = new \App\Models\AtencionModel();
        // Traemos los mensajes que ya están 'resuelto'
        $data['chats'] = $atencionModel->obtenerTickets('resuelto');
        return view('AtencionCliente/historial', $data);
    }

    // 4. Responder Mensaje
    public function responder()
    {
        return view('AtencionCliente/responder');
    }
}