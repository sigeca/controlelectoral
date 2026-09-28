<?php

namespace App\Controllers;

use App\Models\UsuarioModel;
use App\Models\PersonaModel;
use App\Models\RolusuarioModel;

class UsuarioController extends BaseController
{
    protected UsuarioModel $usuarioModel;
    protected PersonaModel $personaModel;
    protected RolusuarioModel $rolusuarioModel;

    public function __construct()
    {
        $this->usuarioModel    = new UsuarioModel();
        $this->personaModel    = new PersonaModel();
        $this->rolusuarioModel = new RolusuarioModel();
    }

    /**
     * Listado general de usuarios
     */
    public function index()
    {
        $usuarios = $this->usuarioModel->getUsuariosDetailed();

        $data = [
            'title'    => 'Gestión de Usuarios del Sistema - Control Electoral',
            'usuarios' => $usuarios,
        ];

        return view('usuario/index', $data);
    }

    /**
     * Ver ficha detallada del usuario
     */
    public function show($id = null)
    {
        $usuario = $this->usuarioModel->getUsuariosDetailed($id);

        if (! $usuario) {
            return redirect()->to(site_url('usuario'))->with('error', 'El usuario no fue encontrado.');
        }

        $data = [
            'title'   => 'Ficha de Usuario: ' . $usuario['usuario'],
            'usuario' => $usuario,
        ];

        return view('usuario/show', $data);
    }

    /**
     * Formulario para crear un nuevo usuario
     */
    public function create()
    {
        $personas = $this->personaModel->orderBy('apellidos', 'ASC')->findAll();
        $roles    = $this->rolusuarioModel->orderBy('nombre', 'ASC')->findAll();

        $data = [
            'title'    => 'Registrar Nuevo Usuario',
            'personas' => $personas,
            'roles'    => $roles,
            'errors'   => session()->getFlashdata('errors') ?? [],
            'old'      => session()->getFlashdata('old') ?? [],
        ];

        return view('usuario/create', $data);
    }

    /**
     * Guardar el nuevo usuario
     */
    public function store()
    {
        $postData = [
            'idpersona'    => $this->request->getPost('idpersona'),
            'usuario'      => trim((string)$this->request->getPost('usuario')),
            'password'     => (string)$this->request->getPost('password'),
            'idrolusuario' => $this->request->getPost('idrolusuario'),
        ];

        if (! $this->usuarioModel->insert($postData)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->usuarioModel->errors())
                             ->with('old', $postData);
        }

        return redirect()->to(site_url('usuario'))->with('success', 'Usuario registrado exitosamente.');
    }

    /**
     * Formulario para editar un usuario
     */
    public function edit($id = null)
    {
        $usuario = $this->usuarioModel->find($id);

        if (! $usuario) {
            return redirect()->to(site_url('usuario'))->with('error', 'El usuario no existe.');
        }

        $personas = $this->personaModel->orderBy('apellidos', 'ASC')->findAll();
        $roles    = $this->rolusuarioModel->orderBy('nombre', 'ASC')->findAll();

        $data = [
            'title'    => 'Editar Usuario: ' . $usuario['usuario'],
            'usuario'  => $usuario,
            'personas' => $personas,
            'roles'    => $roles,
            'errors'   => session()->getFlashdata('errors') ?? [],
        ];

        return view('usuario/edit', $data);
    }

    /**
     * Actualizar los datos del usuario
     */
    public function update($id = null)
    {
        $usuario = $this->usuarioModel->find($id);

        if (! $usuario) {
            return redirect()->to(site_url('usuario'))->with('error', 'El usuario a actualizar no existe.');
        }

        $newPassword = (string)$this->request->getPost('password');

        $postData = [
            'idusuario'    => $id,
            'idpersona'    => $this->request->getPost('idpersona'),
            'usuario'      => trim((string)$this->request->getPost('usuario')),
            'idrolusuario' => $this->request->getPost('idrolusuario'),
        ];

        // Si se ingresó una nueva contraseña se actualiza, de lo contrario se conserva la actual
        if (trim($newPassword) !== '') {
            $postData['password'] = $newPassword;
        } else {
            // Mantener contraseña actual sin disparar validación de obligatoriedad en password
            $postData['password'] = $usuario['password'];
        }

        if (! $this->usuarioModel->update($id, $postData)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->usuarioModel->errors());
        }

        return redirect()->to(site_url('usuario'))->with('success', 'Usuario actualizado correctamente.');
    }

    /**
     * Eliminar un usuario
     */
    public function delete($id = null)
    {
        $usuario = $this->usuarioModel->find($id);

        if (! $usuario) {
            return redirect()->to(site_url('usuario'))->with('error', 'El usuario no existe.');
        }

        $this->usuarioModel->delete($id);

        return redirect()->to(site_url('usuario'))->with('success', 'Usuario eliminado exitosamente.');
    }
}
