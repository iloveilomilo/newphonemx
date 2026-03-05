<?php

namespace App\Controllers\cliente;

use App\Controllers\BaseController;
use App\Models\CarritoModel;

class Carrito extends BaseController
{
    public function index()
    {
        $carritoModel = new CarritoModel();
        // Obtenemos el ID del usuario logueado
        $usuario_id = session('id'); 

        $items = $carritoModel->obtenerCarrito($usuario_id);
        $carrito = [];
        $total = 0;

        foreach ($items as $item) {
            // Calculamos el precio final si hay descuento en la BD
            $precioFinal = $item['precio'];
            if ($item['descuento'] > 0) {
                $precioFinal = $item['precio'] - ($item['precio'] * ($item['descuento'] / 100));
            }

            $carrito[] = [
                'id' => $item['carrito_id'], 
                'inventario_id' => $item['inventario_id'], 
                'nombre' => $item['nombre'],
                'imagen' => $item['imagen'],
                'precio' => $precioFinal,
                'cantidad' => $item['cantidad']
            ];

            $total += ($precioFinal * $item['cantidad']);
        }

        $data = [
            'carrito' => $carrito,
            'total' => $total
        ];

        return view('cliente/carrito', $data);
    }

    public function agregar()
    {
        $request = service('request');
        $carritoModel = new CarritoModel();
        
        $usuario_id = session('id'); 
        $inventario_id = $request->getPost('id');

        // Verificamos si este celular ya está en el carrito de este usuario
        $existe = $carritoModel->where('usuario_id', $usuario_id)
                               ->where('inventario_id', $inventario_id)
                               ->first();

        if ($existe) {
            $carritoModel->update($existe['id'], ['cantidad' => $existe['cantidad'] + 1]);
        } else {
            $carritoModel->insert([
                'usuario_id' => $usuario_id,
                'inventario_id' => $inventario_id,
                'cantidad' => 1
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'mensaje' => 'Producto agregado a tu cuenta'
        ]);
    }

    public function eliminar($id)
    {
        $carritoModel = new CarritoModel();
        $carritoModel->delete($id);
        
        return redirect()->to(base_url('carrito'));
    }
}