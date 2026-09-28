<?php

namespace App\Models;

use CodeIgniter\Model;

class TipodignidadModel extends Model
{
    protected $table            = 'tipodignidad';
    protected $primaryKey       = 'idtipodignidad';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nombre',
    ];

    // Validation
    protected $validationRules = [
        'idtipodignidad' => 'permit_empty|is_natural_no_zero',
        'nombre'         => 'required|min_length[3]|max_length[100]|is_unique[tipodignidad.nombre,idtipodignidad,{idtipodignidad}]',
    ];

    protected $validationMessages = [
        'nombre' => [
            'required'   => 'El nombre del tipo de dignidad es obligatorio.',
            'min_length' => 'El nombre debe tener al menos 3 caracteres.',
            'max_length' => 'El nombre no puede exceder los 100 caracteres.',
            'is_unique'  => 'Este tipo de dignidad ya se encuentra registrado.',
        ],
    ];

    protected $skipValidation = false;

    /**
     * Contar cuántas dignidades/candidaturas están asociadas a este tipo de dignidad
     */
    public function countDignidadesAsociadas(int $idtipodignidad): int
    {
        return $this->db->table('dignidad')
                        ->where('idtipodignidad', $idtipodignidad)
                        ->countAllResults();
    }
}
