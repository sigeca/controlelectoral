<?php

namespace App\Models;

use CodeIgniter\Model;

class RolusuarioModel extends Model
{
    protected $table            = 'rolusuario';
    protected $primaryKey       = 'idrolusuario';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nombre'];

    // Validation
    protected $validationRules = [
        'idrolusuario' => 'permit_empty|is_natural_no_zero',
        'nombre'       => 'required|min_length[3]|max_length[50]|is_unique[rolusuario.nombre,idrolusuario,{idrolusuario}]',
    ];

    protected $validationMessages = [
        'nombre' => [
            'required'   => 'El nombre del rol es obligatorio.',
            'min_length' => 'El nombre debe tener al menos 3 caracteres.',
            'max_length' => 'El nombre no puede exceder los 50 caracteres.',
            'is_unique'  => 'Este rol de usuario ya se encuentra registrado.',
        ],
    ];

    protected $skipValidation = false;

    /**
     * Contar cuántos usuarios tienen asignado este rol
     */
    public function countUsuariosAsignados($idrolusuario)
    {
        $db = \Config\Database::connect();
        return $db->table('usuario')->where('idrolusuario', $idrolusuario)->countAllResults();
    }
}
