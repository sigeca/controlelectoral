<?php

namespace App\Controllers;

use App\Models\SexoModel;

class SexoController extends BaseController
{
    protected SexoModel $sexoModel;

    public function __construct()
    {
        $this->sexoModel = new SexoModel();
    }

    /**
     * Listar todos los registros de sexo
     */
    public function index()
    {
        $sexos = $this->sexoModel->orderBy('idsexo', 'ASC')->findAll();

        // Agregar contador de personas vinculadas a cada sexo
        foreach ($sexos as &$item) {
            $item['total_personas'] = $this->sexoModel->countPersonasAsociadas($item['idsexo']);
        }

        $data = [
            'title' => 'Gestión de Sexos - Control Electoral',
            'sexos' => $sexos,
        ];

        return view('sexo/index', $data);
    }

    /**
     * Mostrar formulario para crear un nuevo sexo
     */
    public function create()
    {
        $data = [
            'title'  => 'Registrar Nuevo Sexo',
            'errors' => session()->getFlashdata('errors') ?? [],
            'old'    => session()->getFlashdata('old') ?? [],
        ];

        return view('sexo/create', $data);
    }

    /**
     * Guardar el nuevo sexo en la base de datos
     */
    public function store()
    {
        $postData = [
            'nombre' => trim((string)$this->request->getPost('nombre')),
        ];

        if (! $this->sexoModel->insert($postData)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->sexoModel->errors())
                             ->with('old', $postData);
        }

        return redirect()->to(site_url('sexo'))->with('success', 'Sexo registrado con éxito.');
    }

    /**
     * Mostrar formulario para editar un sexo
     */
    public function edit($id = null)
    {
        $sexo = $this->sexoModel->find($id);

        if (! $sexo) {
            return redirect()->to(site_url('sexo'))->with('error', 'El registro de sexo no fue encontrado.');
        }

        $data = [
            'title'  => 'Editar Sexo #' . $sexo['idsexo'],
            'sexo'   => $sexo,
            'errors' => session()->getFlashdata('errors') ?? [],
        ];

        return view('sexo/edit', $data);
    }

    /**
     * Actualizar los datos del sexo
     */
    public function update($id = null)
    {
        $sexo = $this->sexoModel->find($id);

        if (! $sexo) {
            return redirect()->to(site_url('sexo'))->with('error', 'El registro de sexo no existe.');
        }

        $postData = [
            'idsexo' => $id,
            'nombre' => trim((string)$this->request->getPost('nombre')),
        ];

        if (! $this->sexoModel->update($id, $postData)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->sexoModel->errors());
        }

        return redirect()->to(site_url('sexo'))->with('success', 'Sexo actualizado correctamente.');
    }

    /**
     * Eliminar un registro de sexo
     */
    public function delete($id = null)
    {
        $sexo = $this->sexoModel->find($id);

        if (! $sexo) {
            return redirect()->to(site_url('sexo'))->with('error', 'El registro no existe.');
        }

        // Validar si tiene personas asociadas por la clave foránea
        $totalAsociadas = $this->sexoModel->countPersonasAsociadas($id);
        if ($totalAsociadas > 0) {
            return redirect()->to(site_url('sexo'))->with(
                'error',
                "No se puede eliminar el sexo \"{$sexo['nombre']}\" porque está asignado a {$totalAsociadas} persona(s)."
            );
        }

        // Validar si tiene mesas asociadas por la clave foránea
        $totalMezas = $this->sexoModel->countMezasAsociadas($id);
        if ($totalMezas > 0) {
            return redirect()->to(site_url('sexo'))->with(
                'error',
                "No se puede eliminar el sexo \"{$sexo['nombre']}\" porque está asignado a {$totalMezas} mesa(s) electoral(es)."
            );
        }

        $this->sexoModel->delete($id);

        return redirect()->to(site_url('sexo'))->with('success', 'Registro de sexo eliminado con éxito.');
    }
}
