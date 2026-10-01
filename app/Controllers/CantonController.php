<?php

namespace App\Controllers;

use App\Models\CantonModel;
use App\Models\ProvinciaModel;

class CantonController extends BaseController
{
    protected CantonModel $cantonModel;
    protected ProvinciaModel $provinciaModel;

    public function __construct()
    {
        $this->cantonModel    = new CantonModel();
        $this->provinciaModel = new ProvinciaModel();
    }

    /**
     * Navegador por registro de cantones (un registro a la vez con menú de navegación y parroquias asociadas)
     */
    public function index($id = null)
    {
        $cantonIds = $this->cantonModel->getCantonIdsOrdered();
        $total     = count($cantonIds);

        if ($id === null) {
            $queryId = $this->request->getGet('id');
            if ($queryId !== null && is_numeric($queryId)) {
                $id = (int)$queryId;
            }
        }

        $currentIndex       = 0;
        $currentCanton      = null;
        $parroquiasAsociadas = [];

        if ($total > 0) {
            if ($id !== null) {
                $pos = array_search((int)$id, $cantonIds);
                $currentIndex = ($pos !== false) ? $pos : 0;
            } elseif ($this->request->getGet('pos') !== null && is_numeric($this->request->getGet('pos'))) {
                $posQuery = (int)$this->request->getGet('pos') - 1;
                $currentIndex = ($posQuery >= 0 && $posQuery < $total) ? $posQuery : 0;
            }

            $currentId           = $cantonIds[$currentIndex];
            $currentCanton      = $this->cantonModel->getCantonesWithProvincia($currentId);
            $parroquiasAsociadas = $this->cantonModel->getParroquiasDeCanton($currentId);
        }

        $firstId = $total > 0 ? $cantonIds[0] : null;
        $prevId  = ($total > 0 && $currentIndex > 0) ? $cantonIds[$currentIndex - 1] : null;
        $nextId  = ($total > 0 && $currentIndex < $total - 1) ? $cantonIds[$currentIndex + 1] : null;
        $lastId  = $total > 0 ? $cantonIds[$total - 1] : null;

        $data = [
            'title'               => $currentCanton ? 'Cantón: ' . $currentCanton['nombre'] . ' - Control Electoral' : 'Cantones - Control Electoral',
            'currentCanton'       => $currentCanton,
            'parroquiasAsociadas' => $parroquiasAsociadas,
            'currentIndex'        => $currentIndex,
            'total'               => $total,
            'firstId'             => $firstId,
            'prevId'              => $prevId,
            'nextId'              => $nextId,
            'lastId'              => $lastId,
            'allIds'              => $cantonIds,
        ];

        return view('canton/index', $data);
    }

    /**
     * Listado general de todos los cantones en formato tabla
     */
    public function listar()
    {
        $cantones = $this->cantonModel->getCantonesWithCounts();

        $data = [
            'title'    => 'Listado General de Cantones - Control Electoral',
            'cantones' => $cantones,
        ];

        return view('canton/listar', $data);
    }

    /**
     * Formulario para registrar un nuevo cantón
     */
    public function create()
    {
        $provincias = $this->provinciaModel->orderBy('nombre', 'ASC')->findAll();

        $data = [
            'title'      => 'Registrar Nuevo Cantón',
            'provincias' => $provincias,
            'errors'     => session()->getFlashdata('errors') ?? [],
            'old'        => session()->getFlashdata('old') ?? [],
        ];

        return view('canton/create', $data);
    }

    /**
     * Guardar un nuevo cantón
     */
    public function store()
    {
        $postData = [
            'nombre'      => trim((string)$this->request->getPost('nombre')),
            'idprovincia' => $this->request->getPost('idprovincia'),
        ];

        $insertId = $this->cantonModel->insert($postData);

        if (! $insertId) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->cantonModel->errors())
                             ->with('old', $postData);
        }

        return redirect()->to(site_url('canton/ver/' . $insertId))->with('success', 'Cantón registrado exitosamente.');
    }

    /**
     * Formulario para editar un cantón
     */
    public function edit($id = null)
    {
        $canton = $this->cantonModel->find($id);

        if (! $canton) {
            return redirect()->to(site_url('canton'))->with('error', 'El cantón solicitado no existe.');
        }

        $provincias = $this->provinciaModel->orderBy('nombre', 'ASC')->findAll();

        $data = [
            'title'      => 'Editar Cantón: ' . $canton['nombre'],
            'canton'     => $canton,
            'provincias' => $provincias,
            'errors'     => session()->getFlashdata('errors') ?? [],
        ];

        return view('canton/edit', $data);
    }

    /**
     * Actualizar los datos del cantón
     */
    public function update($id = null)
    {
        $canton = $this->cantonModel->find($id);

        if (! $canton) {
            return redirect()->to(site_url('canton'))->with('error', 'El cantón a actualizar no existe.');
        }

        $postData = [
            'idcanton'    => $id,
            'nombre'      => trim((string)$this->request->getPost('nombre')),
            'idprovincia' => $this->request->getPost('idprovincia'),
        ];

        if (! $this->cantonModel->update($id, $postData)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->cantonModel->errors());
        }

        return redirect()->to(site_url('canton/ver/' . $id))->with('success', 'Cantón actualizado correctamente.');
    }

    /**
     * Eliminar un cantón
     */
    public function delete($id = null)
    {
        $canton = $this->cantonModel->find($id);

        if (! $canton) {
            return redirect()->to(site_url('canton'))->with('error', 'El cantón no existe.');
        }

        // Proteger clave foránea si tiene parroquias asociadas
        $totalParroquias = $this->cantonModel->countParroquiasAsociadas($id);
        if ($totalParroquias > 0) {
            return redirect()->to(site_url('canton/ver/' . $id))->with(
                'error',
                "No se puede eliminar el cantón \"{$canton['nombre']}\" porque tiene {$totalParroquias} parroquia(s) asociada(s). Debe reasignar o eliminar las parroquias primero."
            );
        }

        $this->cantonModel->delete($id);

        return redirect()->to(site_url('canton'))->with('success', 'Cantón eliminado exitosamente.');
    }
}
