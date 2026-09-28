<?php

namespace App\Controllers;

use App\Models\ParroquiaModel;
use App\Models\CantonModel;

class ParroquiaController extends BaseController
{
    protected ParroquiaModel $parroquiaModel;
    protected CantonModel $cantonModel;

    public function __construct()
    {
        $this->parroquiaModel = new ParroquiaModel();
        $this->cantonModel    = new CantonModel();
    }

    /**
     * Listar todas las parroquias
     */
    public function index()
    {
        $parroquias = $this->parroquiaModel->getParroquiasDetailed();

        $data = [
            'title'      => 'Gestión de Parroquias - Control Electoral',
            'parroquias' => $parroquias,
        ];

        return view('parroquia/index', $data);
    }

    /**
     * Formulario para registrar una nueva parroquia
     */
    public function create()
    {
        $cantones = $this->cantonModel->getCantonesWithProvincia();

        $data = [
            'title'    => 'Registrar Nueva Parroquia',
            'cantones' => $cantones,
            'errors'   => session()->getFlashdata('errors') ?? [],
            'old'      => session()->getFlashdata('old') ?? [],
        ];

        return view('parroquia/create', $data);
    }

    /**
     * Guardar una nueva parroquia
     */
    public function store()
    {
        $postData = [
            'nombre'   => trim((string)$this->request->getPost('nombre')),
            'idcanton' => $this->request->getPost('idcanton'),
        ];

        if (! $this->parroquiaModel->insert($postData)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->parroquiaModel->errors())
                             ->with('old', $postData);
        }

        return redirect()->to(site_url('parroquia'))->with('success', 'Parroquia registrada exitosamente.');
    }

    /**
     * Formulario para editar una parroquia
     */
    public function edit($id = null)
    {
        $parroquia = $this->parroquiaModel->find($id);

        if (! $parroquia) {
            return redirect()->to(site_url('parroquia'))->with('error', 'La parroquia no existe.');
        }

        $cantones = $this->cantonModel->getCantonesWithProvincia();

        $data = [
            'title'     => 'Editar Parroquia: ' . $parroquia['nombre'],
            'parroquia' => $parroquia,
            'cantones'  => $cantones,
            'errors'    => session()->getFlashdata('errors') ?? [],
        ];

        return view('parroquia/edit', $data);
    }

    /**
     * Actualizar los datos de la parroquia
     */
    public function update($id = null)
    {
        $parroquia = $this->parroquiaModel->find($id);

        if (! $parroquia) {
            return redirect()->to(site_url('parroquia'))->with('error', 'La parroquia a actualizar no existe.');
        }

        $postData = [
            'idparroquia' => $id,
            'nombre'      => trim((string)$this->request->getPost('nombre')),
            'idcanton'    => $this->request->getPost('idcanton'),
        ];

        if (! $this->parroquiaModel->update($id, $postData)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->parroquiaModel->errors());
        }

        return redirect()->to(site_url('parroquia'))->with('success', 'Parroquia actualizada correctamente.');
    }

    /**
     * Eliminar una parroquia
     */
    public function delete($id = null)
    {
        $parroquia = $this->parroquiaModel->find($id);

        if (! $parroquia) {
            return redirect()->to(site_url('parroquia'))->with('error', 'La parroquia no existe.');
        }

        $zonasCount = $this->parroquiaModel->countZonasAsociadas((int)$id);
        if ($zonasCount > 0) {
            return redirect()->to(site_url('parroquia'))
                             ->with('error', "No se puede eliminar la parroquia '{$parroquia['nombre']}' porque tiene {$zonasCount} zona(s) electoral(es) asociada(s). Debe reasignar o eliminar las zonas primero.");
        }

        $this->parroquiaModel->delete($id);

        return redirect()->to(site_url('parroquia'))->with('success', 'Parroquia eliminada exitosamente.');
    }
}
