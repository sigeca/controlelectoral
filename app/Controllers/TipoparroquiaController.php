<?php

namespace App\Controllers;

use App\Models\TipoparroquiaModel;

class TipoparroquiaController extends BaseController
{
    protected TipoparroquiaModel $tipoparroquiaModel;

    public function __construct()
    {
        $this->tipoparroquiaModel = new TipoparroquiaModel();
    }

    /**
     * Listar todos los tipos de parroquia
     */
    public function index()
    {
        $tipos = $this->tipoparroquiaModel->orderBy('idtipoparroquia', 'ASC')->findAll();

        $data = [
            'title'          => 'Gestión de Tipos de Parroquia - Control Electoral',
            'tiposParroquia' => $tipos,
        ];

        return view('tipoparroquia/index', $data);
    }

    /**
     * Mostrar formulario para crear un nuevo tipo de parroquia
     */
    public function create()
    {
        $data = [
            'title'  => 'Registrar Nuevo Tipo de Parroquia',
            'errors' => session()->getFlashdata('errors') ?? [],
            'old'    => session()->getFlashdata('old') ?? [],
        ];

        return view('tipoparroquia/create', $data);
    }

    /**
     * Guardar el nuevo tipo de parroquia en la base de datos
     */
    public function store()
    {
        $postData = [
            'nombre' => trim((string)$this->request->getPost('nombre')),
        ];

        if (! $this->tipoparroquiaModel->insert($postData)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->tipoparroquiaModel->errors())
                             ->with('old', $postData);
        }

        return redirect()->to(site_url('tipoparroquia'))->with('success', 'Tipo de parroquia registrado con éxito.');
    }

    /**
     * Mostrar formulario para editar un tipo de parroquia
     */
    public function edit($id = null)
    {
        $tipo = $this->tipoparroquiaModel->find($id);

        if (! $tipo) {
            return redirect()->to(site_url('tipoparroquia'))->with('error', 'El registro de tipo de parroquia no fue encontrado.');
        }

        $data = [
            'title'         => 'Editar Tipo de Parroquia #' . $tipo['idtipoparroquia'],
            'tipoParroquia' => $tipo,
            'errors'        => session()->getFlashdata('errors') ?? [],
        ];

        return view('tipoparroquia/edit', $data);
    }

    /**
     * Actualizar los datos del tipo de parroquia
     */
    public function update($id = null)
    {
        $tipo = $this->tipoparroquiaModel->find($id);

        if (! $tipo) {
            return redirect()->to(site_url('tipoparroquia'))->with('error', 'El registro de tipo de parroquia no existe.');
        }

        $postData = [
            'idtipoparroquia' => $id,
            'nombre'          => trim((string)$this->request->getPost('nombre')),
        ];

        if (! $this->tipoparroquiaModel->update($id, $postData)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->tipoparroquiaModel->errors());
        }

        return redirect()->to(site_url('tipoparroquia'))->with('success', 'Tipo de parroquia actualizado correctamente.');
    }

    /**
     * Eliminar un registro de tipo de parroquia
     */
    public function delete($id = null)
    {
        $tipo = $this->tipoparroquiaModel->find($id);

        if (! $tipo) {
            return redirect()->to(site_url('tipoparroquia'))->with('error', 'El registro no existe.');
        }

        $this->tipoparroquiaModel->delete($id);

        return redirect()->to(site_url('tipoparroquia'))->with('success', 'Registro de tipo de parroquia eliminado con éxito.');
    }
}
