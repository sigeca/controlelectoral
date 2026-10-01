<?php

namespace App\Models;

use CodeIgniter\Model;

class DignidadactaModel extends Model
{
    protected $table            = 'dignidadacta';
    protected $primaryKey       = 'iddignidadacta';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'idacta',
        'iddignidad',
        'votacion',
    ];

    // Reglas de validación
    protected $validationRules = [
        'iddignidadacta' => 'permit_empty|is_natural_no_zero',
        'idacta'         => 'required|is_natural_no_zero',
        'iddignidad'     => 'required|is_natural_no_zero',
        'votacion'       => 'required|integer|greater_than_equal_to[0]',
    ];

    protected $validationMessages = [
        'idacta' => [
            'required'           => 'Debe seleccionar un acta electoral.',
            'is_natural_no_zero' => 'El acta seleccionada no es válida.',
        ],
        'iddignidad' => [
            'required'           => 'Debe seleccionar una dignidad/candidato.',
            'is_natural_no_zero' => 'La dignidad seleccionada no es válida.',
        ],
        'votacion' => [
            'required'              => 'El número de votos obtenidos es obligatorio.',
            'integer'               => 'El número de votos debe ser un número entero.',
            'greater_than_equal_to' => 'El número de votos no puede ser negativo.',
        ],
    ];

    protected $skipValidation = false;

    /**
     * Obtener IDs ordenados de todos los registros de dignidadacta
     */
    public function getDignidadactaIdsOrdered(): array
    {
        $rows = $this->select('iddignidadacta')
                     ->orderBy('iddignidadacta', 'ASC')
                     ->findAll();

        return array_column($rows, 'iddignidadacta');
    }

    /**
     * Obtener el detalle completo de votación por dignidad/acta
     */
    public function getDignidadactaDetailed($id = null)
    {
        $builder = $this->select('dignidadacta.*, 
                                 acta.idmeza, acta.totalpapeleta, acta.totalblancos, acta.totalnulos,
                                 meza.numero AS meza_numero, 
                                 recintoelectoral.nombre AS recinto_nombre,
                                 persona.idpersona AS candidato_idpersona,
                                 persona.nombre AS candidato_nombre,
                                 persona.apellidos AS candidato_apellidos,
                                 persona.cedula AS candidato_cedula,
                                 tipodignidad.nombre AS tipodignidad_nombre')
                        ->join('acta', 'acta.idacta = dignidadacta.idacta', 'left')
                        ->join('meza', 'meza.idmeza = acta.idmeza', 'left')
                        ->join('recintoelectoral', 'recintoelectoral.idrecintoelectoral = meza.idrecintoelectoral', 'left')
                        ->join('dignidad', 'dignidad.iddignidad = dignidadacta.iddignidad', 'left')
                        ->join('persona', 'persona.idpersona = dignidad.idpersona', 'left')
                        ->join('tipodignidad', 'tipodignidad.idtipodignidad = dignidad.idtipodignidad', 'left');

        if ($id !== null) {
            return $builder->where('dignidadacta.iddignidadacta', $id)->first();
        }

        return $builder->orderBy('dignidadacta.iddignidadacta', 'ASC')->findAll();
    }

    /**
     * Obtener todos los registros detallados de votos por dignidad y acta
     */
    public function getDignidadactasDetailed(): array
    {
        return $this->getDignidadactaDetailed(null);
    }
}
