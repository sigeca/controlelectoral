<?php

namespace App\Controllers;

use App\Models\ProvinciaModel;

class ProvinciaController extends BaseController
{
    protected ProvinciaModel $provinciaModel;

    public function __construct()
    {
        $this->provinciaModel = new ProvinciaModel();
    }

    /**
     * Listar todas las provincias
     */
    public function index()
    {
        $provincias = $this->provinciaModel->orderBy('nombre', 'ASC')->findAll();

        foreach ($provincias as &$item) {
            $item['total_cantones'] = $this->provinciaModel->countCantonesAsociados($item['idprovincia']);
        }

        $data = [
            'title'      => 'Gestión de Provincias - Control Electoral',
            'provincias' => $provincias,
        ];

        return view('provincia/index', $data);
    }

    /**
     * Formulario para registrar una nueva provincia
     */
    public function create()
    {
        $data = [
            'title'  => 'Registrar Nueva Provincia',
            'errors' => session()->getFlashdata('errors') ?? [],
            'old'    => session()->getFlashdata('old') ?? [],
        ];

        return view('provincia/create', $data);
    }

    /**
     * Guardar una nueva provincia
     */
    public function store()
    {
        $postData = [
            'nombre' => trim((string)$this->request->getPost('nombre')),
        ];

        if (! $this->provinciaModel->insert($postData)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->provinciaModel->errors())
                             ->with('old', $postData);
        }

        return redirect()->to(site_url('provincia'))->with('success', 'Provincia registrada con éxito.');
    }

    /**
     * Formulario para editar una provincia
     */
    public function edit($id = null)
    {
        $provincia = $this->provinciaModel->find($id);

        if (! $provincia) {
            return redirect()->to(site_url('provincia'))->with('error', 'La provincia no fue encontrada.');
        }

        $data = [
            'title'     => 'Editar Provincia: ' . $provincia['nombre'],
            'provincia' => $provincia,
            'errors'    => session()->getFlashdata('errors') ?? [],
        ];

        return view('provincia/edit', $data);
    }

    /**
     * Actualizar una provincia existente
     */
    public function update($id = null)
    {
        $provincia = $this->provinciaModel->find($id);

        if (! $provincia) {
            return redirect()->to(site_url('provincia'))->with('error', 'La provincia no existe.');
        }

        $postData = [
            'idprovincia' => $id,
            'nombre'      => trim((string)$this->request->getPost('nombre')),
        ];

        if (! $this->provinciaModel->update($id, $postData)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->provinciaModel->errors());
        }

        return redirect()->to(site_url('provincia'))->with('success', 'Provincia actualizada correctamente.');
    }

    /**
     * Eliminar una provincia
     */
    public function delete($id = null)
    {
        $provincia = $this->provinciaModel->find($id);

        if (! $provincia) {
            return redirect()->to(site_url('provincia'))->with('error', 'La provincia a eliminar no existe.');
        }

        // Validar si tiene cantones asociados por clave foránea
        $totalCantones = $this->provinciaModel->countCantonesAsociados($id);
        if ($totalCantones > 0) {
            return redirect()->to(site_url('provincia'))->with(
                'error',
                "No se puede eliminar la provincia \"{$provincia['nombre']}\" porque tiene {$totalCantones} cantón(es) asociado(s)."
            );
        }

        $this->provinciaModel->delete($id);

        return redirect()->to(site_url('provincia'))->with('success', 'Provincia eliminada exitosamente.');
    }
}
