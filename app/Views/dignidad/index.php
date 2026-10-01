<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Título y Selector Rápido del Módulo -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">
    <div>
        <h2 class="h3 mb-0 text-gray-800">
            <i class="bi bi-person-badge-fill me-2 text-primary"></i>Dignidades y Candidaturas (Navegador Individual)
        </h2>
        <p class="text-muted small mb-0">Visualización de postulaciones electorales registro por registro con barra de navegación.</p>
    </div>
    <?php if ($total > 0): ?>
        <div class="d-flex align-items-center gap-2">
            <label for="jumpDignidadSelect" class="form-label small mb-0 text-muted d-none d-sm-inline">Ir a:</label>
            <select id="jumpDignidadSelect" class="form-select form-select-sm" style="min-width: 220px;" onchange="if(this.value) window.location.href=this.value;">
                <?php foreach ($allIds as $idx => $id): ?>
                    <option value="<?= site_url('dignidad/ver/' . $id) ?>" <?= ($idx === $currentIndex) ? 'selected' : '' ?>>
                        Registro <?= ($idx + 1) ?> de <?= $total ?> (Candidatura ID #<?= $id ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    <?php endif; ?>
</div>

<!-- ========================================================== -->
<!-- MENÚ SUPERIOR DE NAVEGACIÓN Y ACCIONES                     -->
<!-- (Primer, Anterior, Siguiente, Último | Nuevo, Editar, Borrar, Listar) -->
<!-- ========================================================== -->
<div class="card shadow-sm border-0 mb-4 bg-white">
    <div class="card-body p-2 p-md-3">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-stretch align-items-lg-center gap-2">
            
            <!-- Grupo de Navegación: Primer, Anterior, Indicador, Siguiente, Último -->
            <div class="btn-group shadow-sm" role="group" aria-label="Navegación de candidaturas">
                <!-- Primer -->
                <a href="<?= ($firstId && $currentIndex > 0) ? site_url('dignidad/ver/' . $firstId) : '#' ?>" 
                   class="btn btn-outline-primary btn-sm d-inline-flex align-items-center justify-content-center <?= ($currentIndex === 0 || $total === 0) ? 'disabled' : '' ?>"
                   title="Primer Registro (Inicio)">
                    <i class="bi bi-chevron-double-left me-1"></i>
                    <span class="d-none d-sm-inline">Primer</span>
                </a>

                <!-- Anterior -->
                <a href="<?= ($prevId !== null) ? site_url('dignidad/ver/' . $prevId) : '#' ?>" 
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
                <a href="<?= ($nextId !== null) ? site_url('dignidad/ver/' . $nextId) : '#' ?>" 
                   class="btn btn-outline-primary btn-sm d-inline-flex align-items-center justify-content-center <?= ($nextId === null) ? 'disabled' : '' ?>"
                   title="Registro Siguiente (Flecha Derecha)" id="btnSiguiente">
                    <span>Siguiente</span>
                    <i class="bi bi-chevron-right ms-1"></i>
                </a>

                <!-- Último -->
                <a href="<?= ($lastId && $currentIndex < $total - 1) ? site_url('dignidad/ver/' . $lastId) : '#' ?>" 
                   class="btn btn-outline-primary btn-sm d-inline-flex align-items-center justify-content-center <?= ($currentIndex >= $total - 1 || $total === 0) ? 'disabled' : '' ?>"
                   title="Último Registro (Fin)">
                    <span class="d-none d-sm-inline">Último</span>
                    <i class="bi bi-chevron-double-right ms-1"></i>
                </a>
            </div>

            <!-- Grupo de Acciones: Nuevo, Editar, Borrar, Listar -->
            <div class="btn-group shadow-sm" role="group" aria-label="Acciones de candidatura">
                <!-- Nuevo -->
                <a href="<?= site_url('dignidad/create') ?>" class="btn btn-success btn-sm d-inline-flex align-items-center justify-content-center" title="Registrar Nueva Candidatura">
                    <i class="bi bi-plus-circle me-1"></i>
                    <span>Nuevo</span>
                </a>

                <!-- Editar -->
                <?php if ($currentDignidad): ?>
                    <a href="<?= site_url('dignidad/edit/' . $currentDignidad['iddignidad']) ?>" class="btn btn-warning btn-sm text-dark d-inline-flex align-items-center justify-content-center" title="Editar este Registro">
                        <i class="bi bi-pencil-square me-1"></i>
                        <span>Editar</span>
                    </a>
                <?php else: ?>
                    <button class="btn btn-outline-secondary btn-sm" disabled>
                        <i class="bi bi-pencil-square me-1"></i> Editar
                    </button>
                <?php endif; ?>

                <!-- Borrar -->
                <?php if ($currentDignidad): ?>
                    <a href="<?= site_url('dignidad/delete/' . $currentDignidad['iddignidad']) ?>" 
                       class="btn btn-danger btn-sm d-inline-flex align-items-center justify-content-center" 
                       title="Eliminar este Registro"
                       onclick="return confirm('¿Está seguro de eliminar la postulación de <?= esc($currentDignidad['persona_nombre']) ?> para <?= esc($currentDignidad['tipodignidad_nombre']) ?>?');">
                        <i class="bi bi-trash3 me-1"></i>
                        <span>Borrar</span>
                    </a>
                <?php else: ?>
                    <button class="btn btn-outline-secondary btn-sm" disabled>
                        <i class="bi bi-trash3 me-1"></i> Borrar
                    </button>
                <?php endif; ?>

                <!-- Listar -->
                <a href="<?= site_url('dignidad/listar') ?>" class="btn btn-info btn-sm text-white d-inline-flex align-items-center justify-content-center" title="Ver Listado Completo en Tabla">
                    <i class="bi bi-table me-1"></i>
                    <span>Listar</span>
                </a>
            </div>

        </div>
    </div>
</div>

<!-- ========================================================== -->
<!-- DETALLE DEL REGISTRO ACTUAL (PRESENTACIÓN INDIVIDUAL)       -->
<!-- ========================================================== -->
<?php if ($currentDignidad): ?>
    <?php
        // Foto de la persona en repositorio/fotos/{cedula}.jpg
        $cedula = $currentDignidad['persona_cedula'] ?? '';
        $rutaFotoFisica = ROOTPATH . 'repositorio/fotos/' . $cedula . '.jpg';
        $tieneFoto = !empty($cedula) && file_exists($rutaFotoFisica);
        $urlFoto = $tieneFoto ? site_url('persona/foto/' . $currentDignidad['idpersona']) . '?v=' . filemtime($rutaFotoFisica) : null;

        // Estilos de género
        $sexoLower = strtolower($currentDignidad['sexo_nombre'] ?? '');
        $badgeClass = 'bg-secondary-subtle text-secondary border';
        $iconoSexo = 'bi-gender-ambiguous';
        if (str_contains($sexoLower, 'masc')) {
            $badgeClass = 'bg-info-subtle text-info-emphasis border border-info-subtle';
            $iconoSexo = 'bi-gender-male';
        } elseif (str_contains($sexoLower, 'fem')) {
            $badgeClass = 'bg-danger-subtle text-danger-emphasis border border-danger-subtle';
            $iconoSexo = 'bi-gender-female';
        }
    ?>

    <div class="row">
        <!-- Tarjeta Principal con Información de la Candidatura -->
        <div class="col-lg-7 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-warning-subtle text-dark border border-warning-subtle px-2 py-1 fs-6 fw-semibold">
                            <i class="bi bi-award-fill text-warning me-1"></i> <?= esc($currentDignidad['tipodignidad_nombre'] ?? 'Sin Cargo') ?>
                        </span>
                        <span class="badge bg-light text-secondary border">
                            ID: #<?= esc($currentDignidad['iddignidad']) ?>
                        </span>
                    </div>
                    <span class="badge <?= $badgeClass ?> px-3 py-1 fs-7">
                        <i class="bi <?= $iconoSexo ?> me-1"></i><?= esc($currentDignidad['sexo_nombre'] ?? 'No Asignado') ?>
                    </span>
                </div>

                <div class="card-body p-4">
                    <!-- Cabecera visual del candidato con Foto -->
                    <div class="d-flex align-items-center gap-3 p-3 bg-light rounded-3 border mb-4">
                        <?php if ($tieneFoto): ?>
                            <div class="position-relative">
                                <img src="<?= $urlFoto ?>" 
                                     alt="Foto de <?= esc($currentDignidad['persona_nombre']) ?>" 
                                     class="rounded-circle shadow-sm border border-3 border-white" 
                                     style="width: 76px; height: 76px; object-fit: cover;">
                            </div>
                        <?php else: ?>
                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-sm" 
                                 style="width: 76px; height: 76px; font-size: 2.2rem; background: linear-gradient(135deg, #f59e0b, #d97706);">
                                <i class="bi bi-person"></i>
                            </div>
                        <?php endif; ?>
                        <div class="flex-grow-1">
                            <h4 class="mb-1 text-dark fw-bold"><?= esc($currentDignidad['persona_nombre'] . ' ' . $currentDignidad['persona_apellidos']) ?></h4>
                            <p class="text-muted small mb-0">
                                <i class="bi bi-card-text text-primary me-1"></i>
                                Cédula: <strong class="font-monospace text-dark fs-6"><?= esc($currentDignidad['persona_cedula'] ?? 'N/A') ?></strong>
                            </p>
                        </div>
                    </div>

                    <!-- Campos del registro -->
                    <div class="list-group list-group-flush rounded-3 border">
                        <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <span class="text-muted fw-semibold">
                                <i class="bi bi-hash text-primary me-2"></i>Identificador de Postulación:
                            </span>
                            <span class="fw-bold font-monospace fs-6">#<?= esc($currentDignidad['iddignidad']) ?></span>
                        </div>

                        <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <span class="text-muted fw-semibold">
                                <i class="bi bi-award-fill text-warning me-2"></i>Cargo / Dignidad Electoral:
                            </span>
                            <span class="badge bg-warning-subtle text-dark border border-warning-subtle px-3 py-1 fs-6 fw-semibold">
                                <?= esc($currentDignidad['tipodignidad_nombre'] ?? 'Sin Asignar') ?>
                            </span>
                        </div>

                        <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <span class="text-muted fw-semibold">
                                <i class="bi bi-person-fill text-primary me-2"></i>Candidato Postulado:
                            </span>
                            <span class="fw-bold text-dark fs-6">
                                <?= esc($currentDignidad['persona_nombre'] . ' ' . $currentDignidad['persona_apellidos']) ?>
                            </span>
                        </div>

                        <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <span class="text-muted fw-semibold">
                                <i class="bi bi-credit-card-2-front text-primary me-2"></i>Número de Cédula:
                            </span>
                            <span class="badge bg-light text-dark border font-monospace px-3 py-1 fs-6">
                                <?= esc($currentDignidad['persona_cedula'] ?? 'N/A') ?>
                            </span>
                        </div>

                        <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <span class="text-muted fw-semibold">
                                <i class="bi <?= $iconoSexo ?> text-primary me-2"></i>Género del Candidato:
                            </span>
                            <span class="badge <?= $badgeClass ?> px-3 py-1 fs-6">
                                <i class="bi <?= $iconoSexo ?> me-1"></i><?= esc($currentDignidad['sexo_nombre'] ?? 'No Asignado') ?>
                            </span>
                        </div>

                        <?php if (!empty($currentDignidad['fechanacimiento'])): ?>
                            <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                                <span class="text-muted fw-semibold">
                                    <i class="bi bi-calendar3 text-primary me-2"></i>Fecha de Nacimiento:
                                </span>
                                <span class="fw-semibold text-dark">
                                    <?= date('d/m/Y', strtotime($currentDignidad['fechanacimiento'])) ?>
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="card-footer bg-white border-top py-3 d-flex justify-content-between align-items-center">
                    <a href="<?= site_url('persona/ver/' . $currentDignidad['idpersona']) ?>" class="btn btn-sm btn-outline-info">
                        <i class="bi bi-person-lines-fill me-1"></i> Ver Ficha Personal
                    </a>
                    <div class="d-flex gap-2">
                        <a href="<?= site_url('dignidad/edit/' . $currentDignidad['iddignidad']) ?>" class="btn btn-sm btn-outline-warning text-dark">
                            <i class="bi bi-pencil-square me-1"></i> Modificar
                        </a>
                        <a href="<?= site_url('dignidad/listar') ?>" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-list-ul me-1"></i> Ver Todas
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tarjeta Secundaria: Votación Registrada en Actas de Escrutinio -->
        <div class="col-lg-5 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                    <div class="fw-bold text-dark d-flex align-items-center gap-2">
                        <i class="bi bi-file-earmark-check-fill text-primary"></i>
                        <span>Votación en Actas de Escrutinio</span>
                    </div>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">
                        <?= count($votacionesActas) ?> acta(s)
                    </span>
                </div>

                <div class="card-body p-3">
                    <?php if (empty($votacionesActas)): ?>
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-inbox fs-1 text-secondary opacity-50 mb-2 d-block"></i>
                            <p class="mb-2 fw-semibold">Esta candidatura aún no registra votos en actas de escrutinio.</p>
                            <p class="small text-muted mb-3">Abra una acta de escrutinio e ingrese la votación alcanzada por este candidato.</p>
                            <a href="<?= site_url('acta') ?>" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-file-earmark-text me-1"></i> Ir a Actas de Escrutinio
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 120px;" class="text-center">Votación</th>
                                        <th>Acta / Mesa</th>
                                        <th class="text-end" style="width: 70px;">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($votacionesActas as $v): ?>
                                        <tr>
                                            <td class="text-center">
                                                <span class="badge bg-success-subtle text-success-emphasis border border-success-subtle px-2 py-1 font-monospace fs-6 fw-bold">
                                                    <i class="bi bi-check-circle me-1"></i><?= number_format((int)$v['votacion']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="fw-semibold text-dark small">
                                                    <a href="<?= site_url('acta/ver/' . $v['idacta']) ?>" class="text-decoration-none">
                                                        Acta #<?= esc($v['idacta']) ?>
                                                    </a> - Mesa <?= esc($v['meza_numero'] ?? 'N/A') ?> (<?= esc($v['sexo_nombre'] ?? '') ?>)
                                                </div>
                                                <div class="text-muted small" style="font-size: 0.78rem;">
                                                    <i class="bi bi-building me-1"></i><?= esc($v['recinto_nombre'] ?? 'Sin Recinto') ?>
                                                </div>
                                            </td>
                                            <td class="text-end">
                                                <a href="<?= site_url('acta/ver/' . $v['idacta']) ?>" class="btn btn-sm btn-outline-primary p-1 px-2" title="Ver Acta de Escrutinio">
                                                    <i class="bi bi-eye" style="font-size: 0.8rem;"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="card-footer bg-white border-top py-2 d-flex justify-content-between align-items-center">
                    <a href="<?= site_url('dignidadacta') ?>" class="btn btn-sm btn-link text-decoration-none px-0">
                        <i class="bi bi-box-arrow-up-right me-1"></i> Votaciones por Dignidad
                    </a>
                    <a href="<?= site_url('acta') ?>" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-file-earmark-text me-1"></i> Ver Actas
                    </a>
                </div>
            </div>
        </div>
    </div>

<?php else: ?>
    <!-- Estado Vacío cuando no existen registros -->
    <div class="card shadow-sm border-0 py-5 text-center">
        <div class="card-body">
            <i class="bi bi-person-badge fs-1 d-block mb-3 text-secondary"></i>
            <h4 class="text-dark fw-bold">No existen dignidades o candidaturas registradas</h4>
            <p class="text-muted mb-4">Actualmente la base de datos no contiene postulaciones electorales.</p>
            <a href="<?= site_url('dignidad/create') ?>" class="btn btn-primary px-4 py-2">
                <i class="bi bi-plus-circle me-1"></i> Registrar la Primera Candidatura
            </a>
        </div>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Atajos de teclado: Flecha Izquierda (Anterior) y Flecha Derecha (Siguiente)
    document.addEventListener('keydown', function(e) {
        // Evitar accionar si el foco está en un input o select
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
