<?php

namespace App\Models;

use CodeIgniter\Model;

class CarritoModel extends Model
{
    protected $table = 'carrito';
    protected $primaryKey = 'id';
    protected $allowedFields = ['usuario_id', 'inventario_id', 'cantidad'];

    public function obtenerCarrito($usuario_id)
    {
        $builder = $this->select('
            carrito.id as carrito_id,
            carrito.inventario_id,
            carrito.cantidad,
            productos.nombre,
            productos.imagen_principal as imagen,
            inventario.precio,
            inventario.descuento
        ');
        $builder->join('inventario', 'inventario.id = carrito.inventario_id');
        $builder->join('productos', 'productos.id = inventario.producto_id');
        $builder->where('carrito.usuario_id', $usuario_id);
        
        return $builder->findAll();
    }
}