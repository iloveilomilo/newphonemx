<?php

namespace App\Controllers\Administrador;  

use App\Controllers\BaseController;  

class Dashboard extends BaseController
{
    public function admin()
    {
        // Actualizamos la ruta de la vista
        return view('Administrador/admin'); 
    }

    public function soporte()
    {
        return view('layouts/main'); 
    }

    public function cliente()
    {
        echo "<h1>Tienda</h1>";
    }
}