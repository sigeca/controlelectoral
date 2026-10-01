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
     * Navegador por registro de provincias (un registro a la vez con menú de navegación y cantones asociados)
     */
    public function index($id = null)
    {
        $provinciaIds = $this->provinciaModel->getProvinciaIdsOrdered();
        $total        = count($provinciaIds);

        if ($id === null) {
            $queryId = $this->request->getGet('id');
            if ($queryId !== null && is_numeric($queryId)) {
                $id = (int)$queryId;
            }
        }

        $currentIndex     = 0;
        $currentProvincia = null;
        $cantonesAsociados = [];

        if ($total > 0) {
            if ($id !== null) {
                $pos = array_search((int)$id, $provinciaIds);
                $currentIndex = ($pos !== false) ? $pos : 0;
            } elseif ($this->request->getGet('pos') !== null && is_numeric($this->request->getGet('pos'))) {
                $posQuery = (int)$this->request->getGet('pos') - 1;
                $currentIndex = ($posQuery >= 0 && $posQuery < $total) ? $posQuery : 0;
            }

            $currentId        = $provinciaIds[$currentIndex];
            $currentProvincia = $this->provinciaModel->find($currentId);
            $cantonesAsociados = $this->provinciaModel->getCantonesDeProvincia($currentId);
        }

        $firstId = $total > 0 ? $provinciaIds[0] : null;
        $prevId  = ($total > 0 && $currentIndex > 0) ? $provinciaIds[$currentIndex - 1] : null;
        $nextId  = ($total > 0 && $currentIndex < $total - 1) ? $provinciaIds[$currentIndex + 1] : null;
        $lastId  = $total > 0 ? $provinciaIds[$total - 1] : null;

        $data = [
            'title'             => $currentProvincia ? 'Provincia: ' . $currentProvincia['nombre'] . ' - Control Electoral' : 'Provincias - Control Electoral',
            'currentProvincia'  => $currentProvincia,
            'cantonesAsociados' => $cantonesAsociados,
            'currentIndex'      => $currentIndex,
            'total'             => $total,
            'firstId'           => $firstId,
            'prevId'            => $prevId,
            'nextId'            => $nextId,
            'lastId'            => $lastId,
            'allIds'            => $provinciaIds,
        ];

        return view('provincia/index', $data);
    }

    /**
     * Listado general de todas las provincias en formato tabla
     */
    public function listar()
    {
        $provincias = $this->provinciaModel->getProvinciasWithCounts();

        $data = [
            'title'      => 'Listado General de Provincias - Control Electoral',
            'provincias' => $provincias,
        ];

        return view('provincia/listar', $data);
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

        $insertId = $this->provinciaModel->insert($postData);

        if (! $insertId) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->provinciaModel->errors())
                             ->with('old', $postData);
        }

        return redirect()->to(site_url('provincia/ver/' . $insertId))->with('success', 'Provincia registrada con éxito.');
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

        return redirect()->to(site_url('provincia/ver/' . $id))->with('success', 'Provincia actualizada correctamente.');
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
            return redirect()->to(site_url('provincia/ver/' . $id))->with(
                'error',
                "No se puede eliminar la provincia \"{$provincia['nombre']}\" porque tiene {$totalCantones} cantón(es) asociado(s). Debe reasignar o eliminar los cantones primero."
            );
        }

        $this->provinciaModel->delete($id);

        return redirect()->to(site_url('provincia'))->with('success', 'Provincia eliminada exitosamente.');
    }
}
