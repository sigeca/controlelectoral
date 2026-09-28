<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h2 class="h3 mb-0 text-gray-800"><i class="bi bi-table me-2 text-primary"></i>Listado General de Mesas Electorales</h2>
        <p class="text-muted small mb-0">Vista tabular completa de todas las juntas receptoras del voto registradas en el sistema.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= site_url('meza') ?>" class="btn btn-outline-primary shadow-sm" title="Ir al Navegador Registro por Registro">
            <i class="bi bi-card-heading me-1"></i> Modo Navegador
        </a>
        <a href="<?= site_url('meza/create') ?>" class="btn btn-primary shadow-sm">
            <i class="bi bi-plus-circle me-1"></i> Nueva Mesa
        </a>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-white py-3 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
        <div class="fw-bold text-primary d-flex align-items-center gap-2 flex-wrap">
            <span><i class="bi bi-inbox me-1"></i> Mesas Registradas (<?= count($mezas) ?>)</span>
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 small">
                <i class="bi bi-check2-square me-1"></i><?= count($mezas) ?> Juntas en Total
            </span>
        </div>
        <div class="col-12 col-sm-4">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                <input type="text" id="filtroMezas" class="form-control" placeholder="Buscar por número, género, recinto...">
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tablaMezas">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 80px;">ID</th>
                        <th style="width: 140px;">N° de Mesa</th>
                        <th style="width: 130px;">Género</th>
                        <th>Recinto Electoral</th>
                        <th>Zona / Parroquia</th>
                        <th>Cantón / Provincia</th>
                        <th>Dignidades a Elegir</th>
                        <th class="text-center" style="width: 190px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($mezas)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                                <div class="fs-5">No existen mesas electorales registradas actualmente.</div>
                                <p class="small text-muted mb-3">Comience registrando una nueva mesa electoral en el sistema.</p>
                                <a href="<?= site_url('meza/create') ?>" class="btn btn-sm btn-primary">
                                    <i class="bi bi-plus-lg me-1"></i> Registrar Mesa
                                </a>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($mezas as $m): ?>
                            <tr>
                                <td class="text-center fw-semibold text-secondary">#<?= esc($m['idmeza']) ?></td>
                                <td>
                                    <?php 
                                        $rutaActaM = ROOTPATH . 'repositorio/actaescrutinio/' . $m['idmeza'] . '.jpg';
                                        $tieneActaM = file_exists($rutaActaM);
                                    ?>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="bg-primary text-white rounded-circle p-2 d-inline-flex justify-content-center align-items-center fw-bold" style="width: 34px; height: 34px; font-size: 0.85rem;">
                                            <?= esc($m['numero']) ?>
                                        </div>
                                        <div>
                                            <span class="fw-bold text-dark fs-6 d-block">Mesa <?= esc($m['numero']) ?></span>
                                            <div>
                                                <?php if ($tieneActaM): ?>
                                                    <a href="<?= site_url('meza/acta/' . $m['idmeza']) ?>" 
                                                       target="_blank" 
                                                       class="badge bg-success-subtle text-success border border-success-subtle px-1 py-0.5 text-decoration-none" 
                                                       title="Ver Acta Oficial de Escrutinio" 
                                                       style="font-size: 0.72rem;">
                                                        <i class="bi bi-file-earmark-check-fill me-1"></i>Con Acta
                                                    </a>
                                                <?php else: ?>
                                                    <span class="badge bg-light text-muted border px-1 py-0.5" title="Sin acta digitalizada" style="font-size: 0.72rem;">
                                                        <i class="bi bi-file-earmark-x me-1"></i>Sin Acta
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <?php 
                                        $sexoLower = strtolower($m['sexo_nombre'] ?? '');
                                        $badgeClass = 'bg-secondary-subtle text-secondary border';
                                        $icono = 'bi-gender-ambiguous';
                                        if (str_contains($sexoLower, 'masc')) {
                                            $badgeClass = 'bg-info-subtle text-info-emphasis border border-info-subtle';
                                            $icono = 'bi-gender-male';
                                        } elseif (str_contains($sexoLower, 'fem')) {
                                            $badgeClass = 'bg-danger-subtle text-danger-emphasis border border-danger-subtle';
                                            $icono = 'bi-gender-female';
                                        }
                                    ?>
                                    <span class="badge <?= $badgeClass ?> px-2 py-1 fs-7">
                                        <i class="bi <?= $icono ?> me-1"></i><?= esc($m['sexo_nombre'] ?? 'N/A') ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-1">
                                        <i class="bi bi-building text-primary me-1"></i>
                                        <span class="fw-semibold text-dark"><?= esc($m['recinto_nombre'] ?? 'Sin Recinto') ?></span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border px-2 py-1 fs-7 d-inline-block text-truncate" style="max-width: 200px;">
                                        <i class="bi bi-pin-map-fill me-1 text-secondary"></i><?= esc($m['zona_nombre'] ?? 'N/A') ?> &bull; <?= esc($m['parroquia_nombre'] ?? 'N/A') ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 fs-7">
                                        <i class="bi bi-geo-alt-fill me-1"></i><?= esc($m['canton_nombre'] ?? 'N/A') ?> (<?= esc($m['provincia_nombre'] ?? 'N/A') ?>)
                                    </span>
                                </td>
                                <td>
                                    <?php $dignidadesMesa = $dignidadesPorMeza[$m['idmeza']] ?? []; ?>
                                    <?php if (empty($dignidadesMesa)): ?>
                                        <span class="text-muted small fst-italic">Sin asignar</span>
                                    <?php else: ?>
                                        <div class="d-flex align-items-center gap-1 flex-wrap">
                                            <?php foreach ($dignidadesMesa as $dm): ?>
                                                <?php 
                                                    $ced = $dm['persona_cedula'] ?? '';
                                                    $rf = ROOTPATH . 'repositorio/fotos/' . $ced . '.jpg';
                                                    $tf = !empty($ced) && file_exists($rf);
                                                    $infoDignidad = esc($dm['persona_nombre'] . ' ' . $dm['persona_apellidos'] . ' (' . $dm['tipodignidad_nombre'] . ') - ' . $dm['numeropapeleta'] . ' papeletas');
                                                ?>
                                                <a href="<?= site_url('dignidad?id=' . $dm['iddignidad']) ?>" 
                                                   class="d-inline-block text-decoration-none" 
                                                   title="<?= $infoDignidad ?>">
                                                    <?php if ($tf): ?>
                                                        <img src="<?= site_url('persona/foto/' . $dm['idpersona']) ?>?v=<?= filemtime($rf) ?>" 
                                                             alt="<?= esc($dm['persona_nombre']) ?>" 
                                                             class="rounded-circle border border-2 border-white shadow-sm" 
                                                             style="width: 32px; height: 32px; object-fit: cover;">
                                                    <?php else: ?>
                                                        <span class="bg-primary text-white rounded-circle d-inline-flex justify-content-center align-items-center fw-bold shadow-sm" 
                                                              style="width: 32px; height: 32px; font-size: 0.72rem;">
                                                            <?= esc(mb_substr($dm['persona_nombre'] ?? '', 0, 1) . mb_substr($dm['persona_apellidos'] ?? '', 0, 1)) ?>
                                                        </span>
                                                    <?php endif; ?>
                                                </a>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="<?= site_url('meza/ver/' . $m['idmeza']) ?>" class="btn btn-outline-primary" title="Ver en Navegador individual">
                                            <i class="bi bi-eye"></i> Ficha
                                        </a>
                                        <a href="<?= site_url('meza/edit/' . $m['idmeza']) ?>" class="btn btn-outline-warning" title="Editar">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <a href="<?= site_url('meza/delete/' . $m['idmeza']) ?>" 
                                           class="btn btn-outline-danger" 
                                           title="Eliminar"
                                           onclick="return confirm('¿Está seguro de eliminar la Mesa #<?= esc($m['numero']) ?> (<?= esc($m['sexo_nombre']) ?>) de <?= esc($m['recinto_nombre']) ?>?');">
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
    document.getElementById('filtroMezas')?.addEventListener('keyup', function() {
        const valor = this.value.toLowerCase().trim();
        const filas = document.querySelectorAll('#tablaMezas tbody tr');
        
        filas.forEach(fila => {
            const textoFila = fila.textContent.toLowerCase();
            fila.style.display = textoFila.includes(valor) ? '' : 'none';
        });
    });
</script>
<?= $this->endSection() ?>
