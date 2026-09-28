<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h2 class="h3 mb-0 text-gray-800"><i class="bi bi-card-checklist me-2 text-primary"></i>Dignidades a Elegir por Mesa</h2>
        <p class="text-muted small mb-0">Gestión de dignidades y candidaturas a ser elegidas en cada mesa electoral, junto a la cantidad de papeletas contadas.</p>
    </div>
    <div>
        <a href="<?= site_url('mezadignidad/create') ?>" class="btn btn-primary shadow-sm">
            <i class="bi bi-plus-circle me-1"></i> Asignar Dignidad a Mesa
        </a>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-white py-3 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
        <div class="fw-bold text-primary d-flex align-items-center gap-2 flex-wrap">
            <span><i class="bi bi-ui-checks me-1"></i> Listado de Dignidades a Elegir (<?= count($asignaciones) ?>)</span>
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 small">
                <i class="bi bi-check2-all me-1"></i><?= count($asignaciones) ?> Registros
            </span>
        </div>
        <div class="col-12 col-sm-4">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                <input type="text" id="filtroMezadignidad" class="form-control" placeholder="Buscar por mesa, candidato, cargo, papeletas contadas...">
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tablaMezadignidad">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 70px;">ID</th>
                        <th class="text-center" style="width: 150px;">Papeletas Contadas</th>
                        <th>Mesa Electoral</th>
                        <th>Recinto / Territorio</th>
                        <th>Candidato Postulado</th>
                        <th>Dignidad / Cargo</th>
                        <th class="text-center" style="width: 170px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($asignaciones)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-card-checklist fs-1 d-block mb-2 text-secondary"></i>
                                <div class="fs-5">No existen dignidades a elegir registradas en mesas actualmente.</div>
                                <p class="small text-muted mb-3">Comience asociando una candidatura a una mesa electoral e indicando las papeletas contadas.</p>
                                <a href="<?= site_url('mezadignidad/create') ?>" class="btn btn-sm btn-primary">
                                    <i class="bi bi-plus-lg me-1"></i> Asignar Dignidad
                                </a>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($asignaciones as $a): ?>
                            <tr>
                                <td class="text-center fw-semibold text-secondary">#<?= esc($a['idmezadignidad']) ?></td>
                                <td class="text-center">
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fs-7 fw-bold">
                                        <i class="bi bi-file-earmark-check me-1"></i><?= number_format((int)$a['numeropapeleta']) ?> papeletas
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="bg-primary text-white rounded-circle p-2 d-inline-flex justify-content-center align-items-center fw-bold" style="width: 32px; height: 32px; font-size: 0.85rem;">
                                            <?= esc($a['meza_numero'] ?? '?') ?>
                                        </div>
                                        <div>
                                            <span class="fw-bold text-dark fs-6">Mesa <?= esc($a['meza_numero'] ?? 'N/A') ?></span>
                                            <span class="badge bg-light text-secondary border ms-1 fs-8"><?= esc($a['meza_sexo'] ?? 'N/A') ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <div class="fw-semibold text-dark"><i class="bi bi-building me-1 text-primary"></i><?= esc($a['recinto_nombre'] ?? 'Sin Recinto') ?></div>
                                        <div class="small text-muted"><?= esc($a['canton_nombre'] ?? 'N/A') ?> (<?= esc($a['provincia_nombre'] ?? 'N/A') ?>)</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-1">
                                        <i class="bi bi-person-fill text-secondary me-1"></i>
                                        <span class="fw-semibold text-dark"><?= esc($a['persona_nombre'] ?? '') ?> <?= esc($a['persona_apellidos'] ?? '') ?></span>
                                    </div>
                                    <code class="text-muted small">CI: <?= esc($a['persona_cedula'] ?? 'N/A') ?></code>
                                </td>
                                <td>
                                    <span class="badge bg-warning-subtle text-dark border border-warning-subtle px-2 py-1 fs-7 fw-semibold">
                                        <i class="bi bi-award-fill text-warning me-1"></i><?= esc($a['tipodignidad_nombre'] ?? 'Sin Cargo') ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="<?= site_url('mezadignidad/edit/' . $a['idmezadignidad']) ?>" class="btn btn-outline-warning" title="Editar">
                                            <i class="bi bi-pencil-square"></i> Editar
                                        </a>
                                        <a href="<?= site_url('mezadignidad/delete/' . $a['idmezadignidad']) ?>" 
                                           class="btn btn-outline-danger" 
                                           title="Eliminar"
                                           onclick="return confirm('¿Está seguro de eliminar esta dignidad asignada a la Mesa <?= esc($a['meza_numero']) ?>?');">
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
    document.getElementById('filtroMezadignidad')?.addEventListener('keyup', function() {
        const valor = this.value.toLowerCase().trim();
        const filas = document.querySelectorAll('#tablaMezadignidad tbody tr');
        
        filas.forEach(fila => {
            const textoFila = fila.textContent.toLowerCase();
            fila.style.display = textoFila.includes(valor) ? '' : 'none';
        });
    });
</script>
<?= $this->endSection() ?>
