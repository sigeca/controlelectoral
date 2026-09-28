<?php

namespace App\Models;

use CodeIgniter\Model;

class CantonModel extends Model
{
    protected $table            = 'canton';
    protected $primaryKey       = 'idcanton';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nombre',
        'idprovincia',
    ];

    // Validation
    protected $validationRules = [
        'idcanton'    => 'permit_empty|is_natural_no_zero',
        'nombre'      => 'required|min_length[2]|max_length[50]',
        'idprovincia' => 'required|is_not_unique[provincia.idprovincia]',
    ];

    protected $validationMessages = [
        'nombre' => [
            'required'   => 'El nombre del cantón es obligatorio.',
            'min_length' => 'El nombre del cantón debe tener al menos 2 caracteres.',
            'max_length' => 'El nombre del cantón no puede superar los 50 caracteres.',
        ],
        'idprovincia' => [
            'required'      => 'Debe seleccionar una provincia para este cantón.',
            'is_not_unique' => 'La provincia seleccionada no existe en la base de datos.',
        ],
    ];

    protected $skipValidation = false;

    /**
     * Obtener cantones con el nombre de su provincia asociada
     */
    public function getCantonesWithProvincia($id = null)
    {
        $builder = $this->select('canton.*, provincia.nombre AS provincia_nombre')
                        ->join('provincia', 'provincia.idprovincia = canton.idprovincia', 'left');

        if ($id !== null) {
            return $builder->where('canton.idcanton', $id)->first();
        }

        return $builder->orderBy('provincia.nombre', 'ASC')
                       ->orderBy('canton.nombre', 'ASC')
                       ->findAll();
    }

    /**
     * Contar cuántas parroquias están asociadas a este cantón
     */
    public function countParroquiasAsociadas($idcanton)
    {
        $db = \Config\Database::connect();
        return $db->table('parroquia')->where('idcanton', $idcanton)->countAllResults();
    }
}
