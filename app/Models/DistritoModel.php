<?php

namespace App\Models;

use CodeIgniter\Model;

class DistritoModel extends Model
{
    protected $table            = 'distrito';
    protected $primaryKey       = 'iddistrito';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nombre'];

    // Validation
    protected $validationRules = [
        'iddistrito' => 'permit_empty|is_natural_no_zero',
        'nombre'     => 'required|min_length[2]|max_length[50]|is_unique[distrito.nombre,iddistrito,{iddistrito}]',
    ];

    protected $validationMessages = [
        'nombre' => [
            'required'   => 'El nombre del distrito es obligatorio.',
            'min_length' => 'El nombre del distrito debe tener al menos 2 caracteres.',
            'max_length' => 'El nombre del distrito no puede exceder los 50 caracteres.',
            'is_unique'  => 'Este distrito ya se encuentra registrado.',
        ],
    ];

    protected $skipValidation = false;

    /**
     * Obtener IDs ordenados de todos los distritos
     */
    public function getDistritoIdsOrdered(): array
    {
        $rows = $this->select('iddistrito')
                     ->orderBy('iddistrito', 'ASC')
                     ->findAll();

        return array_column($rows, 'iddistrito');
    }

    /**
     * Obtener distritos con el conteo de parroquias asociadas
     */
    public function getDistritosWithCounts(): array
    {
        $distritos = $this->orderBy('iddistrito', 'ASC')->findAll();

        foreach ($distritos as &$d) {
            $d['total_parroquias'] = $this->countParroquiasAsociadas($d['iddistrito']);
        }

        return $distritos;
    }

    /**
     * Obtener todas las parroquias pertenecientes a un distrito específico
     */
    public function getParroquiasDeDistrito(int $iddistrito): array
    {
        return $this->db->table('parroquia')
                        ->select('parroquia.*, canton.nombre AS canton_nombre, provincia.nombre AS provincia_nombre, tipoparroquia.nombre AS tipoparroquia_nombre')
                        ->join('canton', 'canton.idcanton = parroquia.idcanton', 'left')
                        ->join('provincia', 'provincia.idprovincia = canton.idprovincia', 'left')
                        ->join('tipoparroquia', 'tipoparroquia.idtipoparroquia = parroquia.idtipoparroquia', 'left')
                        ->where('parroquia.iddistrito', $iddistrito)
                        ->orderBy('provincia.nombre', 'ASC')
                        ->orderBy('canton.nombre', 'ASC')
                        ->orderBy('parroquia.nombre', 'ASC')
                        ->get()
                        ->getResultArray();
    }

    /**
     * Contar cuántas parroquias están asociadas a un distrito específico
     */
    public function countParroquiasAsociadas(int $iddistrito): int
    {
        return $this->db->table('parroquia')
                        ->where('iddistrito', $iddistrito)
                        ->countAllResults();
    }
}
