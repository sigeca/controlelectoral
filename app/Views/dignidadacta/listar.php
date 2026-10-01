<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h2 class="h3 mb-0 text-gray-800"><i class="bi bi-table me-2 text-primary"></i>Listado General de Votación por Dignidad (Acta)</h2>
        <p class="text-muted small mb-0">Vista tabular completa del conteo de votos de candidatos por acta de escrutinio.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= site_url('dignidadacta') ?>" class="btn btn-outline-primary shadow-sm">
            <i class="bi bi-card-heading me-1"></i> Ver por Registro
        </a>
        <a href="<?= site_url('dignidadacta/create') ?>" class="btn btn-primary shadow-sm">
            <i class="bi bi-plus-circle me-1"></i> Nueva Votación
        </a>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-white py-3 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
        <div class="fw-bold text-primary">
            <i class="bi bi-box-seam me-1"></i> Registros de Votación por Candidatura (<?= count($registros) ?>)
        </div>
        <div class="col-12 col-sm-4">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                <input type="text" id="filtroDignidadacta" class="form-control" placeholder="Buscar por candidato, acta, dignidad...">
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tablaDignidadacta">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 80px;">ID</th>
                        <th>Candidato / Dignidad</th>
                        <th>Acta / Mesa Electoral</th>
                        <th>Recinto Electoral</th>
                        <th class="text-center">Votos Obtenidos</th>
                        <th class="text-center" style="width: 190px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($registros)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                                <div class="fs-5">No existen registros de votación actualmente.</div>
                                <p class="small text-muted mb-3">Comience registrando votos de candidatos por acta.</p>
                                <a href="<?= site_url('dignidadacta/create') ?>" class="btn btn-sm btn-primary">
                                    <i class="bi bi-plus-lg me-1"></i> Registrar Votación
                                </a>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($registros as $item): ?>
                            <?php
                                $cedula = $item['candidato_cedula'] ?? '';
                                $idPersona = $item['candidato_idpersona'] ?? 0;
                                $rutaFotoFisica = ROOTPATH . 'repositorio/fotos/' . $cedula . '.jpg';
                                $tieneFoto = !empty($cedula) && file_exists($rutaFotoFisica);
                                $urlFoto = $tieneFoto ? site_url('persona/foto/' . $idPersona) . '?v=' . filemtime($rutaFotoFisica) : null;
                            ?>
                            <tr>
                                <td class="text-center fw-semibold text-secondary">#<?= esc($item['iddignidadacta']) ?></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2.5">
                                        <?php if ($tieneFoto): ?>
                                            <img src="<?= $urlFoto ?>" 
                                                 alt="Foto de <?= esc($item['candidato_nombre']) ?>" 
                                                 class="rounded-circle shadow-sm border border-2 border-white flex-shrink-0" 
                                                 style="width: 36px; height: 36px; object-fit: cover;">
                                        <?php else: ?>
                                            <div class="bg-primary-subtle text-primary rounded-circle p-2 d-inline-flex justify-content-center align-items-center flex-shrink-0" style="width: 36px; height: 36px; font-size: 0.85rem;">
                                                <i class="bi bi-person-fill"></i>
                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <a href="<?= site_url('dignidadacta/ver/' . $item['iddignidadacta']) ?>" class="fw-bold text-dark text-decoration-none hover-primary fs-6">
                                                <?= esc(($item['candidato_apellidos'] ?? '') . ' ' . ($item['candidato_nombre'] ?? 'Sin Candidato')) ?>
                                            </a>
                                            <div class="small text-muted">
                                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-0.5 fs-7">
                                                    <?= esc($item['tipodignidad_nombre'] ?? 'Candidatura') ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <a href="<?= site_url('acta/ver/' . $item['idacta']) ?>" class="fw-semibold text-primary text-decoration-none">
                                        Acta #<?= esc($item['idacta']) ?>
                                    </a>
                                    <div class="small text-muted">
                                        Mesa #<?= esc($item['meza_numero'] ?? 'N/A') ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-dark fw-semibold"><?= esc($item['recinto_nombre'] ?? 'N/A') ?></span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-success-subtle text-success border px-3 py-1 fs-6 fw-bold">
                                        <i class="bi bi-check-circle-fill me-1"></i><?= number_format($item['votacion'], 0, ',', '.') ?> votos
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="<?= site_url('dignidadacta/ver/' . $item['iddignidadacta']) ?>" class="btn btn-outline-primary" title="Ver Ficha">
                                            <i class="bi bi-eye"></i> Ver
                                        </a>
                                        <a href="<?= site_url('dignidadacta/edit/' . $item['iddignidadacta']) ?>" class="btn btn-outline-warning" title="Editar">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <a href="<?= site_url('dignidadacta/delete/' . $item['iddignidadacta']) ?>" 
                                           class="btn btn-outline-danger" 
                                           title="Eliminar"
                                           onclick="return confirm('¿Está seguro de eliminar esta votación (ID #<?= esc($item['iddignidadacta']) ?>)?');">
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
    document.getElementById('filtroDignidadacta')?.addEventListener('keyup', function() {
        const valor = this.value.toLowerCase().trim();
        const filas = document.querySelectorAll('#tablaDignidadacta tbody tr');
        
        filas.forEach(fila => {
            const textoFila = fila.textContent.toLowerCase();
            fila.style.display = textoFila.includes(valor) ? '' : 'none';
        });
    });
</script>
<?= $this->endSection() ?>
