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
     * Navegador por registro de recintos electorales (un registro a la vez con menú de navegación)
     */
    public function index($id = null)
    {
        $recintoIds = $this->recintoModel->getRecintoIdsOrdered();
        $total      = count($recintoIds);

        if ($id === null) {
            $queryId = $this->request->getGet('id');
            if ($queryId !== null && is_numeric($queryId)) {
                $id = (int)$queryId;
            }
        }

        $currentIndex   = 0;
        $currentRecinto = null;
        $mezasAsociadas = [];

        if ($total > 0) {
            if ($id !== null) {
                $pos = array_search((int)$id, $recintoIds);
                $currentIndex = ($pos !== false) ? $pos : 0;
            } elseif ($this->request->getGet('pos') !== null && is_numeric($this->request->getGet('pos'))) {
                $posQuery = (int)$this->request->getGet('pos') - 1;
                $currentIndex = ($posQuery >= 0 && $posQuery < $total) ? $posQuery : 0;
            }

            $currentId      = $recintoIds[$currentIndex];
            $currentRecinto = $this->recintoModel->getRecintosDetailed($currentId);
            $mezasAsociadas = $this->recintoModel->getMezasDeRecinto($currentId);
        }

        $firstId = $total > 0 ? $recintoIds[0] : null;
        $prevId  = ($total > 0 && $currentIndex > 0) ? $recintoIds[$currentIndex - 1] : null;
        $nextId  = ($total > 0 && $currentIndex < $total - 1) ? $recintoIds[$currentIndex + 1] : null;
        $lastId  = $total > 0 ? $recintoIds[$total - 1] : null;

        $data = [
            'title'          => $currentRecinto ? 'Recinto Electoral: ' . $currentRecinto['nombre'] . ' - Control Electoral' : 'Recintos Electorales - Control Electoral',
            'currentRecinto' => $currentRecinto,
            'mezasAsociadas' => $mezasAsociadas,
            'currentIndex'   => $currentIndex,
            'total'          => $total,
            'firstId'        => $firstId,
            'prevId'         => $prevId,
            'nextId'         => $nextId,
            'lastId'         => $lastId,
            'allIds'         => $recintoIds,
        ];

        return view('recintoelectoral/index', $data);
    }

    /**
     * Listado general de todos los recintos electorales en formato tabla
     */
    public function listar()
    {
        $recintos = $this->recintoModel->getRecintosDetailed();

        $data = [
            'title'    => 'Listado General de Recintos Electorales - Control Electoral',
            'recintos' => $recintos,
        ];

        return view('recintoelectoral/listar', $data);
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

        $insertId = $this->recintoModel->insert($postData);

        if (! $insertId) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->recintoModel->errors())
                             ->with('old', $postData);
        }

        return redirect()->to(site_url('recintoelectoral/ver/' . $insertId))->with('success', 'Recinto electoral registrado exitosamente.');
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

        return redirect()->to(site_url('recintoelectoral/ver/' . $id))->with('success', 'Recinto electoral actualizado correctamente.');
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
            return redirect()->to(site_url('recintoelectoral/ver/' . $id))
                             ->with('error', "No se puede eliminar el recinto '{$recinto['nombre']}' porque tiene {$mezasCount} mesa(s) electoral(es) asociada(s). Debe reasignar o eliminar las mesas primero.");
        }

        $this->recintoModel->delete($id);

        return redirect()->to(site_url('recintoelectoral'))->with('success', 'Recinto electoral eliminado exitosamente.');
    }
}
