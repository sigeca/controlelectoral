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
     * Obtener IDs ordenados de todas las provincias para navegación
     */
    public function getProvinciaIdsOrdered(): array
    {
        $rows = $this->select('idprovincia')
                     ->orderBy('nombre', 'ASC')
                     ->findAll();

        return array_column($rows, 'idprovincia');
    }

    /**
     * Obtener provincias con conteo de cantones asociados
     */
    public function getProvinciasWithCounts(): array
    {
        $provincias = $this->orderBy('nombre', 'ASC')->findAll();

        foreach ($provincias as &$p) {
            $p['total_cantones'] = $this->countCantonesAsociados($p['idprovincia']);
        }

        return $provincias;
    }

    /**
     * Obtener los cantones pertenecientes a una provincia específica
     */
    public function getCantonesDeProvincia(int $idprovincia): array
    {
        $cantones = $this->db->table('canton')
                             ->where('idprovincia', $idprovincia)
                             ->orderBy('nombre', 'ASC')
                             ->get()
                             ->getResultArray();

        foreach ($cantones as &$c) {
            $c['total_parroquias'] = $this->db->table('parroquia')
                                              ->where('idcanton', $c['idcanton'])
                                              ->countAllResults();
        }

        return $cantones;
    }

    /**
     * Contar cuántos cantones están asociados a esta provincia
     */
    public function countCantonesAsociados($idprovincia): int
    {
        return $this->db->table('canton')->where('idprovincia', $idprovincia)->countAllResults();
    }
}
