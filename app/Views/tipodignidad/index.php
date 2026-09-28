<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h2 class="h3 mb-0 text-gray-800"><i class="bi bi-award me-2 text-primary"></i>Catálogo de Tipos de Dignidad</h2>
        <p class="text-muted small mb-0">Gestión de cargos y dignidades de elección popular (Presidente, Asambleísta, Alcalde, etc.).</p>
    </div>
    <div>
        <a href="<?= site_url('tipodignidad/create') ?>" class="btn btn-primary shadow-sm">
            <i class="bi bi-plus-circle me-1"></i> Nuevo Tipo de Dignidad
        </a>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-white py-3 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
        <div class="fw-bold text-primary">
            <i class="bi bi-award-fill me-1"></i> Listado de Tipos de Dignidad (<?= count($tipos) ?>)
        </div>
        <div class="col-12 col-sm-4">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                <input type="text" id="filtroTipos" class="form-control" placeholder="Buscar tipo de dignidad...">
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tablaTipos">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 80px;">ID</th>
                        <th>Nombre del Cargo / Dignidad</th>
                        <th class="text-center" style="width: 180px;">Candidaturas Registradas</th>
                        <th class="text-center" style="width: 170px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($tipos)): ?>
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="bi bi-award fs-1 d-block mb-2 text-secondary"></i>
                                <div class="fs-5">No existen tipos de dignidad registrados actualmente.</div>
                                <p class="small text-muted mb-3">Comience creando un nuevo tipo de dignidad en el sistema.</p>
                                <a href="<?= site_url('tipodignidad/create') ?>" class="btn btn-sm btn-primary">
                                    <i class="bi bi-plus-lg me-1"></i> Registrar Tipo de Dignidad
                                </a>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($tipos as $t): ?>
                            <tr>
                                <td class="text-center fw-semibold text-secondary">#<?= esc($t['idtipodignidad']) ?></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="bg-primary text-white rounded-circle p-2 d-inline-flex justify-content-center align-items-center" style="width: 32px; height: 32px;">
                                            <i class="bi bi-award" style="font-size: 0.85rem;"></i>
                                        </div>
                                        <span class="fw-bold text-dark fs-6"><?= esc($t['nombre']) ?></span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <?php if (($t['total_dignidades'] ?? 0) > 0): ?>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fs-7 fw-semibold">
                                            <i class="bi bi-person-check-fill me-1"></i><?= esc($t['total_dignidades']) ?> asignada(s)
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-light text-muted border px-2 py-1 fs-7">
                                            0 asignadas
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="<?= site_url('tipodignidad/edit/' . $t['idtipodignidad']) ?>" class="btn btn-outline-warning" title="Editar">
                                            <i class="bi bi-pencil-square"></i> Editar
                                        </a>
                                        <a href="<?= site_url('tipodignidad/delete/' . $t['idtipodignidad']) ?>" 
                                           class="btn btn-outline-danger" 
                                           title="Eliminar"
                                           onclick="return confirm('¿Está seguro de eliminar el tipo de dignidad \'<?= esc($t['nombre']) ?>\'?');">
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
    document.getElementById('filtroTipos')?.addEventListener('keyup', function() {
        const valor = this.value.toLowerCase().trim();
        const filas = document.querySelectorAll('#tablaTipos tbody tr');
        
        filas.forEach(fila => {
            const textoFila = fila.textContent.toLowerCase();
            fila.style.display = textoFila.includes(valor) ? '' : 'none';
        });
    });
</script>
<?= $this->endSection() ?>
