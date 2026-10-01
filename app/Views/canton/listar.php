<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h2 class="h3 mb-0 text-gray-800"><i class="bi bi-table me-2 text-primary"></i>Listado General de Cantones</h2>
        <p class="text-muted small mb-0">Gestión de cantones y municipalidades vinculadas a cada provincia.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= site_url('canton') ?>" class="btn btn-outline-primary shadow-sm">
            <i class="bi bi-card-heading me-1"></i> Ver por Registro
        </a>
        <a href="<?= site_url('canton/create') ?>" class="btn btn-primary shadow-sm">
            <i class="bi bi-plus-circle me-1"></i> Nuevo Cantón
        </a>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-white py-3 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
        <div class="fw-bold text-primary">
            <i class="bi bi-buildings me-1"></i> Listado de Cantones (<?= count($cantones) ?>)
        </div>
        <div class="col-12 col-sm-4">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                <input type="text" id="filtroCantones" class="form-control" placeholder="Buscar por cantón o provincia...">
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tablaCantones">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 80px;">ID</th>
                        <th>Nombre del Cantón</th>
                        <th>Provincia</th>
                        <th class="text-center">Parroquias Pertenecientes</th>
                        <th class="text-center" style="width: 190px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($cantones)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-buildings fs-1 d-block mb-2 text-secondary"></i>
                                <div class="fs-5">No existen cantones registrados actualmente.</div>
                                <p class="small text-muted mb-3">Comience registrando un nuevo cantón en el sistema.</p>
                                <a href="<?= site_url('canton/create') ?>" class="btn btn-sm btn-primary">
                                    <i class="bi bi-plus-lg me-1"></i> Registrar Cantón
                                </a>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($cantones as $c): ?>
                            <tr>
                                <td class="text-center fw-semibold text-secondary">#<?= esc($c['idcanton']) ?></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="bg-primary text-white rounded-circle p-2 d-inline-flex justify-content-center align-items-center" style="width: 32px; height: 32px;">
                                            <i class="bi bi-buildings-fill" style="font-size: 0.85rem;"></i>
                                        </div>
                                        <a href="<?= site_url('canton/ver/' . $c['idcanton']) ?>" class="fw-bold text-dark text-decoration-none hover-primary fs-6">
                                            <?= esc($c['nombre']) ?>
                                        </a>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 fs-7">
                                        <i class="bi bi-map-fill me-1"></i><?= esc($c['provincia_nombre'] ?? 'Sin Provincia') ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-light text-dark border px-3 py-1 fs-7">
                                        <i class="bi bi-geo-fill me-1 text-primary"></i><?= $c['total_parroquias'] ?> parroquias
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="<?= site_url('canton/ver/' . $c['idcanton']) ?>" class="btn btn-outline-primary" title="Ver Ficha">
                                            <i class="bi bi-eye"></i> Ver
                                        </a>
                                        <a href="<?= site_url('canton/edit/' . $c['idcanton']) ?>" class="btn btn-outline-warning" title="Editar">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <a href="<?= site_url('canton/delete/' . $c['idcanton']) ?>" 
                                           class="btn btn-outline-danger" 
                                           title="Eliminar"
                                           onclick="return confirm('¿Está seguro de eliminar el cantón \'<?= esc($c['nombre']) ?>\'?');">
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
    document.getElementById('filtroCantones')?.addEventListener('keyup', function() {
        const valor = this.value.toLowerCase().trim();
        const filas = document.querySelectorAll('#tablaCantones tbody tr');
        
        filas.forEach(fila => {
            const textoFila = fila.textContent.toLowerCase();
            fila.style.display = textoFila.includes(valor) ? '' : 'none';
        });
    });
</script>
<?= $this->endSection() ?>
