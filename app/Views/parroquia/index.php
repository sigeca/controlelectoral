<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Título y Descripción del Módulo -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">
    <div>
        <h2 class="h3 mb-0 text-gray-800">
            <i class="bi bi-geo-fill me-2 text-primary"></i>Parroquia (Navegador Individual)
        </h2>
        <p class="text-muted small mb-0">Visualización registro por registro con barra de navegación, operaciones CRUD y recintos electorales pertenecientes.</p>
    </div>
    <?php if ($total > 0): ?>
        <div class="d-flex align-items-center gap-2">
            <label for="jumpParroquiaSelect" class="form-label small mb-0 text-muted d-none d-sm-inline">Ir a:</label>
            <select id="jumpParroquiaSelect" class="form-select form-select-sm" style="min-width: 200px;" onchange="if(this.value) window.location.href=this.value;">
                <?php foreach ($allIds as $idx => $id): ?>
                    <option value="<?= site_url('parroquia/ver/' . $id) ?>" <?= ($idx === $currentIndex) ? 'selected' : '' ?>>
                        Registro <?= ($idx + 1) ?> de <?= $total ?> (Parroquia ID #<?= $id ?>)
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
                <a href="<?= ($firstId && $currentIndex > 0) ? site_url('parroquia/ver/' . $firstId) : '#' ?>" 
                   class="btn btn-outline-primary btn-sm d-inline-flex align-items-center justify-content-center <?= ($currentIndex === 0 || $total === 0) ? 'disabled' : '' ?>"
                   title="Primer Registro (Inicio)">
                    <i class="bi bi-chevron-double-left me-1"></i>
                    <span class="d-none d-sm-inline">Primero</span>
                </a>

                <!-- Anterior -->
                <a href="<?= ($prevId !== null) ? site_url('parroquia/ver/' . $prevId) : '#' ?>" 
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
                <a href="<?= ($nextId !== null) ? site_url('parroquia/ver/' . $nextId) : '#' ?>" 
                   class="btn btn-outline-primary btn-sm d-inline-flex align-items-center justify-content-center <?= ($nextId === null) ? 'disabled' : '' ?>"
                   title="Registro Siguiente (Flecha Derecha)" id="btnSiguiente">
                    <span>Siguiente</span>
                    <i class="bi bi-chevron-right ms-1"></i>
                </a>

                <!-- Último -->
                <a href="<?= ($lastId && $currentIndex < $total - 1) ? site_url('parroquia/ver/' . $lastId) : '#' ?>" 
                   class="btn btn-outline-primary btn-sm d-inline-flex align-items-center justify-content-center <?= ($currentIndex >= $total - 1 || $total === 0) ? 'disabled' : '' ?>"
                   title="Último Registro (Fin)">
                    <span class="d-none d-sm-inline">Último</span>
                    <i class="bi bi-chevron-double-right ms-1"></i>
                </a>
            </div>

            <!-- Grupo de Acciones: Nuevo, Editar, Borrar, Listar -->
            <div class="btn-group shadow-sm" role="group" aria-label="Acciones de registro">
                <!-- Nuevo -->
                <a href="<?= site_url('parroquia/create') ?>" class="btn btn-success btn-sm d-inline-flex align-items-center justify-content-center" title="Registrar Nueva Parroquia">
                    <i class="bi bi-plus-circle me-1"></i>
                    <span>Nuevo</span>
                </a>

                <!-- Editar -->
                <?php if ($currentParroquia): ?>
                    <a href="<?= site_url('parroquia/edit/' . $currentParroquia['idparroquia']) ?>" class="btn btn-warning btn-sm text-dark d-inline-flex align-items-center justify-content-center" title="Editar este Registro">
                        <i class="bi bi-pencil-square me-1"></i>
                        <span>Editar</span>
                    </a>
                <?php else: ?>
                    <button class="btn btn-outline-secondary btn-sm" disabled>
                        <i class="bi bi-pencil-square me-1"></i> Editar
                    </button>
                <?php endif; ?>

                <!-- Borrar -->
                <?php if ($currentParroquia): ?>
                    <a href="<?= site_url('parroquia/delete/' . $currentParroquia['idparroquia']) ?>" 
                       class="btn btn-danger btn-sm d-inline-flex align-items-center justify-content-center" 
                       title="Eliminar este Registro"
                       onclick="return confirm('¿Está seguro de eliminar la Parroquia \'<?= esc($currentParroquia['nombre']) ?>\' (ID: <?= esc($currentParroquia['idparroquia']) ?>)?');">
                        <i class="bi bi-trash3 me-1"></i>
                        <span>Borrar</span>
                    </a>
                <?php else: ?>
                    <button class="btn btn-outline-secondary btn-sm" disabled>
                        <i class="bi bi-trash3 me-1"></i> Borrar
                    </button>
                <?php endif; ?>

                <!-- Listar -->
                <a href="<?= site_url('parroquia/listar') ?>" class="btn btn-info btn-sm text-white d-inline-flex align-items-center justify-content-center" title="Ver Listado Completo en Tabla">
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
<?php if ($currentParroquia): ?>

    <div class="row mb-4">
        <!-- Tarjeta Principal con Información de la Parroquia -->
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <span class="badge bg-primary text-white px-2 py-1 fs-6">
                            <i class="bi bi-geo-alt-fill me-1"></i> Parroquia ID #<?= esc($currentParroquia['idparroquia']) ?>
                        </span>
                        <?php if (!empty($currentParroquia['tipoparroquia_nombre'])): ?>
                            <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle px-2 py-1 fs-6">
                                <i class="bi bi-tag-fill me-1"></i><?= esc($currentParroquia['tipoparroquia_nombre']) ?>
                            </span>
                        <?php endif; ?>
                        <?php if (!empty($currentParroquia['distrito_nombre'])): ?>
                            <span class="badge bg-purple-subtle text-purple border px-2 py-1 fs-6" style="background-color: #f3e8ff; color: #6b21a8; border-color: #e9d5ff;">
                                <i class="bi bi-map-fill me-1"></i>Distrito: <?= esc($currentParroquia['distrito_nombre']) ?>
                            </span>
                        <?php endif; ?>
                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fs-6">
                            <i class="bi bi-building-check me-1"></i><?= count($recintosAsociados) ?> Recinto(s) Electoral(es)
                        </span>
                    </div>
                    <div>
                        <a href="<?= site_url('parroquia/edit/' . $currentParroquia['idparroquia']) ?>" class="btn btn-sm btn-outline-warning text-dark me-1">
                            <i class="bi bi-pencil-square me-1"></i> Modificar
                        </a>
                    </div>
                </div>

                <div class="card-body p-4">
                    <!-- Cabecera visual del registro -->
                    <div class="d-flex align-items-center gap-3 p-3 bg-light rounded-3 border mb-4">
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-sm flex-shrink-0" 
                             style="width: 64px; height: 64px; font-size: 1.75rem; background: linear-gradient(135deg, #0d6efd, #0b5ed7);">
                            <i class="bi bi-geo-alt-fill"></i>
                        </div>
                        <div>
                            <h4 class="mb-1 text-dark fw-bold"><?= esc($currentParroquia['nombre']) ?></h4>
                            <p class="text-muted small mb-0">
                                <i class="bi bi-buildings me-1 text-primary"></i>
                                Cantón: <strong><?= esc($currentParroquia['canton_nombre'] ?? 'N/A') ?></strong> &bull; 
                                Provincia: <strong><?= esc($currentParroquia['provincia_nombre'] ?? 'N/A') ?></strong>
                            </p>
                        </div>
                    </div>

                    <!-- Lista de Campos Ficha -->
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div class="p-3 bg-light rounded-3 border">
                                <small class="text-muted d-block fw-semibold mb-1"><i class="bi bi-hash text-primary me-1"></i>ID Parroquia:</small>
                                <span class="fw-bold font-monospace fs-6">#<?= esc($currentParroquia['idparroquia']) ?></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3 bg-light rounded-3 border">
                                <small class="text-muted d-block fw-semibold mb-1"><i class="bi bi-tag text-primary me-1"></i>Tipo de Parroquia:</small>
                                <span class="fw-bold text-dark fs-6"><?= esc($currentParroquia['tipoparroquia_nombre'] ?? 'Sin tipo asignado') ?></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3 bg-light rounded-3 border">
                                <small class="text-muted d-block fw-semibold mb-1"><i class="bi bi-map text-primary me-1"></i>Distrito Electoral:</small>
                                <span class="fw-bold text-dark fs-6"><?= esc($currentParroquia['distrito_nombre'] ?? 'Sin distrito asignado') ?></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3 bg-light rounded-3 border">
                                <small class="text-muted d-block fw-semibold mb-1"><i class="bi bi-buildings text-primary me-1"></i>Cantón y Provincia:</small>
                                <span class="fw-bold text-dark fs-6"><?= esc($currentParroquia['canton_nombre'] ?? 'N/A') ?> (<?= esc($currentParroquia['provincia_nombre'] ?? 'N/A') ?>)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================================== -->
    <!-- RECINTOS ELECTORALES PERTENECIENTES A ESTA PARROQUIA        -->
    <!-- ========================================================== -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
            <div class="fw-bold text-dark d-flex align-items-center gap-2">
                <i class="bi bi-building-check text-primary"></i>
                <span>Recintos Electorales Pertenecientes a esta Parroquia (<?= count($recintosAsociados) ?>)</span>
            </div>
            <a href="<?= site_url('recintoelectoral/create') ?>" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-plus-circle me-1"></i> Crear Nuevo Recinto
            </a>
        </div>

        <div class="card-body p-0">
            <?php if (empty($recintosAsociados)): ?>
                <div class="text-center py-5 px-3 text-muted">
                    <i class="bi bi-building fs-1 text-secondary opacity-50 mb-2 d-block"></i>
                    <h6 class="fw-bold text-dark mb-1">No hay recintos electorales asociados a esta parroquia</h6>
                    <p class="small text-muted mb-3" style="max-width: 450px; margin: 0 auto;">
                        Actualmente no existen recintos registrados en las zonas de esta parroquia. Puede crear un nuevo recinto y asignarle una zona perteneciente a esta parroquia.
                    </p>
                    <a href="<?= site_url('recintoelectoral/create') ?>" class="btn btn-sm btn-primary">
                        <i class="bi bi-plus-circle me-1"></i> Registrar Recinto Electoral
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 80px;">ID</th>
                                <th>Nombre del Recinto Electoral</th>
                                <th>Zona Electoral</th>
                                <th class="text-center">N° Electores Habilitados</th>
                                <th class="text-center" style="width: 170px;">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recintosAsociados as $r): ?>
                                <tr>
                                    <td class="text-center fw-semibold text-secondary">#<?= esc($r['idrecintoelectoral']) ?></td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="bg-primary text-white rounded-circle p-2 d-inline-flex justify-content-center align-items-center" style="width: 32px; height: 32px;">
                                                <i class="bi bi-building" style="font-size: 0.85rem;"></i>
                                            </div>
                                            <a href="<?= site_url('recintoelectoral/ver/' . $r['idrecintoelectoral']) ?>" class="fw-bold text-dark text-decoration-none hover-primary fs-6">
                                                <?= esc($r['nombre']) ?>
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border px-2 py-1 fs-7">
                                            <i class="bi bi-pin-map-fill me-1 text-primary"></i><?= esc($r['zona_nombre'] ?? 'Sin Zona') ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 fs-7 fw-bold">
                                            <i class="bi bi-people-fill me-1"></i><?= number_format((int)($r['numeroelectores'] ?? 0), 0, ',', '.') ?> electores
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <!-- Enlace directo para ir al registro individual de ese recinto -->
                                        <a href="<?= site_url('recintoelectoral/ver/' . $r['idrecintoelectoral']) ?>" class="btn btn-outline-primary btn-sm fw-semibold" title="Ir al registro de este recinto">
                                            <i class="bi bi-eye me-1"></i> Ver Recinto
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

<?php else: ?>
    <!-- Estado Vacío cuando no existen registros -->
    <div class="card shadow-sm border-0 py-5 text-center">
        <div class="card-body">
            <i class="bi bi-geo fs-1 d-block mb-3 text-secondary"></i>
            <h4 class="text-dark fw-bold">No existen parroquias registradas</h4>
            <p class="text-muted mb-4">Actualmente la base de datos no contiene parroquias.</p>
            <a href="<?= site_url('parroquia/create') ?>" class="btn btn-primary px-4 py-2">
                <i class="bi bi-plus-circle me-1"></i> Registrar la Primera Parroquia
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
