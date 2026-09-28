<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table            = 'usuario';
    protected $primaryKey       = 'idusuario';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'idpersona',
        'usuario',
        'password',
        'idrolusuario',
    ];

    // Validation
    protected $validationRules = [
        'idusuario'    => 'permit_empty|is_natural_no_zero',
        'idpersona'    => 'required|is_not_unique[persona.idpersona]',
        'usuario'      => 'required|alpha_numeric_punct|min_length[3]|max_length[20]|is_unique[usuario.usuario,idusuario,{idusuario}]',
        'password'     => 'required|min_length[4]|max_length[50]',
        'idrolusuario' => 'required|is_not_unique[rolusuario.idrolusuario]',
    ];

    protected $validationMessages = [
        'idpersona' => [
            'required'      => 'Debe vincular una persona al usuario.',
            'is_not_unique' => 'La persona seleccionada no existe en el sistema.',
        ],
        'usuario' => [
            'required'            => 'El nombre de usuario es obligatorio.',
            'alpha_numeric_punct' => 'El nombre de usuario contiene caracteres no permitidos.',
            'min_length'          => 'El nombre de usuario debe tener al menos 3 caracteres.',
            'max_length'          => 'El nombre de usuario no puede superar los 20 caracteres.',
            'is_unique'           => 'Este nombre de usuario ya está registrado por otra cuenta.',
        ],
        'password' => [
            'required'   => 'La contraseña es obligatoria.',
            'min_length' => 'La contraseña debe tener al menos 4 caracteres.',
            'max_length' => 'La contraseña no puede superar los 50 caracteres.',
        ],
        'idrolusuario' => [
            'required'      => 'Debe seleccionar un rol para el usuario.',
            'is_not_unique' => 'El rol seleccionado no existe en la base de datos.',
        ],
    ];

    protected $skipValidation = false;

    /**
     * Obtener usuarios con datos relacionados de persona y rol
     */
    public function getUsuariosDetailed($id = null)
    {
        $builder = $this->select('usuario.*, persona.cedula, persona.nombre AS persona_nombre, persona.apellidos AS persona_apellidos, rolusuario.nombre AS rol_nombre')
                        ->join('persona', 'persona.idpersona = usuario.idpersona', 'left')
                        ->join('rolusuario', 'rolusuario.idrolusuario = usuario.idrolusuario', 'left');

        if ($id !== null) {
            return $builder->where('usuario.idusuario', $id)->first();
        }

        return $builder->orderBy('usuario.idusuario', 'DESC')->findAll();
    }
}
