<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h2 class="h3 mb-0 text-gray-800"><i class="bi bi-buildings-fill me-2 text-primary"></i>Catálogo de Cantones</h2>
        <p class="text-muted small mb-0">Gestión de cantones y municipios asignados a cada provincia del país.</p>
    </div>
    <div>
        <a href="<?= site_url('canton/create') ?>" class="btn btn-primary shadow-sm">
            <i class="bi bi-plus-circle me-1"></i> Nuevo Cantón
        </a>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-white py-3 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
        <div class="fw-bold text-primary">
            <i class="bi bi-pin-map me-1"></i> Listado de Cantones (<?= count($cantones) ?>)
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
                        <th>Provincia Perteneciente</th>
                        <th class="text-center" style="width: 170px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($cantones)): ?>
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="bi bi-buildings fs-1 d-block mb-2 text-secondary"></i>
                                <div class="fs-5">No existen cantones registrados actualmente.</div>
                                <p class="small text-muted mb-3">Comience registrando un nuevo cantón en el sistema.</p>
                                <a href="<?= site_url('canton/create') ?>" class="btn btn-sm btn-primary">
                                    <i class="bi bi-plus-lg me-1"></i> Registrar Cantón
                                </a>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($cantones as $canton): ?>
                            <tr>
                                <td class="text-center fw-semibold text-secondary">#<?= esc($canton['idcanton']) ?></td>
                                <td>
                                    <span class="fw-bold text-dark fs-6"><?= esc($canton['nombre']) ?></span>
                                </td>
                                <td>
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 fs-7">
                                        <i class="bi bi-geo-alt-fill me-1"></i><?= esc($canton['provincia_nombre'] ?? 'Sin Provincia') ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="<?= site_url('canton/edit/' . $canton['idcanton']) ?>" class="btn btn-outline-warning" title="Editar">
                                            <i class="bi bi-pencil-square"></i> Editar
                                        </a>
                                        <a href="<?= site_url('canton/delete/' . $canton['idcanton']) ?>" 
                                           class="btn btn-outline-danger" 
                                           title="Eliminar"
                                           onclick="return confirm('¿Está seguro de eliminar el cantón \'<?= esc($canton['nombre']) ?>\'?');">
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
    // Filtro instantáneo de búsqueda de cantones
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
