<?php

namespace App\Models;

use CodeIgniter\Model;

class ParroquiaModel extends Model
{
    protected $table            = 'parroquia';
    protected $primaryKey       = 'idparroquia';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nombre',
        'idcanton',
    ];

    // Validation
    protected $validationRules = [
        'idparroquia' => 'permit_empty|is_natural_no_zero',
        'nombre'      => 'required|min_length[2]|max_length[50]',
        'idcanton'    => 'required|is_not_unique[canton.idcanton]',
    ];

    protected $validationMessages = [
        'nombre' => [
            'required'   => 'El nombre de la parroquia es obligatorio.',
            'min_length' => 'El nombre de la parroquia debe tener al menos 2 caracteres.',
            'max_length' => 'El nombre de la parroquia no puede exceder los 50 caracteres.',
        ],
        'idcanton' => [
            'required'      => 'Debe seleccionar un cantón para la parroquia.',
            'is_not_unique' => 'El cantón seleccionado no existe en la base de datos.',
        ],
    ];

    protected $skipValidation = false;

    /**
     * Obtener parroquias con los datos del cantón y provincia asociados
     */
    public function getParroquiasDetailed($id = null)
    {
        $builder = $this->select('parroquia.*, canton.nombre AS canton_nombre, provincia.nombre AS provincia_nombre')
                        ->join('canton', 'canton.idcanton = parroquia.idcanton', 'left')
                        ->join('provincia', 'provincia.idprovincia = canton.idprovincia', 'left');

        if ($id !== null) {
            return $builder->where('parroquia.idparroquia', $id)->first();
        }

        return $builder->orderBy('provincia.nombre', 'ASC')
                       ->orderBy('canton.nombre', 'ASC')
                       ->orderBy('parroquia.nombre', 'ASC')
                       ->findAll();
    }

    /**
     * Contar cuántas zonas están asociadas a una parroquia específica
     */
    public function countZonasAsociadas(int $idparroquia): int
    {
        return $this->db->table('zona')
                        ->where('idparroquia', $idparroquia)
                        ->countAllResults();
    }
}

