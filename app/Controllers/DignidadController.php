<?php

namespace App\Controllers;

use App\Models\DignidadModel;
use App\Models\PersonaModel;
use App\Models\TipodignidadModel;

class DignidadController extends BaseController
{
    protected DignidadModel $dignidadModel;
    protected PersonaModel $personaModel;
    protected TipodignidadModel $tipodignidadModel;

    public function __construct()
    {
        $this->dignidadModel     = new DignidadModel();
        $this->personaModel      = new PersonaModel();
        $this->tipodignidadModel = new TipodignidadModel();
    }

    /**
     * Visualizador individual de dignidades/candidaturas (un registro a la vez con menú superior de navegación)
     */
    public function index($id = null)
    {
        $dignidadIds = $this->dignidadModel->getDignidadIdsOrdered();
        $total       = count($dignidadIds);

        // Permitir parámetro por query string ?id= o ?pos=
        if ($id === null) {
            $queryId = $this->request->getGet('id');
            if ($queryId !== null && is_numeric($queryId)) {
                $id = (int)$queryId;
            }
        }

        $currentIndex    = 0;
        $currentDignidad = null;
        $mesasAsignadas  = [];

        if ($total > 0) {
            if ($id !== null) {
                $pos = array_search((int)$id, $dignidadIds);
                $currentIndex = ($pos !== false) ? $pos : 0;
            } elseif ($this->request->getGet('pos') !== null && is_numeric($this->request->getGet('pos'))) {
                $posQuery = (int)$this->request->getGet('pos') - 1;
                $currentIndex = ($posQuery >= 0 && $posQuery < $total) ? $posQuery : 0;
            }

            $currentId       = $dignidadIds[$currentIndex];
            $currentDignidad = $this->dignidadModel->getDignidadesDetailed($currentId);
            $mesasAsignadas  = $this->dignidadModel->getMesasDeDignidad($currentId);
        }

        $firstId = $total > 0 ? $dignidadIds[0] : null;
        $prevId  = ($total > 0 && $currentIndex > 0) ? $dignidadIds[$currentIndex - 1] : null;
        $nextId  = ($total > 0 && $currentIndex < $total - 1) ? $dignidadIds[$currentIndex + 1] : null;
        $lastId  = $total > 0 ? $dignidadIds[$total - 1] : null;

        $data = [
            'title'           => $currentDignidad ? 'Candidatura: ' . $currentDignidad['persona_nombre'] . ' ' . $currentDignidad['persona_apellidos'] . ' (' . $currentDignidad['tipodignidad_nombre'] . ') - Control Electoral' : 'Dignidades Electorales - Control Electoral',
            'currentDignidad' => $currentDignidad,
            'mesasAsignadas'  => $mesasAsignadas,
            'currentIndex'    => $currentIndex,
            'total'           => $total,
            'firstId'         => $firstId,
            'prevId'          => $prevId,
            'nextId'          => $nextId,
            'lastId'          => $lastId,
            'allIds'          => $dignidadIds,
        ];

        return view('dignidad/index', $data);
    }

    /**
     * Listado general de todas las dignidades y candidaturas en formato tabla
     */
    public function listar()
    {
        $dignidades = $this->dignidadModel->getDignidadesDetailed();

        $data = [
            'title'      => 'Listado General de Dignidades y Candidaturas - Control Electoral',
            'dignidades' => $dignidades,
        ];

        return view('dignidad/listar', $data);
    }

    /**
     * Formulario para registrar una nueva dignidad/candidatura
     */
    public function create()
    {
        $personas = $this->personaModel->getPersonasWithSexo();
        $tipos    = $this->tipodignidadModel->orderBy('nombre', 'ASC')->findAll();

        $data = [
            'title'    => 'Registrar Nueva Dignidad / Candidatura',
            'personas' => $personas,
            'tipos'    => $tipos,
            'errors'   => session()->getFlashdata('errors') ?? [],
            'old'      => session()->getFlashdata('old') ?? [],
        ];

        return view('dignidad/create', $data);
    }

    /**
     * Guardar una nueva dignidad/candidatura
     */
    public function store()
    {
        $postData = [
            'idpersona'      => $this->request->getPost('idpersona'),
            'idtipodignidad' => $this->request->getPost('idtipodignidad'),
        ];

        $insertId = $this->dignidadModel->insert($postData);

        if (! $insertId) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->dignidadModel->errors())
                             ->with('old', $postData);
        }

        return redirect()->to(site_url('dignidad/ver/' . $insertId))->with('success', 'Dignidad/Candidatura registrada exitosamente.');
    }

    /**
     * Formulario para editar una dignidad existente
     */
    public function edit($id = null)
    {
        $dignidad = $this->dignidadModel->find($id);

        if (! $dignidad) {
            return redirect()->to(site_url('dignidad'))->with('error', 'El registro de dignidad no existe.');
        }

        $personas = $this->personaModel->getPersonasWithSexo();
        $tipos    = $this->tipodignidadModel->orderBy('nombre', 'ASC')->findAll();

        $data = [
            'title'    => 'Editar Dignidad Electoral #' . $dignidad['iddignidad'],
            'dignidad' => $dignidad,
            'personas' => $personas,
            'tipos'    => $tipos,
            'errors'   => session()->getFlashdata('errors') ?? [],
        ];

        return view('dignidad/edit', $data);
    }

    /**
     * Actualizar los datos de la dignidad
     */
    public function update($id = null)
    {
        $dignidad = $this->dignidadModel->find($id);

        if (! $dignidad) {
            return redirect()->to(site_url('dignidad'))->with('error', 'El registro de dignidad a actualizar no existe.');
        }

        $postData = [
            'iddignidad'     => $id,
            'idpersona'      => $this->request->getPost('idpersona'),
            'idtipodignidad' => $this->request->getPost('idtipodignidad'),
        ];

        if (! $this->dignidadModel->update($id, $postData)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->dignidadModel->errors());
        }

        return redirect()->to(site_url('dignidad/ver/' . $id))->with('success', 'Dignidad electoral actualizada correctamente.');
    }

    /**
     * Eliminar un registro de dignidad
     */
    public function delete($id = null)
    {
        $dignidad = $this->dignidadModel->find($id);

        if (! $dignidad) {
            return redirect()->to(site_url('dignidad'))->with('error', 'El registro de dignidad no existe.');
        }

        $totalPapeletas = $this->dignidadModel->countMezaDignidadesAsociadas((int)$id);
        if ($totalPapeletas > 0) {
            return redirect()->to(site_url('dignidad/ver/' . $id))->with(
                'error',
                "No se puede eliminar la candidatura de dignidad #{$dignidad['iddignidad']} porque está asignada a {$totalPapeletas} mesa(s) o papeleta(s) electoral(es). Debe desvincularla de las mesas primero."
            );
        }

        $this->dignidadModel->delete($id);

        return redirect()->to(site_url('dignidad'))->with('success', 'Registro de dignidad eliminado exitosamente.');
    }
}
