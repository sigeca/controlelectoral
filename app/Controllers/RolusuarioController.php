<?php

namespace App\Controllers;

use App\Models\RolusuarioModel;

class RolusuarioController extends BaseController
{
    protected RolusuarioModel $rolusuarioModel;

    public function __construct()
    {
        $this->rolusuarioModel = new RolusuarioModel();
    }

    /**
     * Listar todos los roles de usuario
     */
    public function index()
    {
        $roles = $this->rolusuarioModel->orderBy('idrolusuario', 'ASC')->findAll();

        foreach ($roles as &$item) {
            $item['total_usuarios'] = $this->rolusuarioModel->countUsuariosAsignados($item['idrolusuario']);
        }

        $data = [
            'title' => 'Gestión de Roles de Usuario - Control Electoral',
            'roles' => $roles,
        ];

        return view('rolusuario/index', $data);
    }

    /**
     * Formulario para crear un nuevo rol
     */
    public function create()
    {
        $data = [
            'title'  => 'Registrar Nuevo Rol de Usuario',
            'errors' => session()->getFlashdata('errors') ?? [],
            'old'    => session()->getFlashdata('old') ?? [],
        ];

        return view('rolusuario/create', $data);
    }

    /**
     * Guardar un nuevo rol
     */
    public function store()
    {
        $postData = [
            'nombre' => trim((string)$this->request->getPost('nombre')),
        ];

        if (! $this->rolusuarioModel->insert($postData)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->rolusuarioModel->errors())
                             ->with('old', $postData);
        }

        return redirect()->to(site_url('rolusuario'))->with('success', 'Rol de usuario creado con éxito.');
    }

    /**
     * Formulario para editar un rol
     */
    public function edit($id = null)
    {
        $rol = $this->rolusuarioModel->find($id);

        if (! $rol) {
            return redirect()->to(site_url('rolusuario'))->with('error', 'El rol solicitado no existe.');
        }

        $data = [
            'title'  => 'Editar Rol: ' . $rol['nombre'],
            'rol'    => $rol,
            'errors' => session()->getFlashdata('errors') ?? [],
        ];

        return view('rolusuario/edit', $data);
    }

    /**
     * Actualizar los datos de un rol
     */
    public function update($id = null)
    {
        $rol = $this->rolusuarioModel->find($id);

        if (! $rol) {
            return redirect()->to(site_url('rolusuario'))->with('error', 'El rol a actualizar no existe.');
        }

        $postData = [
            'idrolusuario' => $id,
            'nombre'       => trim((string)$this->request->getPost('nombre')),
        ];

        if (! $this->rolusuarioModel->update($id, $postData)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->rolusuarioModel->errors());
        }

        return redirect()->to(site_url('rolusuario'))->with('success', 'Rol de usuario actualizado correctamente.');
    }

    /**
     * Eliminar un rol
     */
    public function delete($id = null)
    {
        $rol = $this->rolusuarioModel->find($id);

        if (! $rol) {
            return redirect()->to(site_url('rolusuario'))->with('error', 'El rol a eliminar no existe.');
        }

        // Proteger clave foránea si tiene usuarios asignados
        $totalUsuarios = $this->rolusuarioModel->countUsuariosAsignados($id);
        if ($totalUsuarios > 0) {
            return redirect()->to(site_url('rolusuario'))->with(
                'error',
                "No se puede eliminar el rol \"{$rol['nombre']}\" porque está asignado a {$totalUsuarios} usuario(s)."
            );
        }

        $this->rolusuarioModel->delete($id);

        return redirect()->to(site_url('rolusuario'))->with('success', 'Rol eliminado exitosamente.');
    }
}
