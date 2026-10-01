<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h2 class="h3 mb-0 text-gray-800"><i class="bi bi-table me-2 text-primary"></i>Listado General de Provincias</h2>
        <p class="text-muted small mb-0">Vista tabular de las provincias y demarcaciones territoriales registradas.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= site_url('provincia') ?>" class="btn btn-outline-primary shadow-sm">
            <i class="bi bi-card-heading me-1"></i> Ver por Registro
        </a>
        <a href="<?= site_url('provincia/create') ?>" class="btn btn-primary shadow-sm">
            <i class="bi bi-plus-circle me-1"></i> Nueva Provincia
        </a>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-white py-3 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
        <div class="fw-bold text-primary">
            <i class="bi bi-map me-1"></i> Listado de Provincias (<?= count($provincias) ?>)
        </div>
        <div class="col-12 col-sm-4">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                <input type="text" id="filtroProvincias" class="form-control" placeholder="Buscar provincia...">
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tablaProvincias">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 90px;">ID</th>
                        <th>Nombre de la Provincia</th>
                        <th class="text-center" style="width: 170px;">Cantones Asociados</th>
                        <th class="text-center" style="width: 190px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($provincias)): ?>
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="bi bi-geo fs-1 d-block mb-2 text-secondary"></i>
                                <div class="fs-5">No existen provincias registradas actualmente.</div>
                                <p class="small text-muted mb-3">Comience registrando una nueva provincia en el sistema.</p>
                                <a href="<?= site_url('provincia/create') ?>" class="btn btn-sm btn-primary">
                                    <i class="bi bi-plus-lg me-1"></i> Registrar Provincia
                                </a>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($provincias as $prov): ?>
                            <tr>
                                <td class="text-center fw-semibold text-secondary">#<?= esc($prov['idprovincia']) ?></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="bg-primary-subtle text-primary rounded-circle p-2 d-inline-flex">
                                            <i class="bi bi-geo-alt"></i>
                                        </div>
                                        <a href="<?= site_url('provincia/ver/' . $prov['idprovincia']) ?>" class="fw-bold text-dark text-decoration-none hover-primary fs-6">
                                            <?= esc($prov['nombre']) ?>
                                        </a>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge rounded-pill <?= $prov['total_cantones'] > 0 ? 'bg-primary-subtle text-primary border border-primary-subtle' : 'bg-light text-muted border' ?>">
                                        <i class="bi bi-buildings me-1"></i><?= $prov['total_cantones'] ?> cantón(es)
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="<?= site_url('provincia/ver/' . $prov['idprovincia']) ?>" class="btn btn-outline-primary" title="Ver Ficha">
                                            <i class="bi bi-eye"></i> Ver
                                        </a>
                                        <a href="<?= site_url('provincia/edit/' . $prov['idprovincia']) ?>" class="btn btn-outline-warning" title="Editar">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <a href="<?= site_url('provincia/delete/' . $prov['idprovincia']) ?>" 
                                           class="btn btn-outline-danger" 
                                           title="Eliminar"
                                           onclick="return confirm('¿Está seguro de eliminar la provincia \'<?= esc($prov['nombre']) ?>\'?');">
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
    document.getElementById('filtroProvincias')?.addEventListener('keyup', function() {
        const valor = this.value.toLowerCase().trim();
        const filas = document.querySelectorAll('#tablaProvincias tbody tr');
        
        filas.forEach(fila => {
            const textoFila = fila.textContent.toLowerCase();
            fila.style.display = textoFila.includes(valor) ? '' : 'none';
        });
    });
</script>
<?= $this->endSection() ?>
