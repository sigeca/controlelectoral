<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h2 class="h3 mb-0 text-gray-800"><i class="bi bi-table me-2 text-primary"></i>Listado General de Distritos</h2>
        <p class="text-muted small mb-0">Vista tabular completa de distritos electorales registrados en el sistema.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= site_url('distrito') ?>" class="btn btn-outline-primary shadow-sm">
            <i class="bi bi-card-heading me-1"></i> Ver por Registro
        </a>
        <a href="<?= site_url('distrito/create') ?>" class="btn btn-primary shadow-sm">
            <i class="bi bi-plus-circle me-1"></i> Nuevo Distrito
        </a>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-white py-3 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
        <div class="fw-bold text-primary">
            <i class="bi bi-map me-1"></i> Listado de Distritos (<?= count($distritos) ?>)
        </div>
        <div class="col-12 col-sm-4">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                <input type="text" id="filtroDistritos" class="form-control" placeholder="Buscar por nombre de distrito...">
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tablaDistritos">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 100px;">ID</th>
                        <th>Nombre del Distrito</th>
                        <th class="text-center">Parroquias Pertenecientes</th>
                        <th class="text-center" style="width: 190px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($distritos)): ?>
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                                <div class="fs-5">No existen distritos registrados actualmente.</div>
                                <p class="small text-muted mb-3">Comience registrando un nuevo distrito en el sistema.</p>
                                <a href="<?= site_url('distrito/create') ?>" class="btn btn-sm btn-primary">
                                    <i class="bi bi-plus-lg me-1"></i> Registrar Distrito
                                </a>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($distritos as $item): ?>
                            <tr>
                                <td class="text-center fw-semibold text-secondary">#<?= esc($item['iddistrito']) ?></td>
                                <td>
                                    <a href="<?= site_url('distrito/ver/' . $item['iddistrito']) ?>" class="fw-bold text-dark text-decoration-none hover-primary fs-6">
                                        <?= esc($item['nombre']) ?>
                                    </a>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-purple-subtle text-purple border px-3 py-1 fs-7" style="background-color: #f3e8ff; color: #6b21a8; border-color: #e9d5ff;">
                                        <i class="bi bi-geo-fill me-1"></i><?= $item['total_parroquias'] ?> parroquias
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="<?= site_url('distrito/ver/' . $item['iddistrito']) ?>" class="btn btn-outline-primary" title="Ver Ficha">
                                            <i class="bi bi-eye"></i> Ver
                                        </a>
                                        <a href="<?= site_url('distrito/edit/' . $item['iddistrito']) ?>" class="btn btn-outline-warning" title="Editar">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <a href="<?= site_url('distrito/delete/' . $item['iddistrito']) ?>" 
                                           class="btn btn-outline-danger" 
                                           title="Eliminar"
                                           onclick="return confirm('¿Está seguro de eliminar el distrito \'<?= esc($item['nombre']) ?>\'?');">
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
    document.getElementById('filtroDistritos')?.addEventListener('keyup', function() {
        const valor = this.value.toLowerCase().trim();
        const filas = document.querySelectorAll('#tablaDistritos tbody tr');
        
        filas.forEach(fila => {
            const textoFila = fila.textContent.toLowerCase();
            fila.style.display = textoFila.includes(valor) ? '' : 'none';
        });
    });
</script>
<?= $this->endSection() ?>
