<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h2 class="h3 mb-0 text-gray-800"><i class="bi bi-table me-2 text-primary"></i>Listado General del Padrón de Personas</h2>
        <p class="text-muted small mb-0">Vista tabular completa de los ciudadanos y personas registradas en el sistema.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= site_url('persona') ?>" class="btn btn-outline-primary shadow-sm" title="Ir al Navegador Registro por Registro">
            <i class="bi bi-card-heading me-1"></i> Modo Navegador
        </a>
        <a href="<?= site_url('persona/create') ?>" class="btn btn-primary shadow-sm">
            <i class="bi bi-person-plus-fill me-1"></i> Nueva Persona
        </a>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-white py-3 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
        <div class="fw-bold text-primary d-flex align-items-center gap-2 flex-wrap">
            <span><i class="bi bi-people me-1"></i> Personas Registradas (<?= count($personas) ?>)</span>
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 small">
                <i class="bi bi-check2-square me-1"></i><?= count($personas) ?> Registros en Total
            </span>
        </div>
        <div class="col-12 col-sm-4">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                <input type="text" id="filtroPersonas" class="form-control" placeholder="Buscar por cédula, nombres o sexo...">
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tablaPersonas">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 70px;">ID</th>
                        <th>Cédula</th>
                        <th>Nombres y Apellidos</th>
                        <th>Fecha de Nacimiento</th>
                        <th>Edad</th>
                        <th>Sexo</th>
                        <th class="text-center" style="width: 190px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($personas)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-person-x fs-1 d-block mb-2 text-secondary"></i>
                                <div class="fs-5">No existen personas registradas actualmente.</div>
                                <p class="small text-muted mb-3">Comience registrando una nueva persona en el sistema.</p>
                                <a href="<?= site_url('persona/create') ?>" class="btn btn-sm btn-primary">
                                    <i class="bi bi-plus-lg me-1"></i> Registrar Persona
                                </a>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($personas as $p): ?>
                            <?php
                                $edad = '';
                                if (!empty($p['fechanacimiento'])) {
                                    $nac = new DateTime($p['fechanacimiento']);
                                    $hoy = new DateTime();
                                    $edad = $hoy->diff($nac)->y . ' años';
                                }

                                $rutaFoto = ROOTPATH . 'repositorio/fotos/' . $p['cedula'] . '.jpg';
                                $tieneFoto = file_exists($rutaFoto);
                            ?>
                            <tr>
                                <td class="text-center text-muted fw-semibold">#<?= esc($p['idpersona']) ?></td>
                                <td>
                                    <span class="badge bg-light text-dark border font-monospace fs-6">
                                        <?= esc($p['cedula']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <?php if ($tieneFoto): ?>
                                            <img src="<?= site_url('persona/foto/' . $p['idpersona']) ?>?v=<?= filemtime($rutaFoto) ?>" 
                                                 alt="Foto" 
                                                 class="rounded-circle border shadow-sm" 
                                                 style="width: 38px; height: 38px; object-fit: cover;">
                                        <?php else: ?>
                                            <div class="bg-secondary-subtle text-secondary rounded-circle border d-inline-flex justify-content-center align-items-center" 
                                                 style="width: 38px; height: 38px; font-size: 0.95rem;">
                                                <i class="bi bi-person"></i>
                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <div class="fw-bold text-dark"><?= esc($p['nombre'] . ' ' . $p['apellidos']) ?></div>
                                            <div class="small text-muted"><?= esc($p['apellidos']) ?>, <?= esc($p['nombre']) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <i class="bi bi-calendar3 me-1 text-muted"></i>
                                    <?= date('d/m/Y', strtotime($p['fechanacimiento'])) ?>
                                </td>
                                <td>
                                    <span class="badge bg-light text-secondary border"><?= $edad ?></span>
                                </td>
                                <td>
                                    <?php 
                                        $badgeClass = 'bg-secondary';
                                        $sexoNombre = $p['sexo_nombre'] ?? 'No especificado';
                                        if (stripos($sexoNombre, 'masculin') !== false || stripos($sexoNombre, 'hombre') !== false) {
                                            $badgeClass = 'badge-male border border-info-subtle';
                                        } elseif (stripos($sexoNombre, 'femenin') !== false || stripos($sexoNombre, 'mujer') !== false) {
                                            $badgeClass = 'badge-female border border-danger-subtle';
                                        }
                                    ?>
                                    <span class="badge <?= $badgeClass ?> px-2 py-1">
                                        <i class="bi bi-gender-ambiguous me-1"></i><?= esc($sexoNombre) ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="<?= site_url('persona/ver/' . $p['idpersona']) ?>" class="btn btn-outline-primary" title="Ver en Navegador individual">
                                            <i class="bi bi-eye"></i> Ficha
                                        </a>
                                        <a href="<?= site_url('persona/edit/' . $p['idpersona']) ?>" class="btn btn-outline-warning" title="Editar">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <a href="<?= site_url('persona/delete/' . $p['idpersona']) ?>" 
                                           class="btn btn-outline-danger" 
                                           title="Eliminar"
                                           onclick="return confirm('¿Está seguro de eliminar a la persona <?= esc($p['nombre'] . ' ' . $p['apellidos']) ?>?');">
                                            <i class="bi bi-trash3"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.getElementById('filtroPersonas')?.addEventListener('keyup', function() {
        const valor = this.value.toLowerCase().trim();
        const filas = document.querySelectorAll('#tablaPersonas tbody tr');
        
        filas.forEach(fila => {
            const textoFila = fila.textContent.toLowerCase();
            fila.style.display = textoFila.includes(valor) ? '' : 'none';
        });
    });
</script>
<?= $this->endSection() ?>
