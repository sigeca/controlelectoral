<?php

namespace App\Models;

use CodeIgniter\Model;

class ProvinciaModel extends Model
{
    protected $table            = 'provincia';
    protected $primaryKey       = 'idprovincia';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nombre'];

    // Validation
    protected $validationRules = [
        'idprovincia' => 'permit_empty|is_natural_no_zero',
        'nombre'      => 'required|min_length[3]|max_length[50]|is_unique[provincia.nombre,idprovincia,{idprovincia}]',
    ];

    protected $validationMessages = [
        'nombre' => [
            'required'   => 'El nombre de la provincia es obligatorio.',
            'min_length' => 'El nombre debe tener al menos 3 caracteres.',
            'max_length' => 'El nombre no puede exceder los 50 caracteres.',
            'is_unique'  => 'Esta provincia ya se encuentra registrada.',
        ],
    ];

    protected $skipValidation = false;

    /**
     * Contar cuántos cantones están asociados a esta provincia
     */
    public function countCantonesAsociados($idprovincia)
    {
        $db = \Config\Database::connect();
        return $db->table('canton')->where('idprovincia', $idprovincia)->countAllResults();
    }
}
