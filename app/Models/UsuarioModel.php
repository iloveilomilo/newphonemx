<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table            = 'usuarios';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false; // No usaremos soft deletes por ahora para simplificar
    protected $allowedFields    = ['rol', 'nombre', 'apellidos', 'correo', 'password', 'telefono'];

    // Fechas automáticas
    protected $useTimestamps = true;
    protected $createdField  = 'fecha_creacion';
    protected $updatedField  = ''; // No pusimos fecha_actualizacion en el script final, lo dejamos vacio
}