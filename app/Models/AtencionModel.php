<?php
namespace App\Models;
use CodeIgniter\Model;

class AtencionModel extends Model {
    protected $table = 'salas_chat';
    protected $primaryKey = 'id';
    // Asegúrate de que estos nombres coincidan con tu base de datos
    protected $allowedFields = ['cliente_id', 'admin_id', 'estado', 'asunto', 'fecha_inicio'];

    public function obtenerConversaciones() {
    return $this->db->table('salas_chat s')
        ->select('s.id, s.estado, u.nombre, u.apellidos, 
                 (SELECT m.mensaje FROM mensajes_chat m WHERE m.sala_chat_id = s.id ORDER BY m.id ASC LIMIT 1) as asunto,
                 (SELECT m.fecha_envio FROM mensajes_chat m WHERE m.sala_chat_id = s.id ORDER BY m.id ASC LIMIT 1) as fecha_inicio')
        ->join('usuarios u', 'u.id = s.cliente_id')
        ->orderBy('s.id', 'DESC')
        ->get()->getResultArray();
}
}