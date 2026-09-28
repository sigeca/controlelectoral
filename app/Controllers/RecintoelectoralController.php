<?php

namespace App\Controllers;

use App\Models\RecintoelectoralModel;
use App\Models\ZonaModel;

class RecintoelectoralController extends BaseController
{
    protected RecintoelectoralModel $recintoModel;
    protected ZonaModel $zonaModel;

    public function __construct()
    {
        $this->recintoModel = new RecintoelectoralModel();
        $this->zonaModel    = new ZonaModel();
    }

    /**
     * Listar todos los recintos electorales
     */
    public function index()
    {
        $recintos = $this->recintoModel->getRecintosDetailed();

        $data = [
            'title'    => 'Gestión de Recintos Electorales - Control Electoral',
            'recintos' => $recintos,
        ];

        return view('recintoelectoral/index', $data);
    }

    /**
     * Formulario para registrar un nuevo recinto electoral
     */
    public function create()
    {
        $zonas = $this->zonaModel->getZonasDetailed();

        $data = [
            'title'  => 'Registrar Nuevo Recinto Electoral',
            'zonas'  => $zonas,
            'errors' => session()->getFlashdata('errors') ?? [],
            'old'    => session()->getFlashdata('old') ?? [],
        ];

        return view('recintoelectoral/create', $data);
    }

    /**
     * Guardar un nuevo recinto electoral
     */
    public function store()
    {
        $postData = [
            'nombre'          => trim((string)$this->request->getPost('nombre')),
            'idzona'          => $this->request->getPost('idzona'),
            'numeroelectores' => $this->request->getPost('numeroelectores'),
        ];

        if (! $this->recintoModel->insert($postData)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->recintoModel->errors())
                             ->with('old', $postData);
        }

        return redirect()->to(site_url('recintoelectoral'))->with('success', 'Recinto electoral registrado exitosamente.');
    }

    /**
     * Formulario para editar un recinto electoral
     */
    public function edit($id = null)
    {
        $recinto = $this->recintoModel->find($id);

        if (! $recinto) {
            return redirect()->to(site_url('recintoelectoral'))->with('error', 'El recinto electoral no existe.');
        }

        $zonas = $this->zonaModel->getZonasDetailed();

        $data = [
            'title'   => 'Editar Recinto: ' . $recinto['nombre'],
            'recinto' => $recinto,
            'zonas'   => $zonas,
            'errors'  => session()->getFlashdata('errors') ?? [],
        ];

        return view('recintoelectoral/edit', $data);
    }

    /**
     * Actualizar los datos del recinto electoral
     */
    public function update($id = null)
    {
        $recinto = $this->recintoModel->find($id);

        if (! $recinto) {
            return redirect()->to(site_url('recintoelectoral'))->with('error', 'El recinto electoral a actualizar no existe.');
        }

        $postData = [
            'idrecintoelectoral' => $id,
            'nombre'             => trim((string)$this->request->getPost('nombre')),
            'idzona'             => $this->request->getPost('idzona'),
            'numeroelectores'    => $this->request->getPost('numeroelectores'),
        ];

        if (! $this->recintoModel->update($id, $postData)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->recintoModel->errors());
        }

        return redirect()->to(site_url('recintoelectoral'))->with('success', 'Recinto electoral actualizado correctamente.');
    }

    /**
     * Eliminar un recinto electoral
     */
    public function delete($id = null)
    {
        $recinto = $this->recintoModel->find($id);

        if (! $recinto) {
            return redirect()->to(site_url('recintoelectoral'))->with('error', 'El recinto electoral no existe.');
        }

        $mezasCount = $this->recintoModel->countMezasAsociadas((int)$id);
        if ($mezasCount > 0) {
            return redirect()->to(site_url('recintoelectoral'))
                             ->with('error', "No se puede eliminar el recinto '{$recinto['nombre']}' porque tiene {$mezasCount} mesa(s) electoral(es) asociada(s). Debe reasignar o eliminar las mesas primero.");
        }

        $this->recintoModel->delete($id);

        return redirect()->to(site_url('recintoelectoral'))->with('success', 'Recinto electoral eliminado exitosamente.');
    }
}
