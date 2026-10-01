<?php

namespace App\Controllers;

use App\Models\ActaModel;
use App\Models\MezaModel;
use App\Models\DignidadModel;

class ActaController extends BaseController
{
    protected ActaModel $actaModel;
    protected MezaModel $mezaModel;
    protected DignidadModel $dignidadModel;

    public function __construct()
    {
        $this->actaModel     = new ActaModel();
        $this->mezaModel     = new MezaModel();
        $this->dignidadModel = new DignidadModel();
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

        $currentIndex         = 0;
        $currentActa          = null;
        $dignidadesEnActa     = [];
        $totalVotosCandidatos = 0;

        if ($total > 0) {
            if ($id !== null) {
                $pos = array_search((int)$id, $actaIds);
                $currentIndex = ($pos !== false) ? $pos : 0;
            } elseif ($this->request->getGet('pos') !== null && is_numeric($this->request->getGet('pos'))) {
                $posQuery = (int)$this->request->getGet('pos') - 1;
                $currentIndex = ($posQuery >= 0 && $posQuery < $total) ? $posQuery : 0;
            }

            $currentId            = $actaIds[$currentIndex];
            $currentActa          = $this->actaModel->getActaDetailed($currentId);
            $dignidadesEnActa     = $this->actaModel->getDignidadactasDeActa($currentId);
            $totalVotosCandidatos = array_sum(array_column($dignidadesEnActa, 'votacion'));
        }

        $firstId            = $total > 0 ? $actaIds[0] : null;
        $prevId             = ($total > 0 && $currentIndex > 0) ? $actaIds[$currentIndex - 1] : null;
        $nextId             = ($total > 0 && $currentIndex < $total - 1) ? $actaIds[$currentIndex + 1] : null;
        $lastId             = $total > 0 ? $actaIds[$total - 1] : null;
        $todasLasDignidades = $this->dignidadModel->getDignidadesDetailed();

        $data = [
            'title'                => $currentActa ? 'Acta Electoral #' . $currentActa['idacta'] . ' - Control Electoral' : 'Actas Electorales - Control Electoral',
            'currentActa'          => $currentActa,
            'dignidadesEnActa'     => $dignidadesEnActa,
            'todasLasDignidades'   => $todasLasDignidades,
            'totalVotosCandidatos' => $totalVotosCandidatos,
            'currentIndex'         => $currentIndex,
            'total'                => $total,
            'firstId'              => $firstId,
            'prevId'               => $prevId,
            'nextId'               => $nextId,
            'lastId'               => $lastId,
            'allIds'               => $actaIds,
        ];

        return view('acta/index', $data);
    }

    /**
     * Listado general de todas las actas en formato tabla
     */
    public function listar()
    {
        $actas                  = $this->actaModel->getActasDetailed();
        $estadisticasDignidades = $this->actaModel->getEstadisticasDignidadesPorTodasLasActas();

        $data = [
            'title'                  => 'Listado General de Actas Electorales - Control Electoral',
            'actas'                  => $actas,
            'estadisticasDignidades' => $estadisticasDignidades,
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

    /**
     * Cargar y guardar la imagen física del acta de escrutinio en repositorio/actaescrutinio/{idacta}.jpg
     */
    public function subirFoto($id = null)
    {
        $acta = $this->actaModel->find($id);

        if (! $acta) {
            return redirect()->to(site_url('acta'))->with('error', 'El acta de escrutinio no existe.');
        }

        $file = $this->request->getFile('foto') ?? $this->request->getFile('acta');

        if (! $file || ! $file->isValid()) {
            return redirect()->to(site_url('acta/ver/' . $id))->with('error', 'Debe seleccionar un archivo de imagen válido para el acta de escrutinio.');
        }

        $mimeType = $file->getMimeType();
        $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];

        if (! in_array($mimeType, $allowedMimes)) {
            return redirect()->to(site_url('acta/ver/' . $id))->with('error', 'Formato no permitido. Solo se aceptan imágenes JPG, PNG o WEBP.');
        }

        $directorio = ROOTPATH . 'repositorio/actaescrutinio/';
        if (! is_dir($directorio)) {
            mkdir($directorio, 0777, true);
        }

        $nombreArchivo = $acta['idacta'] . '.jpg';
        $rutaDestino   = $directorio . $nombreArchivo;

        try {
            if ($mimeType === 'image/png') {
                $src = imagecreatefrompng($file->getTempName());
                if ($src !== false) {
                    $width  = imagesx($src);
                    $height = imagesy($src);
                    $dest   = imagecreatetruecolor($width, $height);
                    $white  = imagecolorallocate($dest, 255, 255, 255);
                    imagefill($dest, 0, 0, $white);
                    imagecopy($dest, $src, 0, 0, 0, 0, $width, $height);
                    imagejpeg($dest, $rutaDestino, 90);
                    imagedestroy($src);
                    imagedestroy($dest);
                } else {
                    $file->move($directorio, $nombreArchivo, true);
                }
            } elseif ($mimeType === 'image/webp' && function_exists('imagecreatefromwebp')) {
                $src = imagecreatefromwebp($file->getTempName());
                if ($src !== false) {
                    imagejpeg($src, $rutaDestino, 90);
                    imagedestroy($src);
                } else {
                    $file->move($directorio, $nombreArchivo, true);
                }
            } else {
                $file->move($directorio, $nombreArchivo, true);
            }

            return redirect()->to(site_url('acta/ver/' . $id))->with('success', "Imagen del acta guardada exitosamente como {$nombreArchivo} en repositorio/actaescrutinio.");
        } catch (\Exception $e) {
            return redirect()->to(site_url('acta/ver/' . $id))->with('error', 'Error al procesar la imagen del acta: ' . $e->getMessage());
        }
    }

    /**
     * Servir la foto del acta física desde repositorio/actaescrutinio/{idacta}.jpg
     */
    public function foto($id = null)
    {
        $acta = $this->actaModel->find($id);

        if (! $acta) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $rutaActa = ROOTPATH . 'repositorio/actaescrutinio/' . $acta['idacta'] . '.jpg';

        if (! file_exists($rutaActa)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Acta física no encontrada');
        }

        return $this->response
                    ->setHeader('Content-Type', 'image/jpeg')
                    ->setHeader('Cache-Control', 'no-cache, must-revalidate')
                    ->setBody(file_get_contents($rutaActa));
    }

    /**
     * Eliminar la foto del acta física
     */
    public function eliminarFoto($id = null)
    {
        $acta = $this->actaModel->find($id);

        if (! $acta) {
            return redirect()->to(site_url('acta'))->with('error', 'El acta especificada no existe.');
        }

        $rutaActa = ROOTPATH . 'repositorio/actaescrutinio/' . $acta['idacta'] . '.jpg';

        if (file_exists($rutaActa)) {
            unlink($rutaActa);
            return redirect()->to(site_url('acta/ver/' . $id))->with('success', 'Imagen física del acta eliminada correctamente del repositorio.');
        }

        return redirect()->to(site_url('acta/ver/' . $id))->with('error', 'No existe imagen física grabada para esta acta.');
    }
}
