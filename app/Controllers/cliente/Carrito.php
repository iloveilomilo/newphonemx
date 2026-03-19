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


        $db = \Config\Database::connect();
        $producto = $db->table('inventario')->select('stock')->where('id', $inventario_id)->get()->getRowArray();

        if (!$producto || $producto['stock'] <= 0) {
            return $this->response->setJSON(['success' => false, 'mensaje' => 'Lo sentimos, este producto está agotado.']);
        }

        if ($existe) {
            if ($existe['cantidad'] >= $producto['stock']) {
                return $this->response->setJSON(['success' => false, 'mensaje' => 'No puedes agregar más, solo hay ' . $producto['stock'] . ' disponibles.']);
            }
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
    
    public function actualizar()
    {
        $id = $this->request->getPost('id');
        $accion = $this->request->getPost('accion');
        
        $carritoModel = new \App\Models\CarritoModel();
        
        // Buscamos el registro exacto en el carrito
        $item = $carritoModel->find($id);
        
        if ($item) {
            $nuevaCantidad = $item['cantidad'];
            
        if ($accion == 'plus') {
            $db = \Config\Database::connect();
            $producto = $db->table('inventario')->select('stock')->where('id', $item['inventario_id'])->get()->getRowArray();

            if ($nuevaCantidad >= $producto['stock']) {
                return $this->response->setJSON(['success' => false, 'message' => 'Límite de stock alcanzado (' . $producto['stock'] . ')']);
            }
            
            $nuevaCantidad++;
        }elseif ($accion == 'minus' && $nuevaCantidad > 1) {
            $nuevaCantidad--;
        }
                    
            $carritoModel->update($id, ['cantidad' => $nuevaCantidad]);
            
            return $this->response->setJSON(['success' => true]);
        }
        
        return $this->response->setJSON(['success' => false, 'message' => 'Error al actualizar']);
    }
}