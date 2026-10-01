<?php

namespace App\Controllers;

use App\Models\DignidadactaModel;
use App\Models\ActaModel;
use App\Models\DignidadModel;

class DignidadactaController extends BaseController
{
    protected DignidadactaModel $dignidadactaModel;
    protected ActaModel $actaModel;
    protected DignidadModel $dignidadModel;

    public function __construct()
    {
        $this->dignidadactaModel = new DignidadactaModel();
        $this->actaModel         = new ActaModel();
        $this->dignidadModel     = new DignidadModel();
    }

    /**
     * Visualizador individual de votos por dignidad en acta (un registro a la vez con menú de navegación)
     */
    public function index($id = null)
    {
        $dignidadactaIds = $this->dignidadactaModel->getDignidadactaIdsOrdered();
        $total           = count($dignidadactaIds);

        if ($id === null) {
            $queryId = $this->request->getGet('id');
            if ($queryId !== null && is_numeric($queryId)) {
                $id = (int)$queryId;
            }
        }

        $currentIndex        = 0;
        $currentDignidadacta = null;

        if ($total > 0) {
            if ($id !== null) {
                $pos = array_search((int)$id, $dignidadactaIds);
                $currentIndex = ($pos !== false) ? $pos : 0;
            } elseif ($this->request->getGet('pos') !== null && is_numeric($this->request->getGet('pos'))) {
                $posQuery = (int)$this->request->getGet('pos') - 1;
                $currentIndex = ($posQuery >= 0 && $posQuery < $total) ? $posQuery : 0;
            }

            $currentId           = $dignidadactaIds[$currentIndex];
            $currentDignidadacta = $this->dignidadactaModel->getDignidadactaDetailed($currentId);
        }

        $firstId = $total > 0 ? $dignidadactaIds[0] : null;
        $prevId  = ($total > 0 && $currentIndex > 0) ? $dignidadactaIds[$currentIndex - 1] : null;
        $nextId  = ($total > 0 && $currentIndex < $total - 1) ? $dignidadactaIds[$currentIndex + 1] : null;
        $lastId  = $total > 0 ? $dignidadactaIds[$total - 1] : null;

        $data = [
            'title'               => $currentDignidadacta ? 'Votación Candidato ID #' . $currentDignidadacta['iddignidadacta'] . ' - Control Electoral' : 'Votación por Dignidad en Acta - Control Electoral',
            'currentDignidadacta' => $currentDignidadacta,
            'currentIndex'        => $currentIndex,
            'total'               => $total,
            'firstId'             => $firstId,
            'prevId'              => $prevId,
            'nextId'              => $nextId,
            'lastId'              => $lastId,
            'allIds'              => $dignidadactaIds,
        ];

        return view('dignidadacta/index', $data);
    }

    /**
     * Listado general de todas las votaciones por candidatura/acta en formato tabla
     */
    public function listar()
    {
        $registros = $this->dignidadactaModel->getDignidadactasDetailed();

        $data = [
            'title'     => 'Listado General de Votación por Dignidad y Acta - Control Electoral',
            'registros' => $registros,
        ];

        return view('dignidadacta/listar', $data);
    }

    /**
     * Formulario para registrar votos de candidatura en un acta
     */
    public function create()
    {
        $actas      = $this->actaModel->getActasDetailed();
        $dignidades = $this->dignidadModel->getDignidadesDetailed();

        $data = [
            'title'      => 'Registrar Votación de Dignidad en Acta',
            'actas'      => $actas,
            'dignidades' => $dignidades,
            'errors'     => session()->getFlashdata('errors') ?? [],
            'old'        => session()->getFlashdata('old') ?? [],
        ];

        return view('dignidadacta/create', $data);
    }

    /**
     * Guardar nuevo registro de votación en la base de datos
     */
    public function store()
    {
        $idacta   = $this->request->getPost('idacta');
        $postData = [
            'idacta'     => $idacta,
            'iddignidad' => $this->request->getPost('iddignidad'),
            'votacion'   => $this->request->getPost('votacion'),
        ];

        if (!$this->dignidadactaModel->save($postData)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->dignidadactaModel->errors());
        }

        $newId = $this->dignidadactaModel->getInsertID();

        if ($this->request->getPost('redirect_to_acta') || $this->request->getGet('redirect_to_acta')) {
            return redirect()->to(site_url('acta/ver/' . $idacta))
                ->with('success', 'Se registraron exitosamente los votos para la candidatura en el Acta #' . $idacta . '.');
        }

        return redirect()->to(site_url('dignidadacta/ver/' . $newId))
            ->with('success', 'El registro de votación #' . $newId . ' ha sido guardado exitosamente.');
    }

    /**
     * Formulario para editar un registro existente de votación
     */
    public function edit($id = null)
    {
        $dignidadacta = $this->dignidadactaModel->find($id);

        if (!$dignidadacta) {
            return redirect()->to(site_url('dignidadacta'))
                ->with('error', 'El registro de votación especificado no existe.');
        }

        $actas      = $this->actaModel->getActasDetailed();
        $dignidades = $this->dignidadModel->getDignidadesDetailed();

        $data = [
            'title'        => 'Editar Votación #' . $dignidadacta['iddignidadacta'],
            'dignidadacta' => $dignidadacta,
            'actas'        => $actas,
            'dignidades'   => $dignidades,
            'errors'       => session()->getFlashdata('errors') ?? [],
        ];

        return view('dignidadacta/edit', $data);
    }

    /**
     * Actualizar los datos del registro de votación
     */
    public function update($id = null)
    {
        $dignidadacta = $this->dignidadactaModel->find($id);

        if (!$dignidadacta) {
            return redirect()->to(site_url('dignidadacta'))
                ->with('error', 'El registro de votación especificado no existe.');
        }

        $idacta   = $this->request->getPost('idacta') ?? $dignidadacta['idacta'];
        $postData = [
            'iddignidadacta' => $id,
            'idacta'         => $idacta,
            'iddignidad'     => $this->request->getPost('iddignidad'),
            'votacion'       => $this->request->getPost('votacion'),
        ];

        if (!$this->dignidadactaModel->save($postData)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->dignidadactaModel->errors());
        }

        if ($this->request->getPost('redirect_to_acta') || $this->request->getGet('redirect_to_acta')) {
            return redirect()->to(site_url('acta/ver/' . $idacta))
                ->with('success', 'La votación del candidato en el Acta #' . $idacta . ' fue actualizada correctamente.');
        }

        return redirect()->to(site_url('dignidadacta/ver/' . $id))
            ->with('success', 'El registro de votación #' . $id . ' ha sido actualizado correctamente.');
    }

    /**
     * Eliminar un registro de votación
     */
    public function delete($id = null)
    {
        $dignidadacta = $this->dignidadactaModel->find($id);

        if (!$dignidadacta) {
            return redirect()->to(site_url('dignidadacta'))
                ->with('error', 'El registro de votación a eliminar no existe.');
        }

        $idacta = $dignidadacta['idacta'];
        $this->dignidadactaModel->delete($id);

        if ($this->request->getGet('redirect_to_acta')) {
            return redirect()->to(site_url('acta/ver/' . $idacta))
                ->with('success', 'El registro de votación fue eliminado del Acta #' . $idacta . '.');
        }

        return redirect()->to(site_url('dignidadacta'))
            ->with('success', 'El registro de votación #' . $id . ' fue eliminado correctamente.');
    }
}
