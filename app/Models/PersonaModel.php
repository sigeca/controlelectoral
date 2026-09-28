<?php

namespace App\Models;

use CodeIgniter\Model;

class PersonaModel extends Model
{
    protected $table            = 'persona';
    protected $primaryKey       = 'idpersona';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'cedula',
        'nombre',
        'apellidos',
        'fechanacimiento',
        'idsexo',
    ];

    // Validation
    protected $validationRules = [
        'idpersona'       => 'permit_empty|is_natural_no_zero',
        'cedula'          => 'required|min_length[5]|max_length[15]|is_unique[persona.cedula,idpersona,{idpersona}]',
        'nombre'          => 'required|min_length[2]|max_length[50]',
        'apellidos'       => 'required|min_length[2]|max_length[50]',
        'fechanacimiento' => 'required|valid_date[Y-m-d]',
        'idsexo'          => 'required|is_not_unique[sexo.idsexo]',
    ];

    protected $validationMessages = [
        'cedula' => [
            'required'   => 'La cédula es obligatoria.',
            'min_length' => 'La cédula debe contener al menos 5 caracteres.',
            'max_length' => 'La cédula no puede superar los 15 caracteres.',
            'is_unique'  => 'Ya existe una persona registrada con este número de cédula.',
        ],
        'nombre' => [
            'required'   => 'El nombre es obligatorio.',
            'min_length' => 'El nombre debe contener al menos 2 caracteres.',
            'max_length' => 'El nombre no puede superar los 50 caracteres.',
        ],
        'apellidos' => [
            'required'   => 'Los apellidos son obligatorios.',
            'min_length' => 'Los apellidos deben contener al menos 2 caracteres.',
            'max_length' => 'Los apellidos no pueden superar los 50 caracteres.',
        ],
        'fechanacimiento' => [
            'required'   => 'La fecha de nacimiento es obligatoria.',
            'valid_date' => 'La fecha de nacimiento debe tener un formato válido (AAAA-MM-DD).',
        ],
        'idsexo' => [
            'required'      => 'Debe seleccionar una opción de sexo.',
            'is_not_unique' => 'El sexo seleccionado no existe en la base de datos.',
        ],
    ];

    protected $skipValidation = false;

    /**
     * Obtener registros de personas con la descripción de la tabla sexo
     */
    public function getPersonasWithSexo($id = null)
    {
        $builder = $this->select('persona.*, sexo.nombre AS sexo_nombre')
                        ->join('sexo', 'sexo.idsexo = persona.idsexo', 'left');

        if ($id !== null) {
            return $builder->where('persona.idpersona', $id)->first();
        }

        return $builder->orderBy('persona.idpersona', 'DESC')->findAll();
    }

    /**
     * Contar cuántos usuarios están vinculados a esta persona
     */
    public function countUsuariosAsociados($idpersona)
    {
        $db = \Config\Database::connect();
        return $db->table('usuario')->where('idpersona', $idpersona)->countAllResults();
    }

    /**
     * Contar cuántas dignidades/candidaturas están asociadas a esta persona
     */
    public function countDignidadesAsociadas($idpersona): int
    {
        $db = \Config\Database::connect();
        return $db->table('dignidad')->where('idpersona', $idpersona)->countAllResults();
    }

    /**
     * Obtener los IDs de personas ordenados de forma ascendente
     */
    public function getPersonaIdsOrdered(): array
    {
        $rows = $this->select('idpersona')->orderBy('idpersona', 'ASC')->findAll();
        return array_map('intval', array_column($rows, 'idpersona'));
    }

    /**
     * Obtener la información del usuario del sistema asociado a esta persona (si existe)
     */
    public function getUsuarioDePersona(int $idpersona)
    {
        $db = \Config\Database::connect();
        return $db->table('usuario')
                  ->select('usuario.idusuario, usuario.usuario, rolusuario.nombre AS rol_nombre')
                  ->join('rolusuario', 'rolusuario.idrolusuario = usuario.idrolusuario', 'left')
                  ->where('usuario.idpersona', $idpersona)
                  ->get()
                  ->getRowArray();
    }

    /**
     * Obtener las candidaturas / dignidades asignadas a esta persona
     */
    public function getDignidadesDePersona(int $idpersona): array
    {
        $db = \Config\Database::connect();
        return $db->table('dignidad')
                  ->select('dignidad.iddignidad, tipodignidad.nombre AS tipodignidad_nombre')
                  ->join('tipodignidad', 'tipodignidad.idtipodignidad = dignidad.idtipodignidad', 'left')
                  ->where('dignidad.idpersona', $idpersona)
                  ->orderBy('tipodignidad.nombre', 'ASC')
                  ->get()
                  ->getResultArray();
    }
}

