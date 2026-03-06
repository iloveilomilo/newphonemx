<?php

namespace App\Controllers\Administrador;  

use App\Controllers\BaseController;  
use App\Models\ProductoClienteModel;

class Dashboard extends BaseController
{
    public function admin()
    {
        return view('Administrador/admin'); 
    }

    public function soporte()
    {
        return view('layouts/main'); 
    }


    public function cliente()
    {
        $productoModel = new \App\Models\ProductoClienteModel();
        $request = service('request');

        // Capturamos la búsqueda y los filtros
        $busqueda = $request->getGet('q');
        $filtros = [
            'categoria'  => $request->getGet('categoria'), 
            'marca'      => $request->getGet('marca'),
            'condicion'  => $request->getGet('condicion'),
            'precio_min' => $request->getGet('precio_min'),
            'precio_max' => $request->getGet('precio_max'),
        ];

        // Le pasamos los filtros al modelo
        $data = [
            'productos'  => $productoModel->getProductosDisponibles($busqueda, $filtros),
            'marcas'     => $productoModel->getMarcasDisponibles(),
            'categorias' => $productoModel->getCategoriasDisponibles(), 
            'busqueda'   => $busqueda,
            'filtros'    => $filtros 
        ];

        return view('cliente/inicio', $data);
    }
}