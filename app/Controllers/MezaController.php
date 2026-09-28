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
        $papeletas    = [];

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
            $papeletas   = $this->mezaModel->getPapeletasDeMeza($currentId);
        }

        $firstId = $total > 0 ? $mezaIds[0] : null;
        $prevId  = ($total > 0 && $currentIndex > 0) ? $mezaIds[$currentIndex - 1] : null;
        $nextId  = ($total > 0 && $currentIndex < $total - 1) ? $mezaIds[$currentIndex + 1] : null;
        $lastId  = $total > 0 ? $mezaIds[$total - 1] : null;

        $data = [
            'title'        => $currentMeza ? 'Mesa Electoral #' . $currentMeza['numero'] . ' - Control Electoral' : 'Mesas Electorales - Control Electoral',
            'currentMeza'  => $currentMeza,
            'papeletas'    => $papeletas,
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
        $mezas             = $this->mezaModel->getMezasDetailed();
        $dignidadesPorMeza = $this->mezaModel->getDignidadesPorTodasLasMesas();

        $data = [
            'title'             => 'Listado General de Mesas Electorales - Control Electoral',
            'mezas'             => $mezas,
            'dignidadesPorMeza' => $dignidadesPorMeza,
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

        $totalPapeletas = $this->mezaModel->countMezaDignidadesAsociadas((int)$id);
        if ($totalPapeletas > 0) {
            return redirect()->to(site_url('meza/ver/' . $id))->with(
                'error',
                "No se puede eliminar la Mesa #{$meza['numero']} porque tiene {$totalPapeletas} dignidad(es)/papeleta(s) asignada(s). Debe reasignar o eliminar las papeletas primero."
            );
        }

        $this->mezaModel->delete($id);

        return redirect()->to(site_url('meza'))->with('success', 'Mesa electoral eliminada exitosamente.');
    }

    /**
     * Cargar y guardar la imagen del acta de escrutinio en repositorio/actaescrutinio/{idmeza}.jpg
     */
    public function subirActa($id = null)
    {
        $meza = $this->mezaModel->find($id);

        if (! $meza) {
            return redirect()->to(site_url('meza'))->with('error', 'La mesa electoral no existe.');
        }

        $file = $this->request->getFile('acta') ?? $this->request->getFile('foto');

        if (! $file || ! $file->isValid()) {
            return redirect()->to(site_url('meza/ver/' . $id))->with('error', 'Debe seleccionar un archivo de imagen válido para el acta de escrutinio.');
        }

        $mimeType = $file->getMimeType();
        $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];

        if (! in_array($mimeType, $allowedMimes)) {
            return redirect()->to(site_url('meza/ver/' . $id))->with('error', 'Formato no permitido. Solo se aceptan imágenes JPG, PNG o WEBP.');
        }

        $directorio = ROOTPATH . 'repositorio/actaescrutinio/';
        if (! is_dir($directorio)) {
            mkdir($directorio, 0777, true);
        }

        $nombreArchivo = $meza['idmeza'] . '.jpg';
        $rutaDestino   = $directorio . $nombreArchivo;

        try {
            // Si la imagen es PNG o WEBP, convertirla a JPEG para garantizar formato .jpg homogéneo
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
                // Si ya es JPEG / JPG
                $file->move($directorio, $nombreArchivo, true);
            }

            return redirect()->to(site_url('meza/ver/' . $id))->with('success', "Acta de escrutinio guardada exitosamente como {$nombreArchivo} en repositorio/actaescrutinio.");
        } catch (\Exception $e) {
            return redirect()->to(site_url('meza/ver/' . $id))->with('error', 'Error al procesar el acta de escrutinio: ' . $e->getMessage());
        }
    }

    /**
     * Servir la foto del acta de escrutinio desde repositorio/actaescrutinio/{idmeza}.jpg
     */
    public function acta($id = null)
    {
        $meza = $this->mezaModel->find($id);

        if (! $meza) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $rutaActa = ROOTPATH . 'repositorio/actaescrutinio/' . $meza['idmeza'] . '.jpg';

        if (! file_exists($rutaActa)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Acta no encontrada');
        }

        return $this->response
                    ->setHeader('Content-Type', 'image/jpeg')
                    ->setHeader('Cache-Control', 'no-cache, must-revalidate')
                    ->setBody(file_get_contents($rutaActa));
    }

    /**
     * Eliminar el acta de escrutinio de la mesa
     */
    public function eliminarActa($id = null)
    {
        $meza = $this->mezaModel->find($id);

        if (! $meza) {
            return redirect()->to(site_url('meza'))->with('error', 'La mesa electoral no existe.');
        }

        $rutaActa = ROOTPATH . 'repositorio/actaescrutinio/' . $meza['idmeza'] . '.jpg';

        if (file_exists($rutaActa)) {
            unlink($rutaActa);
            return redirect()->to(site_url('meza/ver/' . $id))->with('success', 'Acta de escrutinio eliminada correctamente del repositorio.');
        }

        return redirect()->to(site_url('meza/ver/' . $id))->with('error', 'No existe acta de escrutinio para eliminar en esta mesa.');
    }
}
