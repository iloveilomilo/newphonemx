<?php

namespace App\Models;

use CodeIgniter\Model;

class DashboardAdminModel extends Model
{
    protected $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }

    // =======================================================
    // Tarjetas Superiores
    // =======================================================
    
    public function obtenerIngresosTotales()
    {
        // Sumamos el total de los pedidos que ya están pagados, enviados o entregados
        $query = $this->db->table('pedidos')
                          ->selectSum('total')
                          ->whereIn('estado', ['pagado', 'enviado', 'entregado'])
                          ->get();
        $resultado = $query->getRow();
        return $resultado->total ?? 0;
    }

    public function obtenerOrdenesPendientes()
    {
        return $this->db->table('pedidos')->where('estado', 'pendiente')->countAllResults();
    }

    public function obtenerProductosActivos()
    {
        return $this->db->table('inventario')->where('activo', 1)->countAllResults();
    }

    public function obtenerClientesRegistrados()
    {
        return $this->db->table('usuarios')->where('rol', 'cliente')->countAllResults();
    }

    // =======================================================
    // TABLA PRINCIPAL Y ALERTAS
    // =======================================================

    public function obtenerUltimasOrdenes()
    {
        return $this->db->table('pedidos')
                    ->select('pedidos.*, usuarios.nombre, usuarios.apellidos')
                    ->join('usuarios', 'usuarios.id = pedidos.cliente_id', 'left')
                    ->orderBy('pedidos.fecha', 'DESC')
                    ->get()
                    ->getResultArray();
    }

    public function obtenerAlertasStockBajo()
    {
        // Traemos inventario con stock menor o igual a 3
        return $this->db->table('inventario')
                    ->select('inventario.stock, inventario.sku, productos.nombre')
                    ->join('productos', 'productos.id = inventario.producto_id')
                    ->where('inventario.activo', 1)
                    ->where('inventario.stock <=', 3)
                    ->orderBy('inventario.stock', 'ASC')
                    ->limit(5)
                    ->get()
                    ->getResultArray();
    }

    public function obtenerSoportePendiente($admin_id)
    {
        return $this->db->table('salas_chat')
                    ->where('soporte_id', $admin_id)
                    ->where('estado', 'nuevo')
                    ->countAllResults();
    }
}