<?php

namespace App\Controllers;

use App\Models\PersonaModel;
use App\Models\SexoModel;

class PersonaController extends BaseController
{
    protected PersonaModel $personaModel;
    protected SexoModel $sexoModel;

    public function __construct()
    {
        $this->personaModel = new PersonaModel();
        $this->sexoModel    = new SexoModel();
    }

    /**
     * Visualizador individual de personas (un registro a la vez con menú superior de navegación)
     */
    public function index($id = null)
    {
        $personaIds = $this->personaModel->getPersonaIdsOrdered();
        $total      = count($personaIds);

        // Permitir parámetro por query string ?id= o ?pos=
        if ($id === null) {
            $queryId = $this->request->getGet('id');
            if ($queryId !== null && is_numeric($queryId)) {
                $id = (int)$queryId;
            }
        }

        $currentIndex   = 0;
        $currentPersona = null;
        $usuarioInfo    = null;
        $dignidades     = [];

        if ($total > 0) {
            if ($id !== null) {
                $pos = array_search((int)$id, $personaIds);
                $currentIndex = ($pos !== false) ? $pos : 0;
            } elseif ($this->request->getGet('pos') !== null && is_numeric($this->request->getGet('pos'))) {
                $posQuery = (int)$this->request->getGet('pos') - 1;
                $currentIndex = ($posQuery >= 0 && $posQuery < $total) ? $posQuery : 0;
            }

            $currentId      = $personaIds[$currentIndex];
            $currentPersona = $this->personaModel->getPersonasWithSexo($currentId);
            $usuarioInfo    = $this->personaModel->getUsuarioDePersona($currentId);
            $dignidades     = $this->personaModel->getDignidadesDePersona($currentId);
        }

        $firstId = $total > 0 ? $personaIds[0] : null;
        $prevId  = ($total > 0 && $currentIndex > 0) ? $personaIds[$currentIndex - 1] : null;
        $nextId  = ($total > 0 && $currentIndex < $total - 1) ? $personaIds[$currentIndex + 1] : null;
        $lastId  = $total > 0 ? $personaIds[$total - 1] : null;

        $data = [
            'title'          => $currentPersona ? 'Persona: ' . $currentPersona['nombre'] . ' ' . $currentPersona['apellidos'] . ' - Control Electoral' : 'Padrón de Personas - Control Electoral',
            'currentPersona' => $currentPersona,
            'usuarioInfo'    => $usuarioInfo,
            'dignidades'     => $dignidades,
            'currentIndex'   => $currentIndex,
            'total'          => $total,
            'firstId'        => $firstId,
            'prevId'         => $prevId,
            'nextId'         => $nextId,
            'lastId'         => $lastId,
            'allIds'         => $personaIds,
        ];

        return view('persona/index', $data);
    }

    /**
     * Listado general de todas las personas en formato tabla
     */
    public function listar()
    {
        $personas = $this->personaModel->getPersonasWithSexo();

        $data = [
            'title'    => 'Listado General de Personas - Control Electoral',
            'personas' => $personas,
        ];

        return view('persona/listar', $data);
    }

    /**
     * Ver ficha detallada de una persona
     */
    public function show($id = null)
    {
        $persona = $this->personaModel->getPersonasWithSexo($id);

        if (! $persona) {
            return redirect()->to(site_url('persona'))->with('error', 'Persona no encontrada.');
        }

        $data = [
            'title'   => 'Ficha de Persona: ' . $persona['nombre'] . ' ' . $persona['apellidos'],
            'persona' => $persona,
        ];

        return view('persona/show', $data);
    }

    /**
     * Formulario para registrar una nueva persona
     */
    public function create()
    {
        $sexos = $this->sexoModel->orderBy('nombre', 'ASC')->findAll();

        $data = [
            'title'  => 'Registrar Nueva Persona',
            'sexos'  => $sexos,
            'errors' => session()->getFlashdata('errors') ?? [],
            'old'    => session()->getFlashdata('old') ?? [],
        ];

        return view('persona/create', $data);
    }

    /**
     * Guardar el registro de la persona
     */
    public function store()
    {
        $postData = [
            'cedula'          => trim((string)$this->request->getPost('cedula')),
            'nombre'          => trim((string)$this->request->getPost('nombre')),
            'apellidos'       => trim((string)$this->request->getPost('apellidos')),
            'fechanacimiento' => trim((string)$this->request->getPost('fechanacimiento')),
            'idsexo'          => $this->request->getPost('idsexo'),
        ];

        $insertId = $this->personaModel->insert($postData);

        if (! $insertId) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->personaModel->errors())
                             ->with('old', $postData);
        }

        return redirect()->to(site_url('persona/ver/' . $insertId))->with('success', 'Persona registrada correctamente.');
    }

    /**
     * Formulario para editar una persona
     */
    public function edit($id = null)
    {
        $persona = $this->personaModel->find($id);

        if (! $persona) {
            return redirect()->to(site_url('persona'))->with('error', 'El registro de la persona no existe.');
        }

        $sexos = $this->sexoModel->orderBy('nombre', 'ASC')->findAll();

        $data = [
            'title'   => 'Editar Persona: ' . $persona['nombre'] . ' ' . $persona['apellidos'],
            'persona' => $persona,
            'sexos'   => $sexos,
            'errors'  => session()->getFlashdata('errors') ?? [],
        ];

        return view('persona/edit', $data);
    }

    /**
     * Actualizar los datos de la persona
     */
    public function update($id = null)
    {
        $persona = $this->personaModel->find($id);

        if (! $persona) {
            return redirect()->to(site_url('persona'))->with('error', 'La persona a actualizar no existe.');
        }

        $postData = [
            'idpersona'       => $id,
            'cedula'          => trim((string)$this->request->getPost('cedula')),
            'nombre'          => trim((string)$this->request->getPost('nombre')),
            'apellidos'       => trim((string)$this->request->getPost('apellidos')),
            'fechanacimiento' => trim((string)$this->request->getPost('fechanacimiento')),
            'idsexo'          => $this->request->getPost('idsexo'),
        ];

        if (! $this->personaModel->update($id, $postData)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->personaModel->errors());
        }

        return redirect()->to(site_url('persona/ver/' . $id))->with('success', 'Datos de la persona actualizados exitosamente.');
    }

    /**
     * Eliminar el registro de una persona
     */
    public function delete($id = null)
    {
        $persona = $this->personaModel->find($id);

        if (! $persona) {
            return redirect()->to(site_url('persona'))->with('error', 'La persona no existe.');
        }

        // Proteger clave foránea si tiene cuenta de usuario asignada
        $totalUsuarios = $this->personaModel->countUsuariosAsociados($id);
        if ($totalUsuarios > 0) {
            return redirect()->to(site_url('persona/ver/' . $id))->with(
                'error',
                "No se puede eliminar a la persona \"{$persona['nombre']} {$persona['apellidos']}\" porque tiene {$totalUsuarios} cuenta(s) de usuario asignada(s)."
            );
        }

        // Proteger clave foránea si está registrada como candidata en una dignidad
        $totalDignidades = $this->personaModel->countDignidadesAsociadas($id);
        if ($totalDignidades > 0) {
            return redirect()->to(site_url('persona/ver/' . $id))->with(
                'error',
                "No se puede eliminar a la persona \"{$persona['nombre']} {$persona['apellidos']}\" porque está registrada en {$totalDignidades} candidatura(s) o dignidad(es) electoral(es)."
            );
        }

        $this->personaModel->delete($id);

        return redirect()->to(site_url('persona'))->with('success', 'Registro de persona eliminado exitosamente.');
    }

    /**
     * Cargar y guardar la foto de la persona en repositorio/fotos/{cedula}.jpg
     */
    public function subirFoto($id = null)
    {
        $persona = $this->personaModel->find($id);

        if (! $persona) {
            return redirect()->to(site_url('persona'))->with('error', 'La persona no existe.');
        }

        $file = $this->request->getFile('foto');

        if (! $file || ! $file->isValid()) {
            return redirect()->to(site_url('persona/ver/' . $id))->with('error', 'Debe seleccionar un archivo de imagen válido.');
        }

        $mimeType = $file->getMimeType();
        $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];

        if (! in_array($mimeType, $allowedMimes)) {
            return redirect()->to(site_url('persona/ver/' . $id))->with('error', 'Formato no permitido. Solo se aceptan imágenes JPG, PNG o WEBP.');
        }

        $directorio = ROOTPATH . 'repositorio/fotos/';
        if (! is_dir($directorio)) {
            mkdir($directorio, 0777, true);
        }

        $nombreArchivo = $persona['cedula'] . '.jpg';
        $rutaDestino   = $directorio . $nombreArchivo;

        try {
            // Si la imagen es PNG o WEBP, convertirla a JPEG para garantizar extensión y formato .jpg
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

            return redirect()->to(site_url('persona/ver/' . $id))->with('success', "Foto guardada exitosamente como {$nombreArchivo} en repositorio/fotos.");
        } catch (\Exception $e) {
            return redirect()->to(site_url('persona/ver/' . $id))->with('error', 'Error al procesar la foto: ' . $e->getMessage());
        }
    }

    /**
     * Servir la foto de la persona desde repositorio/fotos/{cedula}.jpg
     */
    public function foto($id = null)
    {
        $persona = $this->personaModel->find($id);

        if (! $persona) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $rutaFoto = ROOTPATH . 'repositorio/fotos/' . $persona['cedula'] . '.jpg';

        if (! file_exists($rutaFoto)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Foto no encontrada');
        }

        return $this->response
                    ->setHeader('Content-Type', 'image/jpeg')
                    ->setHeader('Cache-Control', 'no-cache, must-revalidate')
                    ->setBody(file_get_contents($rutaFoto));
    }

    /**
     * Eliminar la foto registrada de la persona
     */
    public function eliminarFoto($id = null)
    {
        $persona = $this->personaModel->find($id);

        if (! $persona) {
            return redirect()->to(site_url('persona'))->with('error', 'La persona no existe.');
        }

        $rutaFoto = ROOTPATH . 'repositorio/fotos/' . $persona['cedula'] . '.jpg';

        if (file_exists($rutaFoto)) {
            unlink($rutaFoto);
            return redirect()->to(site_url('persona/ver/' . $id))->with('success', 'Foto eliminada correctamente del repositorio.');
        }

        return redirect()->to(site_url('persona/ver/' . $id))->with('error', 'La persona no tiene foto registrada.');
    }
}
