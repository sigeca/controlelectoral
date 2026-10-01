<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h2 class="h3 mb-0 text-gray-800"><i class="bi bi-table me-2 text-primary"></i>Listado General de Actas Electorales</h2>
        <p class="text-muted small mb-0">Vista tabular completa de actas de escrutinio registradas en el sistema.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= site_url('acta') ?>" class="btn btn-outline-primary shadow-sm">
            <i class="bi bi-card-heading me-1"></i> Ver por Registro
        </a>
        <a href="<?= site_url('acta/create') ?>" class="btn btn-primary shadow-sm">
            <i class="bi bi-plus-circle me-1"></i> Nueva Acta
        </a>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-white py-3 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
        <div class="fw-bold text-primary">
            <i class="bi bi-file-earmark-text me-1"></i> Listado de Actas Electorales (<?= count($actas) ?>)
        </div>
        <div class="col-12 col-sm-4">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                <input type="text" id="filtroActas" class="form-control" placeholder="Buscar por mesa, recinto, números...">
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tablaActas">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 80px;">ID</th>
                        <th>Mesa Electoral</th>
                        <th>Recinto / Ubicación</th>
                        <th class="text-center">Total Papeletas</th>
                        <th class="text-center">Blancos</th>
                        <th class="text-center">Nulos</th>
                        <th class="text-center">Válidos</th>
                        <th class="text-center" style="width: 190px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($actas)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                                <div class="fs-5">No existen actas registradas actualmente.</div>
                                <p class="small text-muted mb-3">Comience registrando una nueva acta electoral en el sistema.</p>
                                <a href="<?= site_url('acta/create') ?>" class="btn btn-sm btn-primary">
                                    <i class="bi bi-plus-lg me-1"></i> Registrar Nueva Acta
                                </a>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($actas as $item): ?>
                            <?php $validos = $item['totalpapeleta'] - ($item['totalblancos'] + $item['totalnulos']); ?>
                            <tr>
                                <td class="text-center fw-semibold text-secondary">#<?= esc($item['idacta']) ?></td>
                                <td>
                                    <a href="<?= site_url('acta/ver/' . $item['idacta']) ?>" class="fw-bold text-dark text-decoration-none hover-primary fs-6">
                                        Mesa #<?= esc($item['meza_numero'] ?? $item['idmeza']) ?> (<?= esc($item['meza_sexo'] ?? 'General') ?>)
                                    </a>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark"><?= esc($item['recinto_nombre'] ?? 'Sin recinto') ?></div>
                                    <div class="small text-muted">
                                        <?= esc($item['parroquia_nombre'] ?? '') ?><?= !empty($item['canton_nombre']) ? ', ' . esc($item['canton_nombre']) : '' ?>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-primary-subtle text-primary border px-2.5 py-1 fs-7 fw-bold">
                                        <?= number_format($item['totalpapeleta'], 0, ',', '.') ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-warning-subtle text-warning-emphasis border px-2 py-1 fs-7">
                                        <?= number_format($item['totalblancos'], 0, ',', '.') ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-danger-subtle text-danger border px-2 py-1 fs-7">
                                        <?= number_format($item['totalnulos'], 0, ',', '.') ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-success-subtle text-success border px-2.5 py-1 fs-7 fw-bold">
                                        <?= number_format(max(0, $validos), 0, ',', '.') ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="<?= site_url('acta/ver/' . $item['idacta']) ?>" class="btn btn-outline-primary" title="Ver Ficha">
                                            <i class="bi bi-eye"></i> Ver
                                        </a>
                                        <a href="<?= site_url('acta/edit/' . $item['idacta']) ?>" class="btn btn-outline-warning" title="Editar">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <a href="<?= site_url('acta/delete/' . $item['idacta']) ?>" 
                                           class="btn btn-outline-danger" 
                                           title="Eliminar"
                                           onclick="return confirm('¿Está seguro de eliminar el Acta Electoral ID #<?= esc($item['idacta']) ?>?');">
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
    document.getElementById('filtroActas')?.addEventListener('keyup', function() {
        const valor = this.value.toLowerCase().trim();
        const filas = document.querySelectorAll('#tablaActas tbody tr');
        
        filas.forEach(fila => {
            const textoFila = fila.textContent.toLowerCase();
            fila.style.display = textoFila.includes(valor) ? '' : 'none';
        });
    });
</script>
<?= $this->endSection() ?>
