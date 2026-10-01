<?php

namespace App\Controllers;

use App\Models\DistritoModel;

class DistritoController extends BaseController
{
    protected DistritoModel $distritoModel;

    public function __construct()
    {
        $this->distritoModel = new DistritoModel();
    }

    /**
     * Navegador por registro de distritos (un registro a la vez con menú de navegación y parroquias asociadas)
     */
    public function index($id = null)
    {
        $distritoIds = $this->distritoModel->getDistritoIdsOrdered();
        $total       = count($distritoIds);

        if ($id === null) {
            $queryId = $this->request->getGet('id');
            if ($queryId !== null && is_numeric($queryId)) {
                $id = (int)$queryId;
            }
        }

        $currentIndex       = 0;
        $currentDistrito    = null;
        $parroquiasAsociadas = [];

        if ($total > 0) {
            if ($id !== null) {
                $pos = array_search((int)$id, $distritoIds);
                $currentIndex = ($pos !== false) ? $pos : 0;
            } elseif ($this->request->getGet('pos') !== null && is_numeric($this->request->getGet('pos'))) {
                $posQuery = (int)$this->request->getGet('pos') - 1;
                $currentIndex = ($posQuery >= 0 && $posQuery < $total) ? $posQuery : 0;
            }

            $currentId           = $distritoIds[$currentIndex];
            $currentDistrito    = $this->distritoModel->find($currentId);
            $parroquiasAsociadas = $this->distritoModel->getParroquiasDeDistrito($currentId);
        }

        $firstId = $total > 0 ? $distritoIds[0] : null;
        $prevId  = ($total > 0 && $currentIndex > 0) ? $distritoIds[$currentIndex - 1] : null;
        $nextId  = ($total > 0 && $currentIndex < $total - 1) ? $distritoIds[$currentIndex + 1] : null;
        $lastId  = $total > 0 ? $distritoIds[$total - 1] : null;

        $data = [
            'title'               => $currentDistrito ? 'Distrito: ' . $currentDistrito['nombre'] . ' - Control Electoral' : 'Distritos - Control Electoral',
            'currentDistrito'     => $currentDistrito,
            'parroquiasAsociadas' => $parroquiasAsociadas,
            'currentIndex'        => $currentIndex,
            'total'               => $total,
            'firstId'             => $firstId,
            'prevId'              => $prevId,
            'nextId'              => $nextId,
            'lastId'              => $lastId,
            'allIds'              => $distritoIds,
        ];

        return view('distrito/index', $data);
    }

    /**
     * Listado general de todos los distritos en formato tabla
     */
    public function listar()
    {
        $distritos = $this->distritoModel->getDistritosWithCounts();

        $data = [
            'title'     => 'Listado General de Distritos - Control Electoral',
            'distritos' => $distritos,
        ];

        return view('distrito/listar', $data);
    }

    /**
     * Mostrar formulario para crear un nuevo distrito
     */
    public function create()
    {
        $data = [
            'title'  => 'Registrar Nuevo Distrito',
            'errors' => session()->getFlashdata('errors') ?? [],
            'old'    => session()->getFlashdata('old') ?? [],
        ];

        return view('distrito/create', $data);
    }

    /**
     * Guardar el nuevo distrito en la base de datos
     */
    public function store()
    {
        $postData = [
            'nombre' => trim((string)$this->request->getPost('nombre')),
        ];

        $insertId = $this->distritoModel->insert($postData);

        if (! $insertId) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->distritoModel->errors())
                             ->with('old', $postData);
        }

        return redirect()->to(site_url('distrito/ver/' . $insertId))->with('success', 'Distrito registrado con éxito.');
    }

    /**
     * Mostrar formulario para editar un distrito
     */
    public function edit($id = null)
    {
        $distrito = $this->distritoModel->find($id);

        if (! $distrito) {
            return redirect()->to(site_url('distrito'))->with('error', 'El registro de distrito no fue encontrado.');
        }

        $data = [
            'title'    => 'Editar Distrito #' . $distrito['iddistrito'],
            'distrito' => $distrito,
            'errors'   => session()->getFlashdata('errors') ?? [],
        ];

        return view('distrito/edit', $data);
    }

    /**
     * Actualizar los datos del distrito
     */
    public function update($id = null)
    {
        $distrito = $this->distritoModel->find($id);

        if (! $distrito) {
            return redirect()->to(site_url('distrito'))->with('error', 'El registro de distrito no existe.');
        }

        $postData = [
            'iddistrito' => $id,
            'nombre'     => trim((string)$this->request->getPost('nombre')),
        ];

        if (! $this->distritoModel->update($id, $postData)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->distritoModel->errors());
        }

        return redirect()->to(site_url('distrito/ver/' . $id))->with('success', 'Distrito actualizado correctamente.');
    }

    /**
     * Eliminar un registro de distrito
     */
    public function delete($id = null)
    {
        $distrito = $this->distritoModel->find($id);

        if (! $distrito) {
            return redirect()->to(site_url('distrito'))->with('error', 'El registro no existe.');
        }

        $parroquiasCount = $this->distritoModel->countParroquiasAsociadas((int)$id);
        if ($parroquiasCount > 0) {
            return redirect()->to(site_url('distrito/ver/' . $id))
                             ->with('error', "No se puede eliminar el distrito '{$distrito['nombre']}' porque tiene {$parroquiasCount} parroquia(s) asociada(s). Debe reasignar o eliminar las parroquias primero.");
        }

        $this->distritoModel->delete($id);

        return redirect()->to(site_url('distrito'))->with('success', 'Registro de distrito eliminado con éxito.');
    }
}
