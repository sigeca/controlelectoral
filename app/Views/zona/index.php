<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h2 class="h3 mb-0 text-gray-800"><i class="bi bi-pin-map-fill me-2 text-primary"></i>Catálogo de Zonas Electorales</h2>
        <p class="text-muted small mb-0">Gestión de zonas y sectores electorales asociados a cada parroquia para la delimitación del padrón.</p>
    </div>
    <div>
        <a href="<?= site_url('zona/create') ?>" class="btn btn-primary shadow-sm">
            <i class="bi bi-plus-circle me-1"></i> Nueva Zona
        </a>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-white py-3 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
        <div class="fw-bold text-primary">
            <i class="bi bi-geo-alt me-1"></i> Listado de Zonas (<?= count($zonas) ?>)
        </div>
        <div class="col-12 col-sm-4">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                <input type="text" id="filtroZonas" class="form-control" placeholder="Buscar por zona, parroquia, cantón o provincia...">
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tablaZonas">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 80px;">ID</th>
                        <th>Nombre de la Zona</th>
                        <th>Parroquia</th>
                        <th>Cantón</th>
                        <th>Provincia</th>
                        <th class="text-center" style="width: 170px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($zonas)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-pin-map fs-1 d-block mb-2 text-secondary"></i>
                                <div class="fs-5">No existen zonas electorales registradas actualmente.</div>
                                <p class="small text-muted mb-3">Comience registrando una nueva zona en el sistema.</p>
                                <a href="<?= site_url('zona/create') ?>" class="btn btn-sm btn-primary">
                                    <i class="bi bi-plus-lg me-1"></i> Registrar Zona
                                </a>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($zonas as $z): ?>
                            <tr>
                                <td class="text-center fw-semibold text-secondary">#<?= esc($z['idzona']) ?></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="bg-primary text-white rounded-circle p-2 d-inline-flex justify-content-center align-items-center" style="width: 32px; height: 32px;">
                                            <i class="bi bi-pin-map-fill" style="font-size: 0.85rem;"></i>
                                        </div>
                                        <span class="fw-bold text-dark fs-6"><?= esc($z['nombre']) ?></span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border px-2 py-1 fs-7">
                                        <i class="bi bi-geo-fill me-1 text-primary"></i><?= esc($z['parroquia_nombre'] ?? 'Sin Parroquia') ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border px-2 py-1 fs-7">
                                        <i class="bi bi-buildings me-1 text-secondary"></i><?= esc($z['canton_nombre'] ?? 'Sin Cantón') ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 fs-7">
                                        <i class="bi bi-map-fill me-1"></i><?= esc($z['provincia_nombre'] ?? 'Sin Provincia') ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="<?= site_url('zona/edit/' . $z['idzona']) ?>" class="btn btn-outline-warning" title="Editar">
                                            <i class="bi bi-pencil-square"></i> Editar
                                        </a>
                                        <a href="<?= site_url('zona/delete/' . $z['idzona']) ?>" 
                                           class="btn btn-outline-danger" 
                                           title="Eliminar"
                                           onclick="return confirm('¿Está seguro de eliminar la zona \'<?= esc($z['nombre']) ?>\'?');">
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
    document.getElementById('filtroZonas')?.addEventListener('keyup', function() {
        const valor = this.value.toLowerCase().trim();
        const filas = document.querySelectorAll('#tablaZonas tbody tr');
        
        filas.forEach(fila => {
            const textoFila = fila.textContent.toLowerCase();
            fila.style.display = textoFila.includes(valor) ? '' : 'none';
        });
    });
</script>
<?= $this->endSection() ?>
