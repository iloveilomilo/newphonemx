<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductoClienteModel extends Model
{
    protected $table = 'productos';
    protected $primaryKey = 'id';
    protected $allowedFields = ['categoria_id', 'nombre', 'marca', 'descripcion', 'imagen_principal'];

    public function getProductosDisponibles($busqueda = null, $filtros = [])
    {
        $builder = $this->select('productos.*, inventario.id as inventario_id, inventario.precio, inventario.stock, inventario.condicion, inventario.descuento, inventario.sku');
        $builder->join('inventario', 'inventario.producto_id = productos.id');
        $builder->where('productos.fecha_eliminacion', null);
        $builder->where('inventario.activo', 1);
        $builder->where('inventario.stock >', 0);

        // Filtro por Búsqueda 
        if ($busqueda) {
            $builder->groupStart(); 
                $builder->like('productos.nombre', $busqueda);
                $builder->orLike('productos.marca', $busqueda);
                $builder->orLike('productos.descripcion', $busqueda);
            $builder->groupEnd();
        }

        // Filtro por Categoría
        if (!empty($filtros['categoria'])) {
            $builder->where('productos.categoria_id', $filtros['categoria']);
        }
        
        // Filtro por Marca
        if (!empty($filtros['marca'])) {
            $builder->where('productos.marca', $filtros['marca']);
        }
        
        // Filtro por Condición
        if (!empty($filtros['condicion'])) {
            $builder->where('inventario.condicion', $filtros['condicion']);
        }
        
        if (!empty($filtros['precio_min'])) {
            $builder->where('(inventario.precio - (inventario.precio * (IFNULL(inventario.descuento, 0) / 100))) >=', (float)$filtros['precio_min']);
        }
        if (!empty($filtros['precio_max'])) {
            $builder->where('(inventario.precio - (inventario.precio * (IFNULL(inventario.descuento, 0) / 100))) <=', (float)$filtros['precio_max']);
        }

        return $builder->findAll();
    }

    public function getMarcasDisponibles()
    {
        return $this->select('marca')->distinct()->where('fecha_eliminacion', null)->findAll();
    }

    public function getCategoriasDisponibles()
    {
        $db = \Config\Database::connect();
        return $db->table('categorias')->where('activo', 1)->get()->getResultArray();
    }
}