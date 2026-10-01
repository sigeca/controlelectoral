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
        'idtipoparroquia',
        'iddistrito',
    ];

    // Validation
    protected $validationRules = [
        'idparroquia'     => 'permit_empty|is_natural_no_zero',
        'nombre'          => 'required|min_length[2]|max_length[50]',
        'idcanton'        => 'required|is_not_unique[canton.idcanton]',
        'idtipoparroquia' => 'permit_empty|is_not_unique[tipoparroquia.idtipoparroquia]',
        'iddistrito'      => 'permit_empty|is_not_unique[distrito.iddistrito]',
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
        'idtipoparroquia' => [
            'is_not_unique' => 'El tipo de parroquia seleccionado no existe en la base de datos.',
        ],
        'iddistrito' => [
            'is_not_unique' => 'El distrito seleccionado no existe en la base de datos.',
        ],
    ];

    protected $skipValidation = false;

    /**
     * Obtener parroquias con los datos del cantón, provincia, tipo de parroquia y distrito asociados
     */
    public function getParroquiasDetailed($id = null)
    {
        $builder = $this->select('parroquia.*, canton.nombre AS canton_nombre, provincia.nombre AS provincia_nombre, tipoparroquia.nombre AS tipoparroquia_nombre, distrito.nombre AS distrito_nombre')
                        ->join('canton', 'canton.idcanton = parroquia.idcanton', 'left')
                        ->join('provincia', 'provincia.idprovincia = canton.idprovincia', 'left')
                        ->join('tipoparroquia', 'tipoparroquia.idtipoparroquia = parroquia.idtipoparroquia', 'left')
                        ->join('distrito', 'distrito.iddistrito = parroquia.iddistrito', 'left');

        if ($id !== null) {
            return $builder->where('parroquia.idparroquia', $id)->first();
        }

        return $builder->orderBy('provincia.nombre', 'ASC')
                       ->orderBy('canton.nombre', 'ASC')
                       ->orderBy('parroquia.nombre', 'ASC')
                       ->findAll();
    }

    /**
     * Obtener lista ordenada de IDs de parroquias para la navegación por registros
     */
    public function getParroquiaIdsOrdered(): array
    {
        $rows = $this->select('parroquia.idparroquia')
                     ->join('canton', 'canton.idcanton = parroquia.idcanton', 'left')
                     ->join('provincia', 'provincia.idprovincia = canton.idprovincia', 'left')
                     ->orderBy('provincia.nombre', 'ASC')
                     ->orderBy('canton.nombre', 'ASC')
                     ->orderBy('parroquia.nombre', 'ASC')
                     ->findAll();

        return array_column($rows, 'idparroquia');
    }

    /**
     * Obtener parroquias con conteo de recintos y zonas asociadas
     */
    public function getParroquiasWithCounts(): array
    {
        $parroquias = $this->getParroquiasDetailed();

        foreach ($parroquias as &$p) {
            $p['total_recintos'] = $this->countRecintosAsociados($p['idparroquia']);
            $p['total_zonas']    = $this->countZonasAsociadas($p['idparroquia']);
        }

        return $parroquias;
    }

    /**
     * Obtener recintos electorales pertenecientes a una parroquia (a través de sus zonas)
     */
    public function getRecintosDeParroquia(int $idparroquia): array
    {
        return $this->db->table('recintoelectoral')
                        ->select('recintoelectoral.*, zona.nombre AS zona_nombre')
                        ->join('zona', 'zona.idzona = recintoelectoral.idzona', 'inner')
                        ->where('zona.idparroquia', $idparroquia)
                        ->orderBy('zona.nombre', 'ASC')
                        ->orderBy('recintoelectoral.nombre', 'ASC')
                        ->get()
                        ->getResultArray();
    }

    /**
     * Contar cuántos recintos electorales están asociados a una parroquia (a través de sus zonas)
     */
    public function countRecintosAsociados(int $idparroquia): int
    {
        return $this->db->table('recintoelectoral')
                        ->join('zona', 'zona.idzona = recintoelectoral.idzona', 'inner')
                        ->where('zona.idparroquia', $idparroquia)
                        ->countAllResults();
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
