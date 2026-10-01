<?php

namespace App\Controllers;

use App\Models\ActaModel;
use App\Models\MezaModel;

class ActaController extends BaseController
{
    protected ActaModel $actaModel;
    protected MezaModel $mezaModel;

    public function __construct()
    {
        $this->actaModel = new ActaModel();
        $this->mezaModel = new MezaModel();
    }

    /**
     * Visualizador individual de actas electorales (un registro a la vez con menú de navegación)
     */
    public function index($id = null)
    {
        $actaIds = $this->actaModel->getActaIdsOrdered();
        $total   = count($actaIds);

        if ($id === null) {
            $queryId = $this->request->getGet('id');
            if ($queryId !== null && is_numeric($queryId)) {
                $id = (int)$queryId;
            }
        }

        $currentIndex = 0;
        $currentActa  = null;

        if ($total > 0) {
            if ($id !== null) {
                $pos = array_search((int)$id, $actaIds);
                $currentIndex = ($pos !== false) ? $pos : 0;
            } elseif ($this->request->getGet('pos') !== null && is_numeric($this->request->getGet('pos'))) {
                $posQuery = (int)$this->request->getGet('pos') - 1;
                $currentIndex = ($posQuery >= 0 && $posQuery < $total) ? $posQuery : 0;
            }

            $currentId   = $actaIds[$currentIndex];
            $currentActa = $this->actaModel->getActaDetailed($currentId);
        }

        $firstId = $total > 0 ? $actaIds[0] : null;
        $prevId  = ($total > 0 && $currentIndex > 0) ? $actaIds[$currentIndex - 1] : null;
        $nextId  = ($total > 0 && $currentIndex < $total - 1) ? $actaIds[$currentIndex + 1] : null;
        $lastId  = $total > 0 ? $actaIds[$total - 1] : null;

        $data = [
            'title'        => $currentActa ? 'Acta Electoral #' . $currentActa['idacta'] . ' - Control Electoral' : 'Actas Electorales - Control Electoral',
            'currentActa'  => $currentActa,
            'currentIndex' => $currentIndex,
            'total'        => $total,
            'firstId'      => $firstId,
            'prevId'       => $prevId,
            'nextId'       => $nextId,
            'lastId'       => $lastId,
            'allIds'       => $actaIds,
        ];

        return view('acta/index', $data);
    }

    /**
     * Listado general de todas las actas en formato tabla
     */
    public function listar()
    {
        $actas = $this->actaModel->getActasDetailed();

        $data = [
            'title' => 'Listado General de Actas Electorales - Control Electoral',
            'actas' => $actas,
        ];

        return view('acta/listar', $data);
    }

    /**
     * Mostrar formulario para registrar una nueva acta
     */
    public function create()
    {
        $mezas = $this->mezaModel->getMezasDetailed();

        $data = [
            'title'  => 'Registrar Nueva Acta Electoral',
            'mezas'  => $mezas,
            'errors' => session()->getFlashdata('errors') ?? [],
            'old'    => session()->getFlashdata('old') ?? [],
        ];

        return view('acta/create', $data);
    }

    /**
     * Guardar una nueva acta en la base de datos
     */
    public function store()
    {
        $postData = [
            'idmeza'        => $this->request->getPost('idmeza'),
            'totalpapeleta' => $this->request->getPost('totalpapeleta'),
            'totalblancos'  => $this->request->getPost('totalblancos'),
            'totalnulos'    => $this->request->getPost('totalnulos'),
        ];

        if (!$this->actaModel->save($postData)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->actaModel->errors());
        }

        $newId = $this->actaModel->getInsertID();

        return redirect()->to(site_url('acta/ver/' . $newId))
            ->with('success', 'El acta electoral #' . $newId . ' ha sido registrada exitosamente.');
    }

    /**
     * Formulario para editar un acta existente
     */
    public function edit($id = null)
    {
        $acta = $this->actaModel->find($id);

        if (!$acta) {
            return redirect()->to(site_url('acta'))
                ->with('error', 'El acta especificada no existe.');
        }

        $mezas = $this->mezaModel->getMezasDetailed();

        $data = [
            'title'  => 'Editar Acta Electoral #' . $acta['idacta'],
            'acta'   => $acta,
            'mezas'  => $mezas,
            'errors' => session()->getFlashdata('errors') ?? [],
        ];

        return view('acta/edit', $data);
    }

    /**
     * Actualizar los datos del acta
     */
    public function update($id = null)
    {
        $acta = $this->actaModel->find($id);

        if (!$acta) {
            return redirect()->to(site_url('acta'))
                ->with('error', 'El acta especificada no existe.');
        }

        $postData = [
            'idacta'        => $id,
            'idmeza'        => $this->request->getPost('idmeza'),
            'totalpapeleta' => $this->request->getPost('totalpapeleta'),
            'totalblancos'  => $this->request->getPost('totalblancos'),
            'totalnulos'    => $this->request->getPost('totalnulos'),
        ];

        if (!$this->actaModel->save($postData)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->actaModel->errors());
        }

        return redirect()->to(site_url('acta/ver/' . $id))
            ->with('success', 'El acta electoral #' . $id . ' ha sido actualizada correctamente.');
    }

    /**
     * Eliminar un acta
     */
    public function delete($id = null)
    {
        $acta = $this->actaModel->find($id);

        if (!$acta) {
            return redirect()->to(site_url('acta'))
                ->with('error', 'El acta a eliminar no existe.');
        }

        $this->actaModel->delete($id);

        return redirect()->to(site_url('acta'))
            ->with('success', 'El acta electoral #' . $id . ' fue eliminada correctamente.');
    }
}
