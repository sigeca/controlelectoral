<?php

namespace App\Models;

use CodeIgniter\Model;

class MezaModel extends Model
{
    protected $table            = 'meza';
    protected $primaryKey       = 'idmeza';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'numero',
        'idsexo',
        'idrecintoelectoral',
    ];

    // Validation
    protected $validationRules = [
        'idmeza'             => 'permit_empty|is_natural_no_zero',
        'numero'             => 'required|is_natural_no_zero',
        'idsexo'             => 'required|is_not_unique[sexo.idsexo]',
        'idrecintoelectoral' => 'required|is_not_unique[recintoelectoral.idrecintoelectoral]',
    ];

    protected $validationMessages = [
        'numero' => [
            'required'           => 'El número de mesa es obligatorio.',
            'is_natural_no_zero' => 'El número de mesa debe ser un número entero positivo mayor a cero.',
        ],
        'idsexo' => [
            'required'      => 'Debe seleccionar el género/sexo asignado a la mesa.',
            'is_not_unique' => 'El sexo seleccionado no existe en la base de datos.',
        ],
        'idrecintoelectoral' => [
            'required'      => 'Debe seleccionar un recinto electoral para la mesa.',
            'is_not_unique' => 'El recinto electoral seleccionado no existe en la base de datos.',
        ],
    ];

    protected $skipValidation = false;

    /**
     * Obtener mesas electorales con datos de sexo, recinto, zona, parroquia, cantón y provincia
     */
    public function getMezasDetailed($id = null)
    {
        $builder = $this->select('meza.*, sexo.nombre AS sexo_nombre, recintoelectoral.nombre AS recinto_nombre, recintoelectoral.numeroelectores AS recinto_electores, zona.nombre AS zona_nombre, parroquia.nombre AS parroquia_nombre, canton.nombre AS canton_nombre, provincia.nombre AS provincia_nombre')
                        ->join('sexo', 'sexo.idsexo = meza.idsexo', 'left')
                        ->join('recintoelectoral', 'recintoelectoral.idrecintoelectoral = meza.idrecintoelectoral', 'left')
                        ->join('zona', 'zona.idzona = recintoelectoral.idzona', 'left')
                        ->join('parroquia', 'parroquia.idparroquia = zona.idparroquia', 'left')
                        ->join('canton', 'canton.idcanton = parroquia.idcanton', 'left')
                        ->join('provincia', 'provincia.idprovincia = canton.idprovincia', 'left');

        if ($id !== null) {
            return $builder->where('meza.idmeza', $id)->first();
        }

        return $builder->orderBy('provincia.nombre', 'ASC')
                       ->orderBy('canton.nombre', 'ASC')
                       ->orderBy('parroquia.nombre', 'ASC')
                       ->orderBy('zona.nombre', 'ASC')
                       ->orderBy('recintoelectoral.nombre', 'ASC')
                       ->orderBy('meza.numero', 'ASC')
                       ->orderBy('sexo.nombre', 'ASC')
                       ->findAll();
    }

    /**
     * Obtener el listado ordenado de IDs de mesas electorales
     */
    public function getMezaIdsOrdered(): array
    {
        $rows = $this->select('idmeza')->orderBy('idmeza', 'ASC')->findAll();
        return array_map('intval', array_column($rows, 'idmeza'));
    }

    /**
     * Obtener las actas de escrutinio registradas para una mesa específica
     */
    public function getActasDeMeza(int $idmeza): array
    {
        return $this->db->table('acta')
                        ->where('idmeza', $idmeza)
                        ->orderBy('idacta', 'ASC')
                        ->get()
                        ->getResultArray();
    }

    /**
     * Obtener las actas agrupadas por mesa (para listado general)
     */
    public function getActasPorTodasLasMesas(): array
    {
        $rows = $this->db->table('acta')
                         ->select('idmeza, idacta, totalpapeleta, totalblancos, totalnulos')
                         ->orderBy('idacta', 'ASC')
                         ->get()
                         ->getResultArray();

        $agrupados = [];
        foreach ($rows as $r) {
            $agrupados[$r['idmeza']][] = $r;
        }

        return $agrupados;
    }

    /**
     * Contar la cantidad de actas asociadas a una mesa
     */
    public function countActasAsociadas(int $idmeza): int
    {
        return $this->db->table('acta')
                        ->where('idmeza', $idmeza)
                        ->countAllResults();
    }
}

