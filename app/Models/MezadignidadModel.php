<?php

namespace App\Models;

use CodeIgniter\Model;

class MezadignidadModel extends Model
{
    protected $table            = 'mezadignidad';
    protected $primaryKey       = 'idmezadignidad';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'idmeza',
        'iddignidad',
        'numeropapeleta',
    ];

    // Validation
    protected $validationRules = [
        'idmezadignidad' => 'permit_empty|is_natural_no_zero',
        'idmeza'         => 'required|is_not_unique[meza.idmeza]',
        'iddignidad'     => 'required|is_not_unique[dignidad.iddignidad]',
        'numeropapeleta' => 'required|is_natural',
    ];

    protected $validationMessages = [
        'idmeza' => [
            'required'      => 'Debe seleccionar una mesa electoral.',
            'is_not_unique' => 'La mesa electoral seleccionada no existe en la base de datos.',
        ],
        'iddignidad' => [
            'required'      => 'Debe seleccionar una dignidad/candidatura.',
            'is_not_unique' => 'La dignidad seleccionada no existe en la base de datos.',
        ],
        'numeropapeleta' => [
            'required'   => 'La cantidad de papeletas contadas es obligatoria.',
            'is_natural' => 'La cantidad de papeletas contadas debe ser un número entero mayor o igual a cero.',
        ],
    ];

    protected $skipValidation = false;

    /**
     * Obtener dignidades a elegir por mesa con la cantidad de papeletas contadas,
     * recinto, territorio, candidato y tipo de dignidad
     */
    public function getMezadignidadesDetailed($id = null)
    {
        $builder = $this->select('mezadignidad.*, 
                                  meza.numero AS meza_numero, 
                                  sexo.nombre AS meza_sexo, 
                                  recintoelectoral.nombre AS recinto_nombre, 
                                  zona.nombre AS zona_nombre, 
                                  parroquia.nombre AS parroquia_nombre, 
                                  canton.nombre AS canton_nombre, 
                                  provincia.nombre AS provincia_nombre, 
                                  persona.nombre AS persona_nombre, 
                                  persona.apellidos AS persona_apellidos, 
                                  persona.cedula AS persona_cedula, 
                                  tipodignidad.nombre AS tipodignidad_nombre')
                        ->join('meza', 'meza.idmeza = mezadignidad.idmeza', 'left')
                        ->join('sexo', 'sexo.idsexo = meza.idsexo', 'left')
                        ->join('recintoelectoral', 'recintoelectoral.idrecintoelectoral = meza.idrecintoelectoral', 'left')
                        ->join('zona', 'zona.idzona = recintoelectoral.idzona', 'left')
                        ->join('parroquia', 'parroquia.idparroquia = zona.idparroquia', 'left')
                        ->join('canton', 'canton.idcanton = parroquia.idcanton', 'left')
                        ->join('provincia', 'provincia.idprovincia = canton.idprovincia', 'left')
                        ->join('dignidad', 'dignidad.iddignidad = mezadignidad.iddignidad', 'left')
                        ->join('persona', 'persona.idpersona = dignidad.idpersona', 'left')
                        ->join('tipodignidad', 'tipodignidad.idtipodignidad = dignidad.idtipodignidad', 'left');

        if ($id !== null) {
            return $builder->where('mezadignidad.idmezadignidad', $id)->first();
        }

        return $builder->orderBy('provincia.nombre', 'ASC')
                       ->orderBy('canton.nombre', 'ASC')
                       ->orderBy('recintoelectoral.nombre', 'ASC')
                       ->orderBy('meza.numero', 'ASC')
                       ->orderBy('mezadignidad.numeropapeleta', 'ASC')
                       ->orderBy('tipodignidad.nombre', 'ASC')
                       ->findAll();
    }
}
