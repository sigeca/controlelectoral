<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Título y Descripción del Módulo -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">
    <div>
        <h2 class="h3 mb-0 text-gray-800">
            <i class="bi bi-box-seam me-2 text-primary"></i>Votación por Dignidad en Acta (Navegador Individual)
        </h2>
        <p class="text-muted small mb-0">Visualización registro por registro del conteo de votos de candidaturas por acta electoral.</p>
    </div>
    <?php if ($total > 0): ?>
        <div class="d-flex align-items-center gap-2">
            <label for="jumpDignidadactaSelect" class="form-label small mb-0 text-muted d-none d-sm-inline">Ir a:</label>
            <select id="jumpDignidadactaSelect" class="form-select form-select-sm" style="min-width: 220px;" onchange="if(this.value) window.location.href=this.value;">
                <?php foreach ($allIds as $idx => $id): ?>
                    <option value="<?= site_url('dignidadacta/ver/' . $id) ?>" <?= ($idx === $currentIndex) ? 'selected' : '' ?>>
                        Registro <?= ($idx + 1) ?> de <?= $total ?> (Votación ID #<?= $id ?>)
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
                <a href="<?= ($firstId && $currentIndex > 0) ? site_url('dignidadacta/ver/' . $firstId) : '#' ?>" 
                   class="btn btn-outline-primary btn-sm d-inline-flex align-items-center justify-content-center <?= ($currentIndex === 0 || $total === 0) ? 'disabled' : '' ?>"
                   title="Primer Registro (Inicio)">
                    <i class="bi bi-chevron-double-left me-1"></i>
                    <span class="d-none d-sm-inline">Primero</span>
                </a>

                <!-- Anterior -->
                <a href="<?= ($prevId !== null) ? site_url('dignidadacta/ver/' . $prevId) : '#' ?>" 
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
                <a href="<?= ($nextId !== null) ? site_url('dignidadacta/ver/' . $nextId) : '#' ?>" 
                   class="btn btn-outline-primary btn-sm d-inline-flex align-items-center justify-content-center <?= ($nextId === null) ? 'disabled' : '' ?>"
                   title="Registro Siguiente (Flecha Derecha)" id="btnSiguiente">
                    <span>Siguiente</span>
                    <i class="bi bi-chevron-right ms-1"></i>
                </a>

                <!-- Último -->
                <a href="<?= ($lastId && $currentIndex < $total - 1) ? site_url('dignidadacta/ver/' . $lastId) : '#' ?>" 
                   class="btn btn-outline-primary btn-sm d-inline-flex align-items-center justify-content-center <?= ($currentIndex >= $total - 1 || $total === 0) ? 'disabled' : '' ?>"
                   title="Último Registro (Fin)">
                    <span class="d-none d-sm-inline">Último</span>
                    <i class="bi bi-chevron-double-right ms-1"></i>
                </a>
            </div>

            <!-- Grupo de Acciones -->
            <div class="btn-group shadow-sm" role="group" aria-label="Acciones de registro">
                <!-- Nuevo -->
                <a href="<?= site_url('dignidadacta/create') ?>" class="btn btn-success btn-sm d-inline-flex align-items-center justify-content-center" title="Registrar Nueva Votación">
                    <i class="bi bi-plus-circle me-1"></i>
                    <span>Nuevo</span>
                </a>

                <!-- Editar -->
                <?php if ($currentDignidadacta): ?>
                    <a href="<?= site_url('dignidadacta/edit/' . $currentDignidadacta['iddignidadacta']) ?>" class="btn btn-warning btn-sm text-dark d-inline-flex align-items-center justify-content-center" title="Editar este Registro">
                        <i class="bi bi-pencil-square me-1"></i>
                        <span>Editar</span>
                    </a>
                <?php else: ?>
                    <button class="btn btn-outline-secondary btn-sm" disabled>
                        <i class="bi bi-pencil-square me-1"></i> Editar
                    </button>
                <?php endif; ?>

                <!-- Borrar -->
                <?php if ($currentDignidadacta): ?>
                    <a href="<?= site_url('dignidadacta/delete/' . $currentDignidadacta['iddignidadacta']) ?>" 
                       class="btn btn-danger btn-sm d-inline-flex align-items-center justify-content-center" 
                       title="Eliminar este Registro"
                       onclick="return confirm('¿Está seguro de eliminar este registro de votación (ID: <?= esc($currentDignidadacta['iddignidadacta']) ?>)?');">
                        <i class="bi bi-trash3 me-1"></i>
                        <span>Borrar</span>
                    </a>
                <?php else: ?>
                    <button class="btn btn-outline-secondary btn-sm" disabled>
                        <i class="bi bi-trash3 me-1"></i> Borrar
                    </button>
                <?php endif; ?>

                <!-- Listar -->
                <a href="<?= site_url('dignidadacta/listar') ?>" class="btn btn-info btn-sm text-white d-inline-flex align-items-center justify-content-center" title="Ver Listado Completo en Tabla">
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
<?php if ($currentDignidadacta): ?>

    <div class="row g-4 mb-4">
        <!-- Tarjeta Principal: Votación de la Dignidad -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-primary text-white px-2.5 py-1 fs-6">
                            <i class="bi bi-box-seam me-1"></i> Votación ID #<?= esc($currentDignidadacta['iddignidadacta']) ?>
                        </span>
                        <a href="<?= site_url('acta/ver/' . $currentDignidadacta['idacta']) ?>" class="badge bg-info-subtle text-info-emphasis border border-info-subtle text-decoration-none px-2.5 py-1 fs-6">
                            <i class="bi bi-file-earmark-text me-1"></i> Acta ID #<?= esc($currentDignidadacta['idacta']) ?>
                        </a>
                    </div>
                    <div>
                        <a href="<?= site_url('dignidadacta/edit/' . $currentDignidadacta['iddignidadacta']) ?>" class="btn btn-sm btn-outline-warning text-dark me-1">
                            <i class="bi bi-pencil-square me-1"></i> Modificar
                        </a>
                    </div>
                </div>

                <div class="card-body p-4">
                    <?php
                        $cedula = $currentDignidadacta['candidato_cedula'] ?? '';
                        $idPersona = $currentDignidadacta['candidato_idpersona'] ?? 0;
                        $rutaFotoFisica = ROOTPATH . 'repositorio/fotos/' . $cedula . '.jpg';
                        $tieneFoto = !empty($cedula) && file_exists($rutaFotoFisica);
                        $urlFoto = $tieneFoto ? site_url('persona/foto/' . $idPersona) . '?v=' . filemtime($rutaFotoFisica) : null;
                    ?>
                    <!-- Candidato & Dignidad Info Header -->
                    <div class="d-flex align-items-center gap-3 p-3 bg-light rounded-3 border mb-4">
                        <?php if ($tieneFoto): ?>
                            <img src="<?= $urlFoto ?>" 
                                 alt="Foto de <?= esc($currentDignidadacta['candidato_nombre']) ?>" 
                                 class="rounded-circle shadow-sm border border-3 border-white flex-shrink-0" 
                                 style="width: 64px; height: 64px; object-fit: cover;">
                        <?php else: ?>
                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-sm flex-shrink-0" 
                                 style="width: 64px; height: 64px; font-size: 1.75rem; background: linear-gradient(135deg, #0d6efd, #0b5ed7);">
                                <i class="bi bi-person-badge"></i>
                            </div>
                        <?php endif; ?>
                        <div>
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle mb-1">
                                <i class="bi bi-award-fill me-1"></i><?= esc($currentDignidadacta['tipodignidad_nombre'] ?? 'Dignidad Electoral') ?>
                            </span>
                            <h4 class="mb-1 text-dark fw-bold">
                                <?= esc(($currentDignidadacta['candidato_apellidos'] ?? '') . ' ' . ($currentDignidadacta['candidato_nombre'] ?? 'Sin Candidato')) ?>
                            </h4>
                            <p class="text-muted small mb-0">
                                <i class="bi bi-card-text me-1"></i> Cédula: <?= esc($currentDignidadacta['candidato_cedula'] ?? 'N/A') ?>
                            </p>
                        </div>
                    </div>

                    <!-- Métrica Principal de Votación -->
                    <div class="p-4 bg-success-subtle text-success-emphasis border border-success-subtle rounded-3 text-center mb-4 shadow-sm">
                        <span class="d-block text-uppercase fw-bold text-success mb-1" style="letter-spacing: 1px;">Votos Obtenidos en esta Acta</span>
                        <span class="display-3 fw-bolder text-success d-block"><?= number_format($currentDignidadacta['votacion'], 0, ',', '.') ?></span>
                        <span class="text-muted small">Votos contabilizados para el candidato en la mesa asignada.</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tarjeta Secundaria: Contexto del Acta y Mesa -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="fw-bold text-dark mb-0"><i class="bi bi-file-earmark-check me-2 text-primary"></i>Datos del Acta & Mesa</h5>
                </div>
                <div class="card-body p-4">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item px-0 py-2.5 d-flex justify-content-between align-items-center">
                            <span class="text-muted"><i class="bi bi-file-earmark-text me-2"></i>Acta Electoral</span>
                            <a href="<?= site_url('acta/ver/' . $currentDignidadacta['idacta']) ?>" class="fw-bold text-primary text-decoration-none">
                                Acta #<?= esc($currentDignidadacta['idacta']) ?>
                            </a>
                        </li>
                        <li class="list-group-item px-0 py-2.5 d-flex justify-content-between align-items-center">
                            <span class="text-muted"><i class="bi bi-inbox me-2"></i>Mesa Electoral</span>
                            <a href="<?= site_url('meza/ver/' . $currentDignidadacta['idmeza']) ?>" class="fw-bold text-dark text-decoration-none">
                                Mesa #<?= esc($currentDignidadacta['meza_numero'] ?? 'N/A') ?>
                            </a>
                        </li>
                        <li class="list-group-item px-0 py-2.5 d-flex justify-content-between align-items-center">
                            <span class="text-muted"><i class="bi bi-building me-2"></i>Recinto</span>
                            <span class="fw-semibold text-dark text-end"><?= esc($currentDignidadacta['recinto_nombre'] ?? 'N/A') ?></span>
                        </li>
                        <li class="list-group-item px-0 py-2.5 d-flex justify-content-between align-items-center">
                            <span class="text-muted"><i class="bi bi-files me-2"></i>Total Papeletas</span>
                            <span class="badge bg-primary px-2.5 py-1"><?= number_format($currentDignidadacta['totalpapeleta'] ?? 0, 0, ',', '.') ?></span>
                        </li>
                        <li class="list-group-item px-0 py-2.5 d-flex justify-content-between align-items-center">
                            <span class="text-muted"><i class="bi bi-square me-2"></i>Votos Blancos</span>
                            <span class="badge bg-warning text-dark px-2 py-1"><?= number_format($currentDignidadacta['totalblancos'] ?? 0, 0, ',', '.') ?></span>
                        </li>
                        <li class="list-group-item px-0 py-2.5 d-flex justify-content-between align-items-center border-0">
                            <span class="text-muted"><i class="bi bi-x-square me-2"></i>Votos Nulos</span>
                            <span class="badge bg-danger px-2 py-1"><?= number_format($currentDignidadacta['totalnulos'] ?? 0, 0, ',', '.') ?></span>
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
            <i class="bi bi-box-seam fs-1 d-block mb-3 text-secondary"></i>
            <h4 class="text-dark fw-bold">No existen registros de votación</h4>
            <p class="text-muted mb-4">Actualmente la base de datos no contiene registros en la tabla <code>dignidadacta</code>.</p>
            <a href="<?= site_url('dignidadacta/create') ?>" class="btn btn-primary px-4 py-2">
                <i class="bi bi-plus-circle me-1"></i> Registrar Primera Votación
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
