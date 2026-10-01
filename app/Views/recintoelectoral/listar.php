<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h2 class="h3 mb-0 text-gray-800"><i class="bi bi-table me-2 text-primary"></i>Listado General de Recintos Electorales</h2>
        <p class="text-muted small mb-0">Vista tabular completa de recintos, instituciones educativas y sedes de votación.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= site_url('recintoelectoral') ?>" class="btn btn-outline-primary shadow-sm">
            <i class="bi bi-card-heading me-1"></i> Ver por Registro
        </a>
        <a href="<?= site_url('recintoelectoral/create') ?>" class="btn btn-primary shadow-sm">
            <i class="bi bi-plus-circle me-1"></i> Nuevo Recinto
        </a>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-white py-3 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
        <div class="fw-bold text-primary d-flex align-items-center gap-2 flex-wrap">
            <span><i class="bi bi-buildings me-1"></i> Listado de Recintos Electorales (<?= count($recintos) ?>)</span>
            <?php 
                $totalElectores = array_sum(array_column($recintos, 'numeroelectores'));
            ?>
            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 small">
                <i class="bi bi-people-fill me-1"></i><?= number_format($totalElectores, 0, ',', '.') ?> Electores Habilitados
            </span>
        </div>
        <div class="col-12 col-sm-4">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                <input type="text" id="filtroRecintos" class="form-control" placeholder="Buscar por recinto, zona, parroquia, cantón...">
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tablaRecintos">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 80px;">ID</th>
                        <th>Nombre del Recinto</th>
                        <th>Zona</th>
                        <th>Parroquia</th>
                        <th>Cantón</th>
                        <th>Provincia</th>
                        <th class="text-center" style="width: 140px;">N° Electores</th>
                        <th class="text-center" style="width: 190px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($recintos)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-building fs-1 d-block mb-2 text-secondary"></i>
                                <div class="fs-5">No existen recintos electorales registrados actualmente.</div>
                                <p class="small text-muted mb-3">Comience registrando un nuevo recinto electoral en el sistema.</p>
                                <a href="<?= site_url('recintoelectoral/create') ?>" class="btn btn-sm btn-primary">
                                    <i class="bi bi-plus-lg me-1"></i> Registrar Recinto
                                </a>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($recintos as $r): ?>
                            <tr>
                                <td class="text-center fw-semibold text-secondary">#<?= esc($r['idrecintoelectoral']) ?></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="bg-primary text-white rounded-circle p-2 d-inline-flex justify-content-center align-items-center" style="width: 32px; height: 32px;">
                                            <i class="bi bi-building" style="font-size: 0.85rem;"></i>
                                        </div>
                                        <a href="<?= site_url('recintoelectoral/ver/' . $r['idrecintoelectoral']) ?>" class="fw-bold text-dark text-decoration-none hover-primary fs-6">
                                            <?= esc($r['nombre']) ?>
                                        </a>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border px-2 py-1 fs-7">
                                        <i class="bi bi-pin-map-fill me-1 text-primary"></i><?= esc($r['zona_nombre'] ?? 'Sin Zona') ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border px-2 py-1 fs-7">
                                        <i class="bi bi-geo-fill me-1 text-secondary"></i><?= esc($r['parroquia_nombre'] ?? 'Sin Parroquia') ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border px-2 py-1 fs-7">
                                        <i class="bi bi-buildings me-1 text-secondary"></i><?= esc($r['canton_nombre'] ?? 'Sin Cantón') ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 fs-7">
                                        <i class="bi bi-map-fill me-1"></i><?= esc($r['provincia_nombre'] ?? 'Sin Provincia') ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fs-7 fw-semibold">
                                        <i class="bi bi-people-fill me-1"></i><?= number_format((int)($r['numeroelectores'] ?? 0), 0, ',', '.') ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="<?= site_url('recintoelectoral/ver/' . $r['idrecintoelectoral']) ?>" class="btn btn-outline-primary" title="Ver Registro Ficha">
                                            <i class="bi bi-eye"></i> Ver
                                        </a>
                                        <a href="<?= site_url('recintoelectoral/edit/' . $r['idrecintoelectoral']) ?>" class="btn btn-outline-warning" title="Editar">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <a href="<?= site_url('recintoelectoral/delete/' . $r['idrecintoelectoral']) ?>" 
                                           class="btn btn-outline-danger" 
                                           title="Eliminar"
                                           onclick="return confirm('¿Está seguro de eliminar el recinto \'<?= esc($r['nombre']) ?>\'?');">
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
    document.getElementById('filtroRecintos')?.addEventListener('keyup', function() {
        const valor = this.value.toLowerCase().trim();
        const filas = document.querySelectorAll('#tablaRecintos tbody tr');
        
        filas.forEach(fila => {
            const textoFila = fila.textContent.toLowerCase();
            fila.style.display = textoFila.includes(valor) ? '' : 'none';
        });
    });
</script>
<?= $this->endSection() ?>
