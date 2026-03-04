<?php

namespace App\Controllers\Soporte;

use App\Controllers\BaseController;

class Soporte extends BaseController
{
    public function index()
    {
        return view('AtencionCliente/index');
    }

    public function mensajes()
    {
        return view('AtencionCliente/mensajes');
    }

    public function historial()
    {
        return view('AtencionCliente/historial');
    }

    public function responder()
    {
        return view('AtencionCliente/responder');
    }
}
