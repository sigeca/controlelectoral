<?php

namespace App\Controllers;

use App\Models\CantonModel;
use App\Models\ProvinciaModel;

class CantonController extends BaseController
{
    protected CantonModel $cantonModel;
    protected ProvinciaModel $provinciaModel;

    public function __construct()
    {
        $this->cantonModel    = new CantonModel();
        $this->provinciaModel = new ProvinciaModel();
    }

    /**
     * Listar todos los cantones con su provincia asociada
     */
    public function index()
    {
        $cantones = $this->cantonModel->getCantonesWithProvincia();

        $data = [
            'title'    => 'Gestión de Cantones - Control Electoral',
            'cantones' => $cantones,
        ];

        return view('canton/index', $data);
    }

    /**
     * Formulario para registrar un nuevo cantón
     */
    public function create()
    {
        $provincias = $this->provinciaModel->orderBy('nombre', 'ASC')->findAll();

        $data = [
            'title'      => 'Registrar Nuevo Cantón',
            'provincias' => $provincias,
            'errors'     => session()->getFlashdata('errors') ?? [],
            'old'        => session()->getFlashdata('old') ?? [],
        ];

        return view('canton/create', $data);
    }

    /**
     * Guardar un nuevo cantón
     */
    public function store()
    {
        $postData = [
            'nombre'      => trim((string)$this->request->getPost('nombre')),
            'idprovincia' => $this->request->getPost('idprovincia'),
        ];

        if (! $this->cantonModel->insert($postData)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->cantonModel->errors())
                             ->with('old', $postData);
        }

        return redirect()->to(site_url('canton'))->with('success', 'Cantón registrado exitosamente.');
    }

    /**
     * Formulario para editar un cantón
     */
    public function edit($id = null)
    {
        $canton = $this->cantonModel->find($id);

        if (! $canton) {
            return redirect()->to(site_url('canton'))->with('error', 'El cantón solicitado no existe.');
        }

        $provincias = $this->provinciaModel->orderBy('nombre', 'ASC')->findAll();

        $data = [
            'title'      => 'Editar Cantón: ' . $canton['nombre'],
            'canton'     => $canton,
            'provincias' => $provincias,
            'errors'     => session()->getFlashdata('errors') ?? [],
        ];

        return view('canton/edit', $data);
    }

    /**
     * Actualizar los datos del cantón
     */
    public function update($id = null)
    {
        $canton = $this->cantonModel->find($id);

        if (! $canton) {
            return redirect()->to(site_url('canton'))->with('error', 'El cantón a actualizar no existe.');
        }

        $postData = [
            'idcanton'    => $id,
            'nombre'      => trim((string)$this->request->getPost('nombre')),
            'idprovincia' => $this->request->getPost('idprovincia'),
        ];

        if (! $this->cantonModel->update($id, $postData)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->cantonModel->errors());
        }

        return redirect()->to(site_url('canton'))->with('success', 'Cantón actualizado correctamente.');
    }

    /**
     * Eliminar un cantón
     */
    public function delete($id = null)
    {
        $canton = $this->cantonModel->find($id);

        if (! $canton) {
            return redirect()->to(site_url('canton'))->with('error', 'El cantón no existe.');
        }

        // Proteger clave foránea si tiene parroquias asociadas
        $totalParroquias = $this->cantonModel->countParroquiasAsociadas($id);
        if ($totalParroquias > 0) {
            return redirect()->to(site_url('canton'))->with(
                'error',
                "No se puede eliminar el cantón \"{$canton['nombre']}\" porque tiene {$totalParroquias} parroquia(s) asociada(s)."
            );
        }

        $this->cantonModel->delete($id);

        return redirect()->to(site_url('canton'))->with('success', 'Cantón eliminado exitosamente.');
    }
}
