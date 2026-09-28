<?php

namespace App\Models;

use CodeIgniter\Model;

class SexoModel extends Model
{
    protected $table            = 'sexo';
    protected $primaryKey       = 'idsexo';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nombre'];

    // Validation
    protected $validationRules = [
        'idsexo' => 'permit_empty|is_natural_no_zero',
        'nombre' => 'required|min_length[2]|max_length[20]|is_unique[sexo.nombre,idsexo,{idsexo}]',
    ];

    protected $validationMessages = [
        'nombre' => [
            'required'   => 'El nombre del sexo es obligatorio.',
            'min_length' => 'El nombre debe tener al menos 2 caracteres.',
            'max_length' => 'El nombre no puede exceder los 20 caracteres.',
            'is_unique'  => 'Este sexo ya se encuentra registrado.',
        ],
    ];

    protected $skipValidation = false;

    /**
     * Contar cuántas personas están asociadas a un determinado sexo
     */
    public function countPersonasAsociadas($idsexo)
    {
        $db = \Config\Database::connect();
        return $db->table('persona')->where('idsexo', $idsexo)->countAllResults();
    }

    /**
     * Contar cuántas mesas electorales están asociadas a un determinado sexo
     */
    public function countMezasAsociadas($idsexo): int
    {
        $db = \Config\Database::connect();
        return $db->table('meza')->where('idsexo', $idsexo)->countAllResults();
    }
}

