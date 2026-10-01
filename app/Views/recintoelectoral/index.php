<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Título y Descripción del Módulo -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">
    <div>
        <h2 class="h3 mb-0 text-gray-800">
            <i class="bi bi-building-check me-2 text-primary"></i>Recinto Electoral (Navegador Individual)
        </h2>
        <p class="text-muted small mb-0">Visualización registro por registro con barra de navegación y operaciones CRUD.</p>
    </div>
    <?php if ($total > 0): ?>
        <div class="d-flex align-items-center gap-2">
            <label for="jumpRecintoSelect" class="form-label small mb-0 text-muted d-none d-sm-inline">Ir a:</label>
            <select id="jumpRecintoSelect" class="form-select form-select-sm" style="min-width: 200px;" onchange="if(this.value) window.location.href=this.value;">
                <?php foreach ($allIds as $idx => $id): ?>
                    <option value="<?= site_url('recintoelectoral/ver/' . $id) ?>" <?= ($idx === $currentIndex) ? 'selected' : '' ?>>
                        Registro <?= ($idx + 1) ?> de <?= $total ?> (Recinto ID #<?= $id ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    <?php endif; ?>
</div>

<!-- ========================================================== -->
<!-- MENÚ SUPERIOR DE NAVEGACIÓN Y ACCIONES                     -->
<!-- (Primero, Anterior, Siguiente, Último | Nuevo, Editar, Borrar, Listar) -->
<!-- ========================================================== -->
<div class="card shadow-sm border-0 mb-4 bg-white">
    <div class="card-body p-2 p-md-3">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-stretch align-items-lg-center gap-2">
            
            <!-- Grupo de Navegación: Primero, Anterior, Indicador, Siguiente, Último -->
            <div class="btn-group shadow-sm" role="group" aria-label="Navegación de registros">
                <!-- Primero -->
                <a href="<?= ($firstId && $currentIndex > 0) ? site_url('recintoelectoral/ver/' . $firstId) : '#' ?>" 
                   class="btn btn-outline-primary btn-sm d-inline-flex align-items-center justify-content-center <?= ($currentIndex === 0 || $total === 0) ? 'disabled' : '' ?>"
                   title="Primer Registro (Inicio)">
                    <i class="bi bi-chevron-double-left me-1"></i>
                    <span class="d-none d-sm-inline">Primero</span>
                </a>

                <!-- Anterior -->
                <a href="<?= ($prevId !== null) ? site_url('recintoelectoral/ver/' . $prevId) : '#' ?>" 
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
                <a href="<?= ($nextId !== null) ? site_url('recintoelectoral/ver/' . $nextId) : '#' ?>" 
                   class="btn btn-outline-primary btn-sm d-inline-flex align-items-center justify-content-center <?= ($nextId === null) ? 'disabled' : '' ?>"
                   title="Registro Siguiente (Flecha Derecha)" id="btnSiguiente">
                    <span>Siguiente</span>
                    <i class="bi bi-chevron-right ms-1"></i>
                </a>

                <!-- Último -->
                <a href="<?= ($lastId && $currentIndex < $total - 1) ? site_url('recintoelectoral/ver/' . $lastId) : '#' ?>" 
                   class="btn btn-outline-primary btn-sm d-inline-flex align-items-center justify-content-center <?= ($currentIndex >= $total - 1 || $total === 0) ? 'disabled' : '' ?>"
                   title="Último Registro (Fin)">
                    <span class="d-none d-sm-inline">Último</span>
                    <i class="bi bi-chevron-double-right ms-1"></i>
                </a>
            </div>

            <!-- Grupo de Acciones: Nuevo, Editar, Borrar, Listar -->
            <div class="btn-group shadow-sm" role="group" aria-label="Acciones de registro">
                <!-- Nuevo -->
                <a href="<?= site_url('recintoelectoral/create') ?>" class="btn btn-success btn-sm d-inline-flex align-items-center justify-content-center" title="Registrar Nuevo Recinto">
                    <i class="bi bi-plus-circle me-1"></i>
                    <span>Nuevo</span>
                </a>

                <!-- Editar -->
                <?php if ($currentRecinto): ?>
                    <a href="<?= site_url('recintoelectoral/edit/' . $currentRecinto['idrecintoelectoral']) ?>" class="btn btn-warning btn-sm text-dark d-inline-flex align-items-center justify-content-center" title="Editar este Registro">
                        <i class="bi bi-pencil-square me-1"></i>
                        <span>Editar</span>
                    </a>
                <?php else: ?>
                    <button class="btn btn-outline-secondary btn-sm" disabled>
                        <i class="bi bi-pencil-square me-1"></i> Editar
                    </button>
                <?php endif; ?>

                <!-- Borrar -->
                <?php if ($currentRecinto): ?>
                    <a href="<?= site_url('recintoelectoral/delete/' . $currentRecinto['idrecintoelectoral']) ?>" 
                       class="btn btn-danger btn-sm d-inline-flex align-items-center justify-content-center" 
                       title="Eliminar este Registro"
                       onclick="return confirm('¿Está seguro de eliminar el Recinto \'<?= esc($currentRecinto['nombre']) ?>\' (ID: <?= esc($currentRecinto['idrecintoelectoral']) ?>)?');">
                        <i class="bi bi-trash3 me-1"></i>
                        <span>Borrar</span>
                    </a>
                <?php else: ?>
                    <button class="btn btn-outline-secondary btn-sm" disabled>
                        <i class="bi bi-trash3 me-1"></i> Borrar
                    </button>
                <?php endif; ?>

                <!-- Listar -->
                <a href="<?= site_url('recintoelectoral/listar') ?>" class="btn btn-info btn-sm text-white d-inline-flex align-items-center justify-content-center" title="Ver Listado Completo en Tabla">
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
<?php if ($currentRecinto): ?>

    <div class="row">
        <!-- Tarjeta Principal con Información del Recinto Electoral -->
        <div class="col-lg-7 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-primary text-white px-2 py-1 fs-6">
                            <i class="bi bi-building me-1"></i> Recinto ID #<?= esc($currentRecinto['idrecintoelectoral']) ?>
                        </span>
                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                            <i class="bi bi-people-fill me-1"></i><?= number_format((int)$currentRecinto['numeroelectores'], 0, ',', '.') ?> Electores
                        </span>
                    </div>
                    <span class="badge bg-light text-dark border px-3 py-1 fs-7">
                        <i class="bi bi-pin-map-fill me-1 text-primary"></i><?= esc($currentRecinto['zona_nombre'] ?? 'Sin Zona') ?>
                    </span>
                </div>

                <div class="card-body p-4">
                    <!-- Cabecera visual del registro -->
                    <div class="d-flex align-items-center gap-3 p-3 bg-light rounded-3 border mb-4">
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-sm flex-shrink-0" 
                             style="width: 64px; height: 64px; font-size: 1.75rem; background: linear-gradient(135deg, #0d6efd, #0b5ed7);">
                            <i class="bi bi-building"></i>
                        </div>
                        <div>
                            <h4 class="mb-1 text-dark fw-bold"><?= esc($currentRecinto['nombre']) ?></h4>
                            <p class="text-muted small mb-0">
                                <i class="bi bi-geo-alt-fill text-danger me-1"></i>
                                <?= esc($currentRecinto['parroquia_nombre'] ?? 'N/A') ?> &bull; 
                                <span class="fw-semibold text-dark"><?= esc($currentRecinto['canton_nombre'] ?? 'N/A') ?>, <?= esc($currentRecinto['provincia_nombre'] ?? 'N/A') ?></span>
                            </p>
                        </div>
                    </div>

                    <!-- Campos del registro -->
                    <div class="list-group list-group-flush rounded-3 border">
                        <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <span class="text-muted fw-semibold">
                                <i class="bi bi-hash text-primary me-2"></i>Identificador (ID):
                            </span>
                            <span class="fw-bold font-monospace fs-6">#<?= esc($currentRecinto['idrecintoelectoral']) ?></span>
                        </div>

                        <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <span class="text-muted fw-semibold">
                                <i class="bi bi-building-check text-primary me-2"></i>Nombre del Recinto:
                            </span>
                            <span class="fw-bold text-dark fs-6"><?= esc($currentRecinto['nombre']) ?></span>
                        </div>

                        <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <span class="text-muted fw-semibold">
                                <i class="bi bi-people-fill text-primary me-2"></i>Número de Electores Habilitados:
                            </span>
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 fs-6 fw-bold">
                                <?= number_format((int)$currentRecinto['numeroelectores'], 0, ',', '.') ?> empadronados
                            </span>
                        </div>

                        <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <span class="text-muted fw-semibold">
                                <i class="bi bi-pin-map text-primary me-2"></i>Zona Electoral:
                            </span>
                            <span class="badge bg-light text-dark border px-3 py-1 fs-6">
                                <i class="bi bi-pin-map-fill me-1 text-primary"></i><?= esc($currentRecinto['zona_nombre'] ?? 'No Asignada') ?>
                            </span>
                        </div>

                        <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <span class="text-muted fw-semibold">
                                <i class="bi bi-geo text-primary me-2"></i>Parroquia:
                            </span>
                            <span class="fw-semibold text-dark"><?= esc($currentRecinto['parroquia_nombre'] ?? 'N/A') ?></span>
                        </div>

                        <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <span class="text-muted fw-semibold">
                                <i class="bi bi-buildings text-primary me-2"></i>Cantón y Provincia:
                            </span>
                            <span class="badge bg-light text-dark border px-2 py-1 fs-7">
                                <?= esc($currentRecinto['canton_nombre'] ?? 'N/A') ?> &bull; <?= esc($currentRecinto['provincia_nombre'] ?? 'N/A') ?>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-white border-top py-3 d-flex justify-content-between align-items-center">
                    <span class="small text-muted">
                        <i class="bi bi-info-circle me-1"></i> Registro <?= ($currentIndex + 1) ?> de <?= $total ?>.
                    </span>
                    <div class="d-flex gap-2">
                        <a href="<?= site_url('recintoelectoral/edit/' . $currentRecinto['idrecintoelectoral']) ?>" class="btn btn-sm btn-outline-warning text-dark">
                            <i class="bi bi-pencil-square me-1"></i> Modificar
                        </a>
                        <a href="<?= site_url('recintoelectoral/listar') ?>" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-list-ul me-1"></i> Ver Todos
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tarjeta Secundaria: Mesas Electorales Instaladas en este Recinto -->
        <div class="col-lg-5 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                    <div class="fw-bold text-dark d-flex align-items-center gap-2">
                        <i class="bi bi-inbox-fill text-primary"></i>
                        <span>Juntas Receptoras del Voto (Mesas)</span>
                    </div>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">
                        <?= count($mezasAsociadas) ?> mesa(s)
                    </span>
                </div>

                <div class="card-body p-0">
                    <?php if (empty($mezasAsociadas)): ?>
                        <div class="text-center py-4 px-3 text-muted">
                            <i class="bi bi-inbox fs-1 text-secondary opacity-50 mb-2 d-block"></i>
                            <p class="mb-2 fw-semibold">No hay mesas electorales asociadas a este recinto.</p>
                            <p class="small text-muted mb-3">Puede agregar juntas receptoras del voto vinculadas a este establecimiento electoral.</p>
                            <a href="<?= site_url('meza/create') ?>" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-plus-circle me-1"></i> Crear Mesa Electoral
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($mezasAsociadas as $m): ?>
                                <?php
                                    $sexoLower = strtolower($m['sexo_nombre'] ?? '');
                                    $badgeSexoClass = 'bg-secondary-subtle text-secondary';
                                    $iconoSexo = 'bi-gender-ambiguous';
                                    if (str_contains($sexoLower, 'masc')) {
                                        $badgeSexoClass = 'bg-info-subtle text-info-emphasis border border-info-subtle';
                                        $iconoSexo = 'bi-gender-male';
                                    } elseif (str_contains($sexoLower, 'fem')) {
                                        $badgeSexoClass = 'bg-danger-subtle text-danger-emphasis border border-danger-subtle';
                                        $iconoSexo = 'bi-gender-female';
                                    }
                                ?>
                                <div class="list-group-item p-3 d-flex align-items-center justify-content-between gap-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center fw-bold" style="width: 42px; height: 42px;">
                                            #<?= esc($m['numero']) ?>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark fs-6 mb-1">
                                                Mesa N° <?= esc($m['numero']) ?>
                                            </div>
                                            <span class="badge <?= $badgeSexoClass ?> px-2 py-0 fw-semibold" style="font-size: 0.78rem;">
                                                <i class="bi <?= $iconoSexo ?> me-1"></i><?= esc($m['sexo_nombre'] ?? 'Sin género') ?>
                                            </span>
                                        </div>
                                    </div>

                                    <div>
                                        <a href="<?= site_url('meza/ver/' . $m['idmeza']) ?>" class="btn btn-outline-primary btn-sm px-2 py-1" title="Ver detalle de la mesa">
                                            <i class="bi bi-eye me-1"></i> Ver Mesa
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="card-footer bg-white border-top py-2 d-flex justify-content-between align-items-center">
                    <a href="<?= site_url('meza/listar') ?>" class="btn btn-sm btn-link text-decoration-none px-0">
                        <i class="bi bi-box-arrow-up-right me-1"></i> Ver todas las mesas
                    </a>
                    <a href="<?= site_url('meza/create') ?>" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-plus-circle me-1"></i> Crear Mesa
                    </a>
                </div>
            </div>
        </div>
    </div>

<?php else: ?>
    <!-- Estado Vacío cuando no existen registros -->
    <div class="card shadow-sm border-0 py-5 text-center">
        <div class="card-body">
            <i class="bi bi-building fs-1 d-block mb-3 text-secondary"></i>
            <h4 class="text-dark fw-bold">No existen recintos electorales registrados</h4>
            <p class="text-muted mb-4">Actualmente la base de datos no contiene establecimientos electorales.</p>
            <a href="<?= site_url('recintoelectoral/create') ?>" class="btn btn-primary px-4 py-2">
                <i class="bi bi-plus-circle me-1"></i> Registrar el Primer Recinto
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
