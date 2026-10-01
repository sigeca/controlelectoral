<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h2 class="h3 mb-0 text-gray-800"><i class="bi bi-table me-2 text-primary"></i>Listado General de Parroquias</h2>
        <p class="text-muted small mb-0">Gestión completa en tabla de parroquias urbanas y rurales con recintos asociados.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= site_url('parroquia') ?>" class="btn btn-outline-primary shadow-sm">
            <i class="bi bi-card-heading me-1"></i> Ver por Registro
        </a>
        <a href="<?= site_url('parroquia/create') ?>" class="btn btn-primary shadow-sm">
            <i class="bi bi-plus-circle me-1"></i> Nueva Parroquia
        </a>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-white py-3 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
        <div class="fw-bold text-primary">
            <i class="bi bi-geo-alt me-1"></i> Listado de Parroquias (<?= count($parroquias) ?>)
        </div>
        <div class="col-12 col-sm-4">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                <input type="text" id="filtroParroquias" class="form-control" placeholder="Buscar por parroquia, tipo, distrito, cantón...">
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tablaParroquias">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 80px;">ID</th>
                        <th>Nombre de la Parroquia</th>
                        <th>Tipo</th>
                        <th>Distrito</th>
                        <th>Cantón / Provincia</th>
                        <th class="text-center">Recintos</th>
                        <th class="text-center" style="width: 190px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($parroquias)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-geo fs-1 d-block mb-2 text-secondary"></i>
                                <div class="fs-5">No existen parroquias registradas actualmente.</div>
                                <p class="small text-muted mb-3">Comience registrando una nueva parroquia en el sistema.</p>
                                <a href="<?= site_url('parroquia/create') ?>" class="btn btn-sm btn-primary">
                                    <i class="bi bi-plus-lg me-1"></i> Registrar Parroquia
                                </a>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($parroquias as $p): ?>
                            <tr>
                                <td class="text-center fw-semibold text-secondary">#<?= esc($p['idparroquia']) ?></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="bg-primary text-white rounded-circle p-2 d-inline-flex justify-content-center align-items-center" style="width: 32px; height: 32px;">
                                            <i class="bi bi-geo-alt-fill" style="font-size: 0.85rem;"></i>
                                        </div>
                                        <a href="<?= site_url('parroquia/ver/' . $p['idparroquia']) ?>" class="fw-bold text-dark text-decoration-none hover-primary fs-6">
                                            <?= esc($p['nombre']) ?>
                                        </a>
                                    </div>
                                </td>
                                <td>
                                    <?php if (!empty($p['tipoparroquia_nombre'])): ?>
                                        <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle px-2 py-1 fs-7">
                                            <i class="bi bi-tag-fill me-1"></i><?= esc($p['tipoparroquia_nombre']) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-light text-muted border px-2 py-1 fs-7">Sin tipo</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($p['distrito_nombre'])): ?>
                                        <span class="badge bg-purple-subtle text-purple border px-2 py-1 fs-7" style="background-color: #f3e8ff; color: #6b21a8; border-color: #e9d5ff;">
                                            <i class="bi bi-map-fill me-1"></i><?= esc($p['distrito_nombre']) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-light text-muted border px-2 py-1 fs-7">Sin distrito</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border px-2 py-1 fs-7">
                                        <i class="bi bi-buildings me-1 text-primary"></i><?= esc($p['canton_nombre'] ?? 'Sin Cantón') ?> &bull; <?= esc($p['provincia_nombre'] ?? 'N/A') ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fs-7 fw-semibold">
                                        <i class="bi bi-building-check me-1"></i><?= $p['total_recintos'] ?> recintos
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="<?= site_url('parroquia/ver/' . $p['idparroquia']) ?>" class="btn btn-outline-primary" title="Ver Ficha de Parroquia">
                                            <i class="bi bi-eye"></i> Ver
                                        </a>
                                        <a href="<?= site_url('parroquia/edit/' . $p['idparroquia']) ?>" class="btn btn-outline-warning" title="Editar">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <a href="<?= site_url('parroquia/delete/' . $p['idparroquia']) ?>" 
                                           class="btn btn-outline-danger" 
                                           title="Eliminar"
                                           onclick="return confirm('¿Está seguro de eliminar la parroquia \'<?= esc($p['nombre']) ?>\'?');">
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
    document.getElementById('filtroParroquias')?.addEventListener('keyup', function() {
        const valor = this.value.toLowerCase().trim();
        const filas = document.querySelectorAll('#tablaParroquias tbody tr');
        
        filas.forEach(fila => {
            const textoFila = fila.textContent.toLowerCase();
            fila.style.display = textoFila.includes(valor) ? '' : 'none';
        });
    });
</script>
<?= $this->endSection() ?>
