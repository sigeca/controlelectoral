<?php

namespace App\Controllers;

use App\Models\TipodignidadModel;

class TipodignidadController extends BaseController
{
    protected TipodignidadModel $tipodignidadModel;

    public function __construct()
    {
        $this->tipodignidadModel = new TipodignidadModel();
    }

    /**
     * Listar todos los tipos de dignidad
     */
    public function index()
    {
        $tipos = $this->tipodignidadModel->orderBy('idtipodignidad', 'ASC')->findAll();

        // Agregar conteo de dignidades asociadas
        foreach ($tipos as &$tipo) {
            $tipo['total_dignidades'] = $this->tipodignidadModel->countDignidadesAsociadas((int)$tipo['idtipodignidad']);
        }
        unset($tipo);

        $data = [
            'title' => 'Gestión de Tipos de Dignidad - Control Electoral',
            'tipos' => $tipos,
        ];

        return view('tipodignidad/index', $data);
    }

    /**
     * Formulario para registrar un nuevo tipo de dignidad
     */
    public function create()
    {
        $data = [
            'title'  => 'Registrar Nuevo Tipo de Dignidad',
            'errors' => session()->getFlashdata('errors') ?? [],
            'old'    => session()->getFlashdata('old') ?? [],
        ];

        return view('tipodignidad/create', $data);
    }

    /**
     * Guardar un nuevo tipo de dignidad
     */
    public function store()
    {
        $postData = [
            'nombre' => trim((string)$this->request->getPost('nombre')),
        ];

        if (! $this->tipodignidadModel->insert($postData)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->tipodignidadModel->errors())
                             ->with('old', $postData);
        }

        return redirect()->to(site_url('tipodignidad'))->with('success', 'Tipo de dignidad registrado exitosamente.');
    }

    /**
     * Formulario para editar un tipo de dignidad existente
     */
    public function edit($id = null)
    {
        $tipo = $this->tipodignidadModel->find($id);

        if (! $tipo) {
            return redirect()->to(site_url('tipodignidad'))->with('error', 'El tipo de dignidad no existe.');
        }

        $data = [
            'title'  => 'Editar Tipo de Dignidad: ' . $tipo['nombre'],
            'tipo'   => $tipo,
            'errors' => session()->getFlashdata('errors') ?? [],
        ];

        return view('tipodignidad/edit', $data);
    }

    /**
     * Actualizar los datos del tipo de dignidad
     */
    public function update($id = null)
    {
        $tipo = $this->tipodignidadModel->find($id);

        if (! $tipo) {
            return redirect()->to(site_url('tipodignidad'))->with('error', 'El tipo de dignidad a actualizar no existe.');
        }

        $postData = [
            'idtipodignidad' => $id,
            'nombre'         => trim((string)$this->request->getPost('nombre')),
        ];

        if (! $this->tipodignidadModel->update($id, $postData)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->tipodignidadModel->errors());
        }

        return redirect()->to(site_url('tipodignidad'))->with('success', 'Tipo de dignidad actualizado correctamente.');
    }

    /**
     * Eliminar un tipo de dignidad
     */
    public function delete($id = null)
    {
        $tipo = $this->tipodignidadModel->find($id);

        if (! $tipo) {
            return redirect()->to(site_url('tipodignidad'))->with('error', 'El tipo de dignidad no existe.');
        }

        // Proteger clave foránea
        $totalAsociadas = $this->tipodignidadModel->countDignidadesAsociadas((int)$id);
        if ($totalAsociadas > 0) {
            return redirect()->to(site_url('tipodignidad'))->with(
                'error',
                "No se puede eliminar el tipo de dignidad \"{$tipo['nombre']}\" porque tiene {$totalAsociadas} dignidad(es)/candidatura(s) vinculada(s)."
            );
        }

        $this->tipodignidadModel->delete($id);

        return redirect()->to(site_url('tipodignidad'))->with('success', 'Tipo de dignidad eliminado exitosamente.');
    }
}
