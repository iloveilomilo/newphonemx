<?php

namespace App\Models;

use CodeIgniter\Model;

class FiltroModel extends Model
{
    protected $table            = 'filtros';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['nombre'];  
}