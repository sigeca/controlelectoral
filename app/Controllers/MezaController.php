<?php

namespace App\Controllers;

use App\Models\MezaModel;
use App\Models\SexoModel;
use App\Models\RecintoelectoralModel;

class MezaController extends BaseController
{
    protected MezaModel $mezaModel;
    protected SexoModel $sexoModel;
    protected RecintoelectoralModel $recintoModel;

    public function __construct()
    {
        $this->mezaModel    = new MezaModel();
        $this->sexoModel    = new SexoModel();
        $this->recintoModel = new RecintoelectoralModel();
    }

    /**
     * Visualizador individual de mesas electorales (un registro a la vez con menú superior de navegación)
     */
    public function index($id = null)
    {
        $mezaIds = $this->mezaModel->getMezaIdsOrdered();
        $total   = count($mezaIds);

        // Permitir parámetro por query string ?id= o ?pos=
        if ($id === null) {
            $queryId = $this->request->getGet('id');
            if ($queryId !== null && is_numeric($queryId)) {
                $id = (int)$queryId;
            }
        }

        $currentIndex = 0;
        $currentMeza  = null;
        $actas        = [];

        if ($total > 0) {
            if ($id !== null) {
                $pos = array_search((int)$id, $mezaIds);
                $currentIndex = ($pos !== false) ? $pos : 0;
            } elseif ($this->request->getGet('pos') !== null && is_numeric($this->request->getGet('pos'))) {
                $posQuery = (int)$this->request->getGet('pos') - 1;
                $currentIndex = ($posQuery >= 0 && $posQuery < $total) ? $posQuery : 0;
            }

            $currentId   = $mezaIds[$currentIndex];
            $currentMeza = $this->mezaModel->getMezasDetailed($currentId);
            $actas       = $this->mezaModel->getActasDeMeza($currentId);
        }

        $firstId = $total > 0 ? $mezaIds[0] : null;
        $prevId  = ($total > 0 && $currentIndex > 0) ? $mezaIds[$currentIndex - 1] : null;
        $nextId  = ($total > 0 && $currentIndex < $total - 1) ? $mezaIds[$currentIndex + 1] : null;
        $lastId  = $total > 0 ? $mezaIds[$total - 1] : null;

        $data = [
            'title'        => $currentMeza ? 'Mesa Electoral #' . $currentMeza['numero'] . ' - Control Electoral' : 'Mesas Electorales - Control Electoral',
            'currentMeza'  => $currentMeza,
            'actas'        => $actas,
            'currentIndex' => $currentIndex,
            'total'        => $total,
            'firstId'      => $firstId,
            'prevId'       => $prevId,
            'nextId'       => $nextId,
            'lastId'       => $lastId,
            'allIds'       => $mezaIds,
        ];

        return view('meza/index', $data);
    }

    /**
     * Listado general de todas las mesas electorales en formato tabla
     */
    public function listar()
    {
        $mezas        = $this->mezaModel->getMezasDetailed();
        $actasPorMeza = $this->mezaModel->getActasPorTodasLasMesas();

        $data = [
            'title'        => 'Listado General de Mesas Electorales - Control Electoral',
            'mezas'        => $mezas,
            'actasPorMeza' => $actasPorMeza,
        ];

        return view('meza/listar', $data);
    }

    /**
     * Formulario para registrar una nueva mesa electoral
     */
    public function create()
    {
        $sexos    = $this->sexoModel->orderBy('nombre', 'ASC')->findAll();
        $recintos = $this->recintoModel->getRecintosDetailed();

        $data = [
            'title'    => 'Registrar Nueva Mesa Electoral',
            'sexos'    => $sexos,
            'recintos' => $recintos,
            'errors'   => session()->getFlashdata('errors') ?? [],
            'old'      => session()->getFlashdata('old') ?? [],
        ];

        return view('meza/create', $data);
    }

    /**
     * Guardar una nueva mesa electoral
     */
    public function store()
    {
        $postData = [
            'numero'             => $this->request->getPost('numero'),
            'idsexo'             => $this->request->getPost('idsexo'),
            'idrecintoelectoral' => $this->request->getPost('idrecintoelectoral'),
        ];

        $insertId = $this->mezaModel->insert($postData);

        if (! $insertId) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->mezaModel->errors())
                             ->with('old', $postData);
        }

        return redirect()->to(site_url('meza/ver/' . $insertId))->with('success', 'Mesa electoral registrada exitosamente.');
    }

    /**
     * Formulario para editar una mesa electoral existente
     */
    public function edit($id = null)
    {
        $meza = $this->mezaModel->find($id);

        if (! $meza) {
            return redirect()->to(site_url('meza'))->with('error', 'La mesa electoral no existe.');
        }

        $sexos    = $this->sexoModel->orderBy('nombre', 'ASC')->findAll();
        $recintos = $this->recintoModel->getRecintosDetailed();

        $data = [
            'title'    => 'Editar Mesa #' . $meza['numero'],
            'meza'     => $meza,
            'sexos'    => $sexos,
            'recintos' => $recintos,
            'errors'   => session()->getFlashdata('errors') ?? [],
        ];

        return view('meza/edit', $data);
    }

    /**
     * Actualizar los datos de la mesa electoral
     */
    public function update($id = null)
    {
        $meza = $this->mezaModel->find($id);

        if (! $meza) {
            return redirect()->to(site_url('meza'))->with('error', 'La mesa electoral a actualizar no existe.');
        }

        $postData = [
            'idmeza'             => $id,
            'numero'             => $this->request->getPost('numero'),
            'idsexo'             => $this->request->getPost('idsexo'),
            'idrecintoelectoral' => $this->request->getPost('idrecintoelectoral'),
        ];

        if (! $this->mezaModel->update($id, $postData)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->mezaModel->errors());
        }

        return redirect()->to(site_url('meza/ver/' . $id))->with('success', 'Mesa electoral actualizada correctamente.');
    }

    /**
     * Eliminar una mesa electoral
     */
    public function delete($id = null)
    {
        $meza = $this->mezaModel->find($id);

        if (! $meza) {
            return redirect()->to(site_url('meza'))->with('error', 'La mesa electoral no existe.');
        }

        $totalActas = $this->mezaModel->countActasAsociadas((int)$id);
        if ($totalActas > 0) {
            return redirect()->to(site_url('meza/ver/' . $id))->with(
                'error',
                "No se puede eliminar la Mesa #{$meza['numero']} porque tiene {$totalActas} acta(s) de escrutinio registrada(s). Debe eliminar las actas asociadas primero."
            );
        }

        $this->mezaModel->delete($id);

        return redirect()->to(site_url('meza'))->with('success', 'Mesa electoral eliminada exitosamente.');
    }
}
