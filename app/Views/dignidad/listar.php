<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h2 class="h3 mb-0 text-gray-800"><i class="bi bi-table me-2 text-primary"></i>Listado General de Dignidades y Candidaturas</h2>
        <p class="text-muted small mb-0">Vista tabular completa de las postulaciones y cargos de elección popular registrados.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= site_url('dignidad') ?>" class="btn btn-outline-primary shadow-sm" title="Ir al Navegador Registro por Registro">
            <i class="bi bi-card-heading me-1"></i> Modo Navegador
        </a>
        <a href="<?= site_url('dignidad/create') ?>" class="btn btn-primary shadow-sm">
            <i class="bi bi-plus-circle me-1"></i> Nueva Candidatura
        </a>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-white py-3 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
        <div class="fw-bold text-primary d-flex align-items-center gap-2 flex-wrap">
            <span><i class="bi bi-person-badge me-1"></i> Candidaturas Registradas (<?= count($dignidades) ?>)</span>
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 small">
                <i class="bi bi-check2-circle me-1"></i><?= count($dignidades) ?> Postulaciones en Total
            </span>
        </div>
        <div class="col-12 col-sm-4">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                <input type="text" id="filtroDignidades" class="form-control" placeholder="Buscar por candidato, cédula, cargo...">
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tablaDignidades">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 80px;">ID</th>
                        <th>Candidato / Persona</th>
                        <th>Cédula</th>
                        <th>Cargo / Dignidad Electoral</th>
                        <th>Sexo</th>
                        <th class="text-center" style="width: 190px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($dignidades)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-person-badge fs-1 d-block mb-2 text-secondary"></i>
                                <div class="fs-5">No existen dignidades o candidaturas registradas actualmente.</div>
                                <p class="small text-muted mb-3">Comience registrando una nueva candidatura en el sistema.</p>
                                <a href="<?= site_url('dignidad/create') ?>" class="btn btn-sm btn-primary">
                                    <i class="bi bi-plus-lg me-1"></i> Registrar Candidatura
                                </a>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($dignidades as $d): ?>
                            <?php 
                                $cedula = $d['persona_cedula'] ?? '';
                                $rutaFoto = ROOTPATH . 'repositorio/fotos/' . $cedula . '.jpg';
                                $tieneFoto = !empty($cedula) && file_exists($rutaFoto);
                            ?>
                            <tr>
                                <td class="text-center fw-semibold text-secondary">#<?= esc($d['iddignidad']) ?></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <?php if ($tieneFoto): ?>
                                            <img src="<?= site_url('persona/foto/' . $d['idpersona']) ?>?v=<?= filemtime($rutaFoto) ?>" 
                                                 alt="Foto" 
                                                 class="rounded-circle border shadow-sm" 
                                                 style="width: 38px; height: 38px; object-fit: cover;">
                                        <?php else: ?>
                                            <div class="bg-primary text-white rounded-circle d-inline-flex justify-content-center align-items-center" 
                                                 style="width: 38px; height: 38px;">
                                                <i class="bi bi-person-fill" style="font-size: 0.95rem;"></i>
                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <span class="fw-bold text-dark fs-6"><?= esc($d['persona_nombre']) ?> <?= esc($d['persona_apellidos']) ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <code class="text-dark bg-light px-2 py-1 border rounded"><?= esc($d['persona_cedula'] ?? 'N/A') ?></code>
                                </td>
                                <td>
                                    <span class="badge bg-warning-subtle text-dark border border-warning-subtle px-2 py-1 fs-7 fw-semibold">
                                        <i class="bi bi-award-fill text-warning me-1"></i><?= esc($d['tipodignidad_nombre'] ?? 'Sin Tipo') ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-secondary border px-2 py-1 fs-7">
                                        <?= esc($d['sexo_nombre'] ?? 'N/A') ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="<?= site_url('dignidad/ver/' . $d['iddignidad']) ?>" class="btn btn-outline-primary" title="Ver en Navegador individual">
                                            <i class="bi bi-eye"></i> Ficha
                                        </a>
                                        <a href="<?= site_url('dignidad/edit/' . $d['iddignidad']) ?>" class="btn btn-outline-warning" title="Editar">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <a href="<?= site_url('dignidad/delete/' . $d['iddignidad']) ?>" 
                                           class="btn btn-outline-danger" 
                                           title="Eliminar"
                                           onclick="return confirm('¿Está seguro de eliminar la postulación de <?= esc($d['persona_nombre'] . ' ' . $d['persona_apellidos']) ?> para <?= esc($d['tipodignidad_nombre']) ?>?');">
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
    document.getElementById('filtroDignidades')?.addEventListener('keyup', function() {
        const valor = this.value.toLowerCase().trim();
        const filas = document.querySelectorAll('#tablaDignidades tbody tr');
        
        filas.forEach(fila => {
            const textoFila = fila.textContent.toLowerCase();
            fila.style.display = textoFila.includes(valor) ? '' : 'none';
        });
    });
</script>
<?= $this->endSection() ?>
