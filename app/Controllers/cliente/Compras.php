<?php

namespace App\Controllers\cliente;
use App\Controllers\BaseController;

class Compras extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $usuario_id = session('id');

        // Traemos los pedidos con la dirección de envío unida
        $builder = $db->table('pedidos p');
        $builder->select('p.*, d.calle, d.numero_exterior, d.ciudad');
        $builder->join('direcciones_usuarios d', 'd.id = p.direccion_envio_id');
        $builder->where('p.cliente_id', $usuario_id);
        $builder->orderBy('p.fecha', 'DESC');
        $pedidos = $builder->get()->getResultArray();

        foreach ($pedidos as &$p) {
            $builderDetalle = $db->table('detalles_pedido dp');
            $builderDetalle->select('dp.*, pr.nombre as producto_nombre, pr.marca');
            $builderDetalle->join('inventario i', 'i.id = dp.inventario_id');
            $builderDetalle->join('productos pr', 'pr.id = i.producto_id');
            $builderDetalle->where('dp.pedido_id', $p['id']);
            $p['productos'] = $builderDetalle->get()->getResultArray();
        }

        return view('cliente/mis_compras', ['pedidos' => $pedidos]);
    }
}