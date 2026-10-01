<?php

namespace App\Models;

use CodeIgniter\Model;

class DignidadModel extends Model
{
    protected $table            = 'dignidad';
    protected $primaryKey       = 'iddignidad';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'idpersona',
        'idtipodignidad',
    ];

    // Validation
    protected $validationRules = [
        'iddignidad'     => 'permit_empty|is_natural_no_zero',
        'idpersona'      => 'required|is_not_unique[persona.idpersona]',
        'idtipodignidad' => 'required|is_not_unique[tipodignidad.idtipodignidad]',
    ];

    protected $validationMessages = [
        'idpersona' => [
            'required'      => 'Debe seleccionar una persona/candidato.',
            'is_not_unique' => 'La persona seleccionada no existe en la base de datos.',
        ],
        'idtipodignidad' => [
            'required'      => 'Debe seleccionar el tipo de dignidad o cargo.',
            'is_not_unique' => 'El tipo de dignidad seleccionado no existe en la base de datos.',
        ],
    ];

    protected $skipValidation = false;

    /**
     * Obtener dignidades/candidaturas con datos de la persona, sexo y tipo de dignidad
     */
    public function getDignidadesDetailed($id = null)
    {
        $builder = $this->select('dignidad.*, persona.cedula AS persona_cedula, persona.nombre AS persona_nombre, persona.apellidos AS persona_apellidos, persona.fechanacimiento, sexo.nombre AS sexo_nombre, tipodignidad.nombre AS tipodignidad_nombre')
                        ->join('persona', 'persona.idpersona = dignidad.idpersona', 'left')
                        ->join('sexo', 'sexo.idsexo = persona.idsexo', 'left')
                        ->join('tipodignidad', 'tipodignidad.idtipodignidad = dignidad.idtipodignidad', 'left');

        if ($id !== null) {
            return $builder->where('dignidad.iddignidad', $id)->first();
        }

        return $builder->orderBy('tipodignidad.nombre', 'ASC')
                       ->orderBy('persona.apellidos', 'ASC')
                       ->orderBy('persona.nombre', 'ASC')
                       ->findAll();
    }

    /**
     * Obtener el listado ordenado de IDs de dignidades electorales
     */
    public function getDignidadIdsOrdered(): array
    {
        $rows = $this->select('iddignidad')->orderBy('iddignidad', 'ASC')->findAll();
        return array_map('intval', array_column($rows, 'iddignidad'));
    }

    /**
     * Obtener los registros de votación en actas para esta dignidad
     */
    public function getVotacionesEnActasDeDignidad(int $iddignidad): array
    {
        return $this->db->table('dignidadacta')
                        ->select('dignidadacta.iddignidadacta, 
                                  dignidadacta.idacta, 
                                  dignidadacta.votacion, 
                                  meza.idmeza, 
                                  meza.numero AS meza_numero, 
                                  sexo.nombre AS sexo_nombre, 
                                  recintoelectoral.nombre AS recinto_nombre')
                        ->join('acta', 'acta.idacta = dignidadacta.idacta', 'left')
                        ->join('meza', 'meza.idmeza = acta.idmeza', 'left')
                        ->join('sexo', 'sexo.idsexo = meza.idsexo', 'left')
                        ->join('recintoelectoral', 'recintoelectoral.idrecintoelectoral = meza.idrecintoelectoral', 'left')
                        ->where('dignidadacta.iddignidad', $iddignidad)
                        ->orderBy('acta.idacta', 'ASC')
                        ->get()
                        ->getResultArray();
    }
}

