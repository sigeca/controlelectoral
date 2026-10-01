<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Título y Descripción del Módulo -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">
    <div>
        <h2 class="h3 mb-0 text-gray-800">
            <i class="bi bi-file-earmark-text me-2 text-primary"></i>Acta Electoral (Navegador Individual)
        </h2>
        <p class="text-muted small mb-0">Visualización registro por registro de las actas de escrutinio con navegación, operaciones CRUD y totales de votación.</p>
    </div>
    <?php if ($total > 0): ?>
        <div class="d-flex align-items-center gap-2">
            <label for="jumpActaSelect" class="form-label small mb-0 text-muted d-none d-sm-inline">Ir a:</label>
            <select id="jumpActaSelect" class="form-select form-select-sm" style="min-width: 200px;" onchange="if(this.value) window.location.href=this.value;">
                <?php foreach ($allIds as $idx => $id): ?>
                    <option value="<?= site_url('acta/ver/' . $id) ?>" <?= ($idx === $currentIndex) ? 'selected' : '' ?>>
                        Registro <?= ($idx + 1) ?> de <?= $total ?> (Acta ID #<?= $id ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    <?php endif; ?>
</div>

<!-- ========================================================== -->
<!-- MENÚ SUPERIOR DE NAVEGACIÓN Y ACCIONES                     -->
<!-- ========================================================== -->
<div class="card shadow-sm border-0 mb-4 bg-white">
    <div class="card-body p-2 p-md-3">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-stretch align-items-lg-center gap-2">
            
            <!-- Grupo de Navegación -->
            <div class="btn-group shadow-sm" role="group" aria-label="Navegación de registros">
                <!-- Primero -->
                <a href="<?= ($firstId && $currentIndex > 0) ? site_url('acta/ver/' . $firstId) : '#' ?>" 
                   class="btn btn-outline-primary btn-sm d-inline-flex align-items-center justify-content-center <?= ($currentIndex === 0 || $total === 0) ? 'disabled' : '' ?>"
                   title="Primer Registro (Inicio)">
                    <i class="bi bi-chevron-double-left me-1"></i>
                    <span class="d-none d-sm-inline">Primero</span>
                </a>

                <!-- Anterior -->
                <a href="<?= ($prevId !== null) ? site_url('acta/ver/' . $prevId) : '#' ?>" 
                   class="btn btn-outline-primary btn-sm d-inline-flex align-items-center justify-content-center <?= ($prevId === null) ? 'disabled' : '' ?>"
                   title="Registro Anterior (Flecha Izquierda)" id="btnAnterior">
                    <i class="bi bi-chevron-left me-1"></i>
                    <span>Anterior</span>
                </a>

                <!-- Indicador de Posición -->
                <span class="btn btn-primary btn-sm text-white fw-bold px-3 d-inline-flex align-items-center justify-content-center" style="pointer-events: none; cursor: default;">
                    <i class="bi bi-bookmark-check me-1 d-none d-md-inline"></i>
                    <?= $total > 0 ? ($currentIndex + 1) . ' de ' . $total : '0 de 0' ?>
                </span>

                <!-- Siguiente -->
                <a href="<?= ($nextId !== null) ? site_url('acta/ver/' . $nextId) : '#' ?>" 
                   class="btn btn-outline-primary btn-sm d-inline-flex align-items-center justify-content-center <?= ($nextId === null) ? 'disabled' : '' ?>"
                   title="Registro Siguiente (Flecha Derecha)" id="btnSiguiente">
                    <span>Siguiente</span>
                    <i class="bi bi-chevron-right ms-1"></i>
                </a>

                <!-- Último -->
                <a href="<?= ($lastId && $currentIndex < $total - 1) ? site_url('acta/ver/' . $lastId) : '#' ?>" 
                   class="btn btn-outline-primary btn-sm d-inline-flex align-items-center justify-content-center <?= ($currentIndex >= $total - 1 || $total === 0) ? 'disabled' : '' ?>"
                   title="Último Registro (Fin)">
                    <span class="d-none d-sm-inline">Último</span>
                    <i class="bi bi-chevron-double-right ms-1"></i>
                </a>
            </div>

            <!-- Grupo de Acciones -->
            <div class="btn-group shadow-sm" role="group" aria-label="Acciones de registro">
                <!-- Nuevo -->
                <a href="<?= site_url('acta/create') ?>" class="btn btn-success btn-sm d-inline-flex align-items-center justify-content-center" title="Registrar Nueva Acta">
                    <i class="bi bi-plus-circle me-1"></i>
                    <span>Nuevo</span>
                </a>

                <!-- Editar -->
                <?php if ($currentActa): ?>
                    <a href="<?= site_url('acta/edit/' . $currentActa['idacta']) ?>" class="btn btn-warning btn-sm text-dark d-inline-flex align-items-center justify-content-center" title="Editar este Registro">
                        <i class="bi bi-pencil-square me-1"></i>
                        <span>Editar</span>
                    </a>
                <?php else: ?>
                    <button class="btn btn-outline-secondary btn-sm" disabled>
                        <i class="bi bi-pencil-square me-1"></i> Editar
                    </button>
                <?php endif; ?>

                <!-- Borrar -->
                <?php if ($currentActa): ?>
                    <a href="<?= site_url('acta/delete/' . $currentActa['idacta']) ?>" 
                       class="btn btn-danger btn-sm d-inline-flex align-items-center justify-content-center" 
                       title="Eliminar este Registro"
                       onclick="return confirm('¿Está seguro de eliminar el Acta Electoral ID #<?= esc($currentActa['idacta']) ?>?');">
                        <i class="bi bi-trash3 me-1"></i>
                        <span>Borrar</span>
                    </a>
                <?php else: ?>
                    <button class="btn btn-outline-secondary btn-sm" disabled>
                        <i class="bi bi-trash3 me-1"></i> Borrar
                    </button>
                <?php endif; ?>

                <!-- Listar -->
                <a href="<?= site_url('acta/listar') ?>" class="btn btn-info btn-sm text-white d-inline-flex align-items-center justify-content-center" title="Ver Listado Completo en Tabla">
                    <i class="bi bi-table me-1"></i>
                    <span>Listar</span>
                </a>
            </div>

        </div>
    </div>
</div>

<!-- ========================================================== -->
<!-- DETALLE DEL REGISTRO ACTUAL                                -->
<!-- ========================================================== -->
<?php if ($currentActa): ?>

    <div class="row g-4 mb-4">
        <!-- Tarjeta Principal con Información del Acta -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-primary text-white px-2.5 py-1 fs-6">
                            <i class="bi bi-file-earmark-text-fill me-1"></i> Acta ID #<?= esc($currentActa['idacta']) ?>
                        </span>
                        <a href="<?= site_url('meza/ver/' . $currentActa['idmeza']) ?>" class="badge bg-info-subtle text-info-emphasis border border-info-subtle text-decoration-none px-2.5 py-1 fs-6">
                            <i class="bi bi-inbox-fill me-1"></i> Mesa Electoral #<?= esc($currentActa['meza_numero'] ?? $currentActa['idmeza']) ?> (<?= esc($currentActa['meza_sexo'] ?? 'General') ?>)
                        </a>
                    </div>
                    <div>
                        <a href="<?= site_url('acta/edit/' . $currentActa['idacta']) ?>" class="btn btn-sm btn-outline-warning text-dark me-1">
                            <i class="bi bi-pencil-square me-1"></i> Modificar
                        </a>
                    </div>
                </div>

                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3 p-3 bg-light rounded-3 border mb-4">
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-sm flex-shrink-0" 
                             style="width: 64px; height: 64px; font-size: 1.75rem; background: linear-gradient(135deg, #0d6efd, #0b5ed7);">
                            <i class="bi bi-file-earmark-check"></i>
                        </div>
                        <div>
                            <h4 class="mb-1 text-dark fw-bold">Acta de Escrutinio Nº <?= esc($currentActa['idacta']) ?></h4>
                            <p class="text-muted small mb-0">
                                <i class="bi bi-geo-alt me-1"></i> Recinto: <strong><?= esc($currentActa['recinto_nombre'] ?? 'Sin asignar') ?></strong>
                                &bull; <?= esc($currentActa['parroquia_nombre'] ?? '') ?>, <?= esc($currentActa['canton_nombre'] ?? '') ?>, <?= esc($currentActa['provincia_nombre'] ?? '') ?>
                            </p>
                        </div>
                    </div>

                    <!-- Métricas de Votación del Acta -->
                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-bar-chart-fill me-2 text-primary"></i>Resumen de Votación y Papeletas</h5>
                    
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="p-3 bg-primary-subtle text-primary border border-primary-subtle rounded-3 text-center">
                                <span class="d-block text-uppercase fw-semibold small text-primary-emphasis mb-1">Total Papeletas</span>
                                <span class="fs-2 fw-bolder"><?= number_format($currentActa['totalpapeleta'], 0, ',', '.') ?></span>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="p-3 bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-3 text-center">
                                <span class="d-block text-uppercase fw-semibold small text-warning-emphasis mb-1">Votos en Blanco</span>
                                <span class="fs-2 fw-bolder"><?= number_format($currentActa['totalblancos'], 0, ',', '.') ?></span>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="p-3 bg-danger-subtle text-danger border border-danger-subtle rounded-3 text-center">
                                <span class="d-block text-uppercase fw-semibold small text-danger-emphasis mb-1">Votos Nulos</span>
                                <span class="fs-2 fw-bolder"><?= number_format($currentActa['totalnulos'], 0, ',', '.') ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Votos Diferenciales o Calculados -->
                    <?php 
                        $validos = $currentActa['totalpapeleta'] - ($currentActa['totalblancos'] + $currentActa['totalnulos']);
                    ?>
                    <div class="mt-4 p-3 bg-success-subtle border border-success-subtle rounded-3 d-flex justify-content-between align-items-center">
                        <div>
                            <span class="fw-bold text-success-emphasis d-block"><i class="bi bi-check-circle-fill me-1"></i> Votos Válidos Calculados</span>
                            <span class="text-muted small">Papeletas Totales menos Blancos y Nulos</span>
                        </div>
                        <span class="fs-3 fw-bold text-success me-2"><?= number_format(max(0, $validos), 0, ',', '.') ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tarjeta Secundaria: Información de Ubicación y Mesa -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="fw-bold text-dark mb-0"><i class="bi bi-info-circle me-2 text-primary"></i>Mesa y Ubicación</h5>
                </div>
                <div class="card-body p-4">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item px-0 py-2.5 d-flex justify-content-between align-items-center">
                            <span class="text-muted"><i class="bi bi-inbox me-2"></i>Mesa Electoral</span>
                            <a href="<?= site_url('meza/ver/' . $currentActa['idmeza']) ?>" class="fw-bold text-primary text-decoration-none">
                                Mesa #<?= esc($currentActa['meza_numero'] ?? $currentActa['idmeza']) ?>
                            </a>
                        </li>
                        <li class="list-group-item px-0 py-2.5 d-flex justify-content-between align-items-center">
                            <span class="text-muted"><i class="bi bi-gender-ambiguous me-2"></i>Sexo Mesa</span>
                            <span class="badge bg-secondary"><?= esc($currentActa['meza_sexo'] ?? 'General') ?></span>
                        </li>
                        <li class="list-group-item px-0 py-2.5 d-flex justify-content-between align-items-center">
                            <span class="text-muted"><i class="bi bi-building me-2"></i>Recinto</span>
                            <span class="fw-semibold text-dark text-end"><?= esc($currentActa['recinto_nombre'] ?? 'No especificado') ?></span>
                        </li>
                        <li class="list-group-item px-0 py-2.5 d-flex justify-content-between align-items-center">
                            <span class="text-muted"><i class="bi bi-geo me-2"></i>Parroquia</span>
                            <span class="fw-semibold text-dark"><?= esc($currentActa['parroquia_nombre'] ?? 'Sin Parroquia') ?></span>
                        </li>
                        <li class="list-group-item px-0 py-2.5 d-flex justify-content-between align-items-center">
                            <span class="text-muted"><i class="bi bi-buildings me-2"></i>Cantón</span>
                            <span class="fw-semibold text-dark"><?= esc($currentActa['canton_nombre'] ?? 'Sin Cantón') ?></span>
                        </li>
                        <li class="list-group-item px-0 py-2.5 d-flex justify-content-between align-items-center border-0">
                            <span class="text-muted"><i class="bi bi-map me-2"></i>Provincia</span>
                            <span class="fw-semibold text-dark"><?= esc($currentActa['provincia_nombre'] ?? 'Sin Provincia') ?></span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

<?php else: ?>
    <!-- Estado Vacío cuando no existen registros -->
    <div class="card shadow-sm border-0 py-5 text-center">
        <div class="card-body">
            <i class="bi bi-file-earmark-text fs-1 d-block mb-3 text-secondary"></i>
            <h4 class="text-dark fw-bold">No existen actas registradas</h4>
            <p class="text-muted mb-4">Actualmente la base de datos no contiene actas electorales.</p>
            <a href="<?= site_url('acta/create') ?>" class="btn btn-primary px-4 py-2">
                <i class="bi bi-plus-circle me-1"></i> Registrar la Primera Acta
            </a>
        </div>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Atajos de teclado: Flecha Izquierda (Anterior) y Flecha Derecha (Siguiente)
    document.addEventListener('keydown', function(e) {
        if (['INPUT', 'SELECT', 'TEXTAREA'].includes(document.activeElement.tagName)) {
            return;
        }

        if (e.key === 'ArrowLeft') {
            const btnAnt = document.getElementById('btnAnterior');
            if (btnAnt && !btnAnt.classList.contains('disabled') && btnAnt.getAttribute('href') !== '#') {
                window.location.href = btnAnt.getAttribute('href');
            }
        } else if (e.key === 'ArrowRight') {
            const btnSig = document.getElementById('btnSiguiente');
            if (btnSig && !btnSig.classList.contains('disabled') && btnSig.getAttribute('href') !== '#') {
                window.location.href = btnSig.getAttribute('href');
            }
        }
    });
</script>
<?= $this->endSection() ?>
