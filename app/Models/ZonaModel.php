<?php

namespace App\Models;

use CodeIgniter\Model;

class ZonaModel extends Model
{
    protected $table            = 'zona';
    protected $primaryKey       = 'idzona';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nombre',
        'idparroquia',
    ];

    // Validation
    protected $validationRules = [
        'idzona'      => 'permit_empty|is_natural_no_zero',
        'nombre'      => 'required|min_length[2]|max_length[50]',
        'idparroquia' => 'required|is_not_unique[parroquia.idparroquia]',
    ];

    protected $validationMessages = [
        'nombre' => [
            'required'   => 'El nombre de la zona es obligatorio.',
            'min_length' => 'El nombre de la zona debe tener al menos 2 caracteres.',
            'max_length' => 'El nombre de la zona no puede exceder los 50 caracteres.',
        ],
        'idparroquia' => [
            'required'      => 'Debe seleccionar una parroquia para la zona.',
            'is_not_unique' => 'La parroquia seleccionada no existe en la base de datos.',
        ],
    ];

    protected $skipValidation = false;

    /**
     * Obtener zonas con los datos de parroquia, cantón y provincia asociados
     */
    public function getZonasDetailed($id = null)
    {
        $builder = $this->select('zona.*, parroquia.nombre AS parroquia_nombre, canton.nombre AS canton_nombre, provincia.nombre AS provincia_nombre')
                        ->join('parroquia', 'parroquia.idparroquia = zona.idparroquia', 'left')
                        ->join('canton', 'canton.idcanton = parroquia.idcanton', 'left')
                        ->join('provincia', 'provincia.idprovincia = canton.idprovincia', 'left');

        if ($id !== null) {
            return $builder->where('zona.idzona', $id)->first();
        }

        return $builder->orderBy('provincia.nombre', 'ASC')
                       ->orderBy('canton.nombre', 'ASC')
                       ->orderBy('parroquia.nombre', 'ASC')
                       ->orderBy('zona.nombre', 'ASC')
                       ->findAll();
    }

    /**
     * Contar cuántos recintos electorales están asociados a una zona específica
     */
    public function countRecintosAsociados(int $idzona): int
    {
        return $this->db->table('recintoelectoral')
                        ->where('idzona', $idzona)
                        ->countAllResults();
    }
}

