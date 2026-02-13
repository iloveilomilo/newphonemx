<?php

namespace App\Controllers\Administrador;  

use App\Controllers\BaseController;  
use App\Models\ProductoClienteModel;

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
        $modelo = new ProductoClienteModel();
        $busqueda = $this->request->getGet('q');

        $datos['productos'] = $modelo->getProductosDisponibles($busqueda);
        $datos['busqueda'] = $busqueda;

        return view('cliente/inicio', $datos); 
    }
}