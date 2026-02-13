<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductoClienteModel extends Model
{
    protected $table = 'productos';
    protected $primaryKey = 'id';
    protected $allowedFields = ['categoria_id', 'nombre', 'marca', 'descripcion', 'imagen_principal'];

    public function getProductosDisponibles($busqueda = null)
    {
        $builder = $this->select('productos.*, inventario.precio, inventario.stock, inventario.condicion, inventario.descuento');
        $builder->join('inventario', 'inventario.producto_id = productos.id');
        $builder->where('productos.fecha_eliminacion', null);
        $builder->where('inventario.activo', 1);
        $builder->where('inventario.stock >', 0);

        if ($busqueda) {
            $builder->groupStart(); 
                $builder->like('productos.nombre', $busqueda);
                $builder->orLike('productos.marca', $busqueda);
                $builder->orLike('productos.descripcion', $busqueda);
            $builder->groupEnd();
        }

        return $builder->findAll();
    }
}