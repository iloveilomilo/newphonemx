<?php

namespace App\Models;

use CodeIgniter\Model;

class SoporteModel extends Model
{
    protected $table = 'tickets';
    protected $primaryKey = 'id_ticket';

    protected $allowedFields = [
        'cliente',
        'telefono',
        'tipo_servicio',
        'descripcion',
        'estado'
    ];
}
