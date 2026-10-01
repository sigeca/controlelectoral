<?php

namespace App\Models;

use CodeIgniter\Model;

class RecintoelectoralModel extends Model
{
    protected $table            = 'recintoelectoral';
    protected $primaryKey       = 'idrecintoelectoral';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nombre',
        'idzona',
        'numeroelectores',
    ];

    // Validation
    protected $validationRules = [
        'idrecintoelectoral' => 'permit_empty|is_natural_no_zero',
        'nombre'             => 'required|min_length[3]|max_length[150]',
        'idzona'             => 'required|is_not_unique[zona.idzona]',
        'numeroelectores'    => 'required|is_natural',
    ];

    protected $validationMessages = [
        'nombre' => [
            'required'   => 'El nombre del recinto electoral es obligatorio.',
            'min_length' => 'El nombre del recinto electoral debe tener al menos 3 caracteres.',
            'max_length' => 'El nombre del recinto electoral no puede exceder los 150 caracteres.',
        ],
        'idzona' => [
            'required'      => 'Debe seleccionar una zona electoral para el recinto.',
            'is_not_unique' => 'La zona electoral seleccionada no existe en la base de datos.',
        ],
        'numeroelectores' => [
            'required'   => 'El número de electores es obligatorio.',
            'is_natural' => 'El número de electores debe ser un número entero no negativo (cero o mayor).',
        ],
    ];

    protected $skipValidation = false;

    /**
     * Obtener recintos electorales con los datos de zona, parroquia, cantón y provincia asociados
     */
    public function getRecintosDetailed($id = null)
    {
        $builder = $this->select('recintoelectoral.*, zona.nombre AS zona_nombre, parroquia.nombre AS parroquia_nombre, canton.nombre AS canton_nombre, provincia.nombre AS provincia_nombre')
                        ->join('zona', 'zona.idzona = recintoelectoral.idzona', 'left')
                        ->join('parroquia', 'parroquia.idparroquia = zona.idparroquia', 'left')
                        ->join('canton', 'canton.idcanton = parroquia.idcanton', 'left')
                        ->join('provincia', 'provincia.idprovincia = canton.idprovincia', 'left');

        if ($id !== null) {
            return $builder->where('recintoelectoral.idrecintoelectoral', $id)->first();
        }

        return $builder->orderBy('provincia.nombre', 'ASC')
                       ->orderBy('canton.nombre', 'ASC')
                       ->orderBy('parroquia.nombre', 'ASC')
                       ->orderBy('zona.nombre', 'ASC')
                       ->orderBy('recintoelectoral.nombre', 'ASC')
                       ->findAll();
    }

    /**
     * Obtener lista ordenada de IDs de recintos electorales para la navegación por registros
     */
    public function getRecintoIdsOrdered(): array
    {
        $rows = $this->select('recintoelectoral.idrecintoelectoral')
                     ->join('zona', 'zona.idzona = recintoelectoral.idzona', 'left')
                     ->join('parroquia', 'parroquia.idparroquia = zona.idparroquia', 'left')
                     ->join('canton', 'canton.idcanton = parroquia.idcanton', 'left')
                     ->join('provincia', 'provincia.idprovincia = canton.idprovincia', 'left')
                     ->orderBy('provincia.nombre', 'ASC')
                     ->orderBy('canton.nombre', 'ASC')
                     ->orderBy('parroquia.nombre', 'ASC')
                     ->orderBy('zona.nombre', 'ASC')
                     ->orderBy('recintoelectoral.nombre', 'ASC')
                     ->findAll();

        return array_column($rows, 'idrecintoelectoral');
    }

    /**
     * Obtener mesas electorales vinculadas a un recinto específico
     */
    public function getMezasDeRecinto(int $idrecintoelectoral): array
    {
        return $this->db->table('meza')
                        ->select('meza.*, sexo.nombre AS sexo_nombre')
                        ->join('sexo', 'sexo.idsexo = meza.idsexo', 'left')
                        ->where('meza.idrecintoelectoral', $idrecintoelectoral)
                        ->orderBy('meza.numero', 'ASC')
                        ->get()
                        ->getResultArray();
    }

    /**
     * Contar cuántas mesas están asociadas a un recinto electoral específico
     */
    public function countMezasAsociadas(int $idrecintoelectoral): int
    {
        return $this->db->table('meza')
                        ->where('idrecintoelectoral', $idrecintoelectoral)
                        ->countAllResults();
    }
}
