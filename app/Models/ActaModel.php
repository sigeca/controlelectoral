<?php

namespace App\Models;

use CodeIgniter\Model;

class ActaModel extends Model
{
    protected $table            = 'acta';
    protected $primaryKey       = 'idacta';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'idmeza',
        'totalpapeleta',
        'totalblancos',
        'totalnulos',
    ];

    // Reglas de validación
    protected $validationRules = [
        'idacta'        => 'permit_empty|is_natural_no_zero',
        'idmeza'        => 'required|is_natural_no_zero',
        'totalpapeleta' => 'required|integer|greater_than_equal_to[0]',
        'totalblancos'  => 'required|integer|greater_than_equal_to[0]',
        'totalnulos'    => 'required|integer|greater_than_equal_to[0]',
    ];

    protected $validationMessages = [
        'idmeza' => [
            'required'              => 'Debe seleccionar una mesa electoral.',
            'is_natural_no_zero'    => 'La mesa seleccionada no es válida.',
        ],
        'totalpapeleta' => [
            'required'              => 'El total de papeletas es obligatorio.',
            'integer'               => 'El total de papeletas debe ser un número entero.',
            'greater_than_equal_to' => 'El total de papeletas no puede ser negativo.',
        ],
        'totalblancos' => [
            'required'              => 'El total de votos en blanco es obligatorio.',
            'integer'               => 'El total de votos en blanco debe ser un número entero.',
            'greater_than_equal_to' => 'El total de votos en blanco no puede ser negativo.',
        ],
        'totalnulos' => [
            'required'              => 'El total de votos nulos es obligatorio.',
            'integer'               => 'El total de votos nulos debe ser un número entero.',
            'greater_than_equal_to' => 'El total de votos nulos no puede ser negativo.',
        ],
    ];

    protected $skipValidation = false;

    /**
     * Obtener IDs ordenados de todas las actas
     */
    public function getActaIdsOrdered(): array
    {
        $rows = $this->select('idacta')
                     ->orderBy('idacta', 'ASC')
                     ->findAll();

        return array_column($rows, 'idacta');
    }

    /**
     * Obtener acta con detalles completos de la mesa, recinto, parroquia, cantón y provincia
     */
    public function getActaDetailed($id = null)
    {
        $builder = $this->select('acta.*, meza.numero AS meza_numero, sexo.nombre AS meza_sexo, 
                                 recintoelectoral.nombre AS recinto_nombre, zona.nombre AS zona_nombre,
                                 parroquia.nombre AS parroquia_nombre, canton.nombre AS canton_nombre, 
                                 provincia.nombre AS provincia_nombre')
                        ->join('meza', 'meza.idmeza = acta.idmeza', 'left')
                        ->join('sexo', 'sexo.idsexo = meza.idsexo', 'left')
                        ->join('recintoelectoral', 'recintoelectoral.idrecintoelectoral = meza.idrecintoelectoral', 'left')
                        ->join('zona', 'zona.idzona = recintoelectoral.idzona', 'left')
                        ->join('parroquia', 'parroquia.idparroquia = zona.idparroquia', 'left')
                        ->join('canton', 'canton.idcanton = parroquia.idcanton', 'left')
                        ->join('provincia', 'provincia.idprovincia = canton.idprovincia', 'left');

        if ($id !== null) {
            return $builder->where('acta.idacta', $id)->first();
        }

        return $builder->orderBy('acta.idacta', 'ASC')->findAll();
    }

    /**
     * Obtener todas las actas detalladas
     */
    public function getActasDetailed(): array
    {
        return $this->getActaDetailed(null);
    }
}
