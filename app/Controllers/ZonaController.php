<?php

namespace App\Controllers;

use App\Models\ZonaModel;
use App\Models\ParroquiaModel;

class ZonaController extends BaseController
{
    protected ZonaModel $zonaModel;
    protected ParroquiaModel $parroquiaModel;

    public function __construct()
    {
        $this->zonaModel      = new ZonaModel();
        $this->parroquiaModel = new ParroquiaModel();
    }

    /**
     * Listar todas las zonas electorales
     */
    public function index()
    {
        $zonas = $this->zonaModel->getZonasDetailed();

        $data = [
            'title' => 'Gestión de Zonas - Control Electoral',
            'zonas' => $zonas,
        ];

        return view('zona/index', $data);
    }

    /**
     * Formulario para registrar una nueva zona
     */
    public function create()
    {
        $parroquias = $this->parroquiaModel->getParroquiasDetailed();

        $data = [
            'title'      => 'Registrar Nueva Zona Electoral',
            'parroquias' => $parroquias,
            'errors'     => session()->getFlashdata('errors') ?? [],
            'old'        => session()->getFlashdata('old') ?? [],
        ];

        return view('zona/create', $data);
    }

    /**
     * Guardar una nueva zona
     */
    public function store()
    {
        $postData = [
            'nombre'      => trim((string)$this->request->getPost('nombre')),
            'idparroquia' => $this->request->getPost('idparroquia'),
        ];

        if (! $this->zonaModel->insert($postData)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->zonaModel->errors())
                             ->with('old', $postData);
        }

        return redirect()->to(site_url('zona'))->with('success', 'Zona electoral registrada exitosamente.');
    }

    /**
     * Formulario para editar una zona existente
     */
    public function edit($id = null)
    {
        $zona = $this->zonaModel->find($id);

        if (! $zona) {
            return redirect()->to(site_url('zona'))->with('error', 'La zona electoral no existe.');
        }

        $parroquias = $this->parroquiaModel->getParroquiasDetailed();

        $data = [
            'title'      => 'Editar Zona: ' . $zona['nombre'],
            'zona'       => $zona,
            'parroquias' => $parroquias,
            'errors'     => session()->getFlashdata('errors') ?? [],
        ];

        return view('zona/edit', $data);
    }

    /**
     * Actualizar los datos de la zona
     */
    public function update($id = null)
    {
        $zona = $this->zonaModel->find($id);

        if (! $zona) {
            return redirect()->to(site_url('zona'))->with('error', 'La zona a actualizar no existe.');
        }

        $postData = [
            'idzona'      => $id,
            'nombre'      => trim((string)$this->request->getPost('nombre')),
            'idparroquia' => $this->request->getPost('idparroquia'),
        ];

        if (! $this->zonaModel->update($id, $postData)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->zonaModel->errors());
        }

        return redirect()->to(site_url('zona'))->with('success', 'Zona electoral actualizada correctamente.');
    }

    /**
     * Eliminar una zona electoral
     */
    public function delete($id = null)
    {
        $zona = $this->zonaModel->find($id);

        if (! $zona) {
            return redirect()->to(site_url('zona'))->with('error', 'La zona electoral no existe.');
        }

        $recintosCount = $this->zonaModel->countRecintosAsociados((int)$id);
        if ($recintosCount > 0) {
            return redirect()->to(site_url('zona'))
                             ->with('error', "No se puede eliminar la zona '{$zona['nombre']}' porque tiene {$recintosCount} recinto(s) electoral(es) asociado(s). Debe reasignar o eliminar los recintos primero.");
        }

        $this->zonaModel->delete($id);

        return redirect()->to(site_url('zona'))->with('success', 'Zona electoral eliminada exitosamente.');
    }
}
