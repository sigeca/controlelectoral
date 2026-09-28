<?php

namespace App\Controllers;

use App\Models\MezadignidadModel;
use App\Models\MezaModel;
use App\Models\DignidadModel;

class MezadignidadController extends BaseController
{
    protected MezadignidadModel $mezadignidadModel;
    protected MezaModel $mezaModel;
    protected DignidadModel $dignidadModel;

    public function __construct()
    {
        $this->mezadignidadModel = new MezadignidadModel();
        $this->mezaModel         = new MezaModel();
        $this->dignidadModel     = new DignidadModel();
    }

    /**
     * Listar todas las dignidades a ser elegidas en cada mesa con la cantidad de papeletas contadas
     */
    public function index()
    {
        $asignaciones = $this->mezadignidadModel->getMezadignidadesDetailed();

        $data = [
            'title'        => 'Dignidades a Elegir por Mesa - Control Electoral',
            'asignaciones' => $asignaciones,
        ];

        return view('mezadignidad/index', $data);
    }

    /**
     * Formulario para registrar una nueva dignidad a elegir en mesa y cantidad de papeletas contadas
     */
    public function create()
    {
        $mezas      = $this->mezaModel->getMezasDetailed();
        $dignidades = $this->dignidadModel->getDignidadesDetailed();

        $selectedMeza = $this->request->getGet('idmeza');
        $oldData = session()->getFlashdata('old') ?? [];
        if (!empty($selectedMeza) && empty($oldData['idmeza'])) {
            $oldData['idmeza'] = $selectedMeza;
        }

        $data = [
            'title'      => 'Asignar Dignidad a Elegir en Mesa',
            'mezas'      => $mezas,
            'dignidades' => $dignidades,
            'errors'     => session()->getFlashdata('errors') ?? [],
            'old'        => $oldData,
        ];

        return view('mezadignidad/create', $data);
    }

    /**
     * Guardar una nueva asignación
     */
    public function store()
    {
        // En caso de acceso directo por GET o redirección por SSL/servidor
        if ($this->request->is('get')) {
            return redirect()->to(site_url('mezadignidad/create'));
        }

        $postData = [
            'idmeza'         => $this->request->getPost('idmeza'),
            'iddignidad'     => $this->request->getPost('iddignidad'),
            'numeropapeleta' => $this->request->getPost('numeropapeleta'),
        ];

        if (! $this->mezadignidadModel->insert($postData)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->mezadignidadModel->errors())
                             ->with('old', $postData);
        }

        return redirect()->to(site_url('mezadignidad'))->with('success', 'Dignidad a elegir en la mesa registrada exitosamente.');
    }

    /**
     * Formulario para editar una asignación existente
     */
    public function edit($id = null)
    {
        $asignacion = $this->mezadignidadModel->find($id);

        if (! $asignacion) {
            return redirect()->to(site_url('mezadignidad'))->with('error', 'El registro de dignidad en mesa no existe.');
        }

        $mezas      = $this->mezaModel->getMezasDetailed();
        $dignidades = $this->dignidadModel->getDignidadesDetailed();

        $data = [
            'title'      => 'Editar Dignidad a Elegir en Mesa #' . $asignacion['idmezadignidad'],
            'asignacion' => $asignacion,
            'mezas'      => $mezas,
            'dignidades' => $dignidades,
            'errors'     => session()->getFlashdata('errors') ?? [],
        ];

        return view('mezadignidad/edit', $data);
    }

    /**
     * Actualizar los datos de la asignación
     */
    public function update($id = null)
    {
        // En caso de acceso directo por GET o redirección por SSL/servidor
        if ($this->request->is('get')) {
            return redirect()->to(site_url('mezadignidad/edit/' . $id));
        }

        $asignacion = $this->mezadignidadModel->find($id);

        if (! $asignacion) {
            return redirect()->to(site_url('mezadignidad'))->with('error', 'El registro a actualizar no existe.');
        }

        $postData = [
            'idmezadignidad' => $id,
            'idmeza'         => $this->request->getPost('idmeza'),
            'iddignidad'     => $this->request->getPost('iddignidad'),
            'numeropapeleta' => $this->request->getPost('numeropapeleta'),
        ];

        if (! $this->mezadignidadModel->update($id, $postData)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->mezadignidadModel->errors());
        }

        return redirect()->to(site_url('mezadignidad'))->with('success', 'Dignidad a elegir en la mesa actualizada correctamente.');
    }

    /**
     * Eliminar una asignación
     */
    public function delete($id = null)
    {
        $asignacion = $this->mezadignidadModel->find($id);

        if (! $asignacion) {
            return redirect()->to(site_url('mezadignidad'))->with('error', 'El registro no existe.');
        }

        $this->mezadignidadModel->delete($id);

        return redirect()->to(site_url('mezadignidad'))->with('success', 'Dignidad a elegir en la mesa eliminada exitosamente.');
    }
}
