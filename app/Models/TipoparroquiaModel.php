<?php

namespace App\Models;

use CodeIgniter\Model;

class TipoparroquiaModel extends Model
{
    protected $table            = 'tipoparroquia';
    protected $primaryKey       = 'idtipoparroquia';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nombre'];

    // Validation
    protected $validationRules = [
        'idtipoparroquia' => 'permit_empty|is_natural_no_zero',
        'nombre'          => 'required|min_length[2]|max_length[50]|is_unique[tipoparroquia.nombre,idtipoparroquia,{idtipoparroquia}]',
    ];

    protected $validationMessages = [
        'nombre' => [
            'required'   => 'El nombre del tipo de parroquia es obligatorio.',
            'min_length' => 'El nombre del tipo de parroquia debe tener al menos 2 caracteres.',
            'max_length' => 'El nombre del tipo de parroquia no puede exceder los 50 caracteres.',
            'is_unique'  => 'Este tipo de parroquia ya se encuentra registrado.',
        ],
    ];

    protected $skipValidation = false;
}
