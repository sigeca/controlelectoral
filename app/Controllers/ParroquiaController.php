<?php

namespace App\Controllers;

use App\Models\ParroquiaModel;
use App\Models\CantonModel;
use App\Models\TipoparroquiaModel;
use App\Models\DistritoModel;

class ParroquiaController extends BaseController
{
    protected ParroquiaModel $parroquiaModel;
    protected CantonModel $cantonModel;
    protected TipoparroquiaModel $tipoparroquiaModel;
    protected DistritoModel $distritoModel;

    public function __construct()
    {
        $this->parroquiaModel     = new ParroquiaModel();
        $this->cantonModel        = new CantonModel();
        $this->tipoparroquiaModel = new TipoparroquiaModel();
        $this->distritoModel      = new DistritoModel();
    }

    /**
     * Navegador por registro de parroquias (un registro a la vez con menú de navegación y recintos asociados)
     */
    public function index($id = null)
    {
        $parroquiaIds = $this->parroquiaModel->getParroquiaIdsOrdered();
        $total        = count($parroquiaIds);

        if ($id === null) {
            $queryId = $this->request->getGet('id');
            if ($queryId !== null && is_numeric($queryId)) {
                $id = (int)$queryId;
            }
        }

        $currentIndex      = 0;
        $currentParroquia  = null;
        $recintosAsociados = [];

        if ($total > 0) {
            if ($id !== null) {
                $pos = array_search((int)$id, $parroquiaIds);
                $currentIndex = ($pos !== false) ? $pos : 0;
            } elseif ($this->request->getGet('pos') !== null && is_numeric($this->request->getGet('pos'))) {
                $posQuery = (int)$this->request->getGet('pos') - 1;
                $currentIndex = ($posQuery >= 0 && $posQuery < $total) ? $posQuery : 0;
            }

            $currentId         = $parroquiaIds[$currentIndex];
            $currentParroquia  = $this->parroquiaModel->getParroquiasDetailed($currentId);
            $recintosAsociados = $this->parroquiaModel->getRecintosDeParroquia($currentId);
        }

        $firstId = $total > 0 ? $parroquiaIds[0] : null;
        $prevId  = ($total > 0 && $currentIndex > 0) ? $parroquiaIds[$currentIndex - 1] : null;
        $nextId  = ($total > 0 && $currentIndex < $total - 1) ? $parroquiaIds[$currentIndex + 1] : null;
        $lastId  = $total > 0 ? $parroquiaIds[$total - 1] : null;

        $data = [
            'title'             => $currentParroquia ? 'Parroquia: ' . $currentParroquia['nombre'] . ' - Control Electoral' : 'Parroquias - Control Electoral',
            'currentParroquia'  => $currentParroquia,
            'recintosAsociados' => $recintosAsociados,
            'currentIndex'      => $currentIndex,
            'total'             => $total,
            'firstId'           => $firstId,
            'prevId'            => $prevId,
            'nextId'            => $nextId,
            'lastId'            => $lastId,
            'allIds'            => $parroquiaIds,
        ];

        return view('parroquia/index', $data);
    }

    /**
     * Listado general de todas las parroquias en formato tabla
     */
    public function listar()
    {
        $parroquias = $this->parroquiaModel->getParroquiasWithCounts();

        $data = [
            'title'      => 'Listado General de Parroquias - Control Electoral',
            'parroquias' => $parroquias,
        ];

        return view('parroquia/listar', $data);
    }

    /**
     * Formulario para registrar una nueva parroquia
     */
    public function create()
    {
        $cantones       = $this->cantonModel->getCantonesWithProvincia();
        $tiposParroquia = $this->tipoparroquiaModel->orderBy('nombre', 'ASC')->findAll();
        $distritos      = $this->distritoModel->orderBy('nombre', 'ASC')->findAll();

        $data = [
            'title'          => 'Registrar Nueva Parroquia',
            'cantones'       => $cantones,
            'tiposParroquia' => $tiposParroquia,
            'distritos'      => $distritos,
            'errors'         => session()->getFlashdata('errors') ?? [],
            'old'            => session()->getFlashdata('old') ?? [],
        ];

        return view('parroquia/create', $data);
    }

    /**
     * Guardar una nueva parroquia
     */
    public function store()
    {
        $idTipoParroquia = $this->request->getPost('idtipoparroquia');
        $idDistrito      = $this->request->getPost('iddistrito');

        $postData = [
            'nombre'          => trim((string)$this->request->getPost('nombre')),
            'idcanton'        => $this->request->getPost('idcanton'),
            'idtipoparroquia' => !empty($idTipoParroquia) ? $idTipoParroquia : null,
            'iddistrito'      => !empty($idDistrito) ? $idDistrito : null,
        ];

        $insertId = $this->parroquiaModel->insert($postData);

        if (! $insertId) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->parroquiaModel->errors())
                             ->with('old', $postData);
        }

        return redirect()->to(site_url('parroquia/ver/' . $insertId))->with('success', 'Parroquia registrada exitosamente.');
    }

    /**
     * Formulario para editar una parroquia
     */
    public function edit($id = null)
    {
        $parroquia = $this->parroquiaModel->find($id);

        if (! $parroquia) {
            return redirect()->to(site_url('parroquia'))->with('error', 'La parroquia no existe.');
        }

        $cantones       = $this->cantonModel->getCantonesWithProvincia();
        $tiposParroquia = $this->tipoparroquiaModel->orderBy('nombre', 'ASC')->findAll();
        $distritos      = $this->distritoModel->orderBy('nombre', 'ASC')->findAll();

        $data = [
            'title'          => 'Editar Parroquia: ' . $parroquia['nombre'],
            'parroquia'      => $parroquia,
            'cantones'       => $cantones,
            'tiposParroquia' => $tiposParroquia,
            'distritos'      => $distritos,
            'errors'         => session()->getFlashdata('errors') ?? [],
        ];

        return view('parroquia/edit', $data);
    }

    /**
     * Actualizar los datos de la parroquia
     */
    public function update($id = null)
    {
        $parroquia = $this->parroquiaModel->find($id);

        if (! $parroquia) {
            return redirect()->to(site_url('parroquia'))->with('error', 'La parroquia a actualizar no existe.');
        }

        $idTipoParroquia = $this->request->getPost('idtipoparroquia');
        $idDistrito      = $this->request->getPost('iddistrito');

        $postData = [
            'idparroquia'     => $id,
            'nombre'          => trim((string)$this->request->getPost('nombre')),
            'idcanton'        => $this->request->getPost('idcanton'),
            'idtipoparroquia' => !empty($idTipoParroquia) ? $idTipoParroquia : null,
            'iddistrito'      => !empty($idDistrito) ? $idDistrito : null,
        ];

        if (! $this->parroquiaModel->update($id, $postData)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->parroquiaModel->errors());
        }

        return redirect()->to(site_url('parroquia/ver/' . $id))->with('success', 'Parroquia actualizada correctamente.');
    }

    /**
     * Eliminar una parroquia
     */
    public function delete($id = null)
    {
        $parroquia = $this->parroquiaModel->find($id);

        if (! $parroquia) {
            return redirect()->to(site_url('parroquia'))->with('error', 'La parroquia no existe.');
        }

        $zonasCount = $this->parroquiaModel->countZonasAsociadas((int)$id);
        if ($zonasCount > 0) {
            return redirect()->to(site_url('parroquia/ver/' . $id))
                             ->with('error', "No se puede eliminar la parroquia '{$parroquia['nombre']}' porque tiene {$zonasCount} zona(s) electoral(es) asociada(s). Debe reasignar o eliminar las zonas primero.");
        }

        $this->parroquiaModel->delete($id);

        return redirect()->to(site_url('parroquia'))->with('success', 'Parroquia eliminada exitosamente.');
    }
}
