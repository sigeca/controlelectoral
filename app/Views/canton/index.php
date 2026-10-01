<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Título y Descripción del Módulo -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">
    <div>
        <h2 class="h3 mb-0 text-gray-800">
            <i class="bi bi-buildings-fill me-2 text-primary"></i>Cantón (Navegador Individual)
        </h2>
        <p class="text-muted small mb-0">Visualización registro por registro con barra de navegación, operaciones CRUD y parroquias pertenecientes.</p>
    </div>
    <?php if ($total > 0): ?>
        <div class="d-flex align-items-center gap-2">
            <label for="jumpCantonSelect" class="form-label small mb-0 text-muted d-none d-sm-inline">Ir a:</label>
            <select id="jumpCantonSelect" class="form-select form-select-sm" style="min-width: 190px;" onchange="if(this.value) window.location.href=this.value;">
                <?php foreach ($allIds as $idx => $id): ?>
                    <option value="<?= site_url('canton/ver/' . $id) ?>" <?= ($idx === $currentIndex) ? 'selected' : '' ?>>
                        Registro <?= ($idx + 1) ?> de <?= $total ?> (Cantón ID #<?= $id ?>)
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
                <a href="<?= ($firstId && $currentIndex > 0) ? site_url('canton/ver/' . $firstId) : '#' ?>" 
                   class="btn btn-outline-primary btn-sm d-inline-flex align-items-center justify-content-center <?= ($currentIndex === 0 || $total === 0) ? 'disabled' : '' ?>"
                   title="Primer Registro (Inicio)">
                    <i class="bi bi-chevron-double-left me-1"></i>
                    <span class="d-none d-sm-inline">Primero</span>
                </a>

                <!-- Anterior -->
                <a href="<?= ($prevId !== null) ? site_url('canton/ver/' . $prevId) : '#' ?>" 
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
                <a href="<?= ($nextId !== null) ? site_url('canton/ver/' . $nextId) : '#' ?>" 
                   class="btn btn-outline-primary btn-sm d-inline-flex align-items-center justify-content-center <?= ($nextId === null) ? 'disabled' : '' ?>"
                   title="Registro Siguiente (Flecha Derecha)" id="btnSiguiente">
                    <span>Siguiente</span>
                    <i class="bi bi-chevron-right ms-1"></i>
                </a>

                <!-- Último -->
                <a href="<?= ($lastId && $currentIndex < $total - 1) ? site_url('canton/ver/' . $lastId) : '#' ?>" 
                   class="btn btn-outline-primary btn-sm d-inline-flex align-items-center justify-content-center <?= ($currentIndex >= $total - 1 || $total === 0) ? 'disabled' : '' ?>"
                   title="Último Registro (Fin)">
                    <span class="d-none d-sm-inline">Último</span>
                    <i class="bi bi-chevron-double-right ms-1"></i>
                </a>
            </div>

            <!-- Grupo de Acciones: Nuevo, Editar, Borrar, Listar -->
            <div class="btn-group shadow-sm" role="group" aria-label="Acciones de registro">
                <!-- Nuevo -->
                <a href="<?= site_url('canton/create') ?>" class="btn btn-success btn-sm d-inline-flex align-items-center justify-content-center" title="Registrar Nuevo Cantón">
                    <i class="bi bi-plus-circle me-1"></i>
                    <span>Nuevo</span>
                </a>

                <!-- Editar -->
                <?php if ($currentCanton): ?>
                    <a href="<?= site_url('canton/edit/' . $currentCanton['idcanton']) ?>" class="btn btn-warning btn-sm text-dark d-inline-flex align-items-center justify-content-center" title="Editar este Registro">
                        <i class="bi bi-pencil-square me-1"></i>
                        <span>Editar</span>
                    </a>
                <?php else: ?>
                    <button class="btn btn-outline-secondary btn-sm" disabled>
                        <i class="bi bi-pencil-square me-1"></i> Editar
                    </button>
                <?php endif; ?>

                <!-- Borrar -->
                <?php if ($currentCanton): ?>
                    <a href="<?= site_url('canton/delete/' . $currentCanton['idcanton']) ?>" 
                       class="btn btn-danger btn-sm d-inline-flex align-items-center justify-content-center" 
                       title="Eliminar este Registro"
                       onclick="return confirm('¿Está seguro de eliminar el Cantón \'<?= esc($currentCanton['nombre']) ?>\' (ID: <?= esc($currentCanton['idcanton']) ?>)?');">
                        <i class="bi bi-trash3 me-1"></i>
                        <span>Borrar</span>
                    </a>
                <?php else: ?>
                    <button class="btn btn-outline-secondary btn-sm" disabled>
                        <i class="bi bi-trash3 me-1"></i> Borrar
                    </button>
                <?php endif; ?>

                <!-- Listar -->
                <a href="<?= site_url('canton/listar') ?>" class="btn btn-info btn-sm text-white d-inline-flex align-items-center justify-content-center" title="Ver Listado Completo en Tabla">
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
<?php if ($currentCanton): ?>

    <div class="row mb-4">
        <!-- Tarjeta Principal con Información del Cantón -->
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-primary text-white px-2 py-1 fs-6">
                            <i class="bi bi-buildings me-1"></i> Cantón ID #<?= esc($currentCanton['idcanton']) ?>
                        </span>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1 fs-6">
                            <i class="bi bi-map-fill me-1"></i>Provincia: <?= esc($currentCanton['provincia_nombre'] ?? 'Sin Provincia') ?>
                        </span>
                        <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 fs-6">
                            <i class="bi bi-geo-fill me-1"></i><?= count($parroquiasAsociadas) ?> Parroquia(s) Pertenecientes
                        </span>
                    </div>
                    <div>
                        <a href="<?= site_url('canton/edit/' . $currentCanton['idcanton']) ?>" class="btn btn-sm btn-outline-warning text-dark me-1">
                            <i class="bi bi-pencil-square me-1"></i> Modificar
                        </a>
                    </div>
                </div>

                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3 p-3 bg-light rounded-3 border mb-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-sm flex-shrink-0" 
                             style="width: 64px; height: 64px; font-size: 1.75rem; background: linear-gradient(135deg, #0d6efd, #0b5ed7);">
                            <i class="bi bi-buildings-fill"></i>
                        </div>
                        <div>
                            <h4 class="mb-1 text-dark fw-bold"><?= esc($currentCanton['nombre']) ?></h4>
                            <p class="text-muted small mb-0">
                                <i class="bi bi-map-fill text-primary me-1"></i>
                                Provincia de <strong><?= esc($currentCanton['provincia_nombre'] ?? 'N/A') ?></strong> &bull; 
                                Registro <?= ($currentIndex + 1) ?> de <?= $total ?>.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================================== -->
    <!-- PARROQUIAS PERTENECIENTES A ESTE CANTÓN                    -->
    <!-- ========================================================== -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
            <div class="fw-bold text-dark d-flex align-items-center gap-2">
                <i class="bi bi-geo-fill text-primary"></i>
                <span>Parroquias Pertenecientes a este Cantón (<?= count($parroquiasAsociadas) ?>)</span>
            </div>
            <a href="<?= site_url('parroquia/create') ?>" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-plus-circle me-1"></i> Asignar / Crear Parroquia
            </a>
        </div>

        <div class="card-body p-0">
            <?php if (empty($parroquiasAsociadas)): ?>
                <div class="text-center py-5 px-3 text-muted">
                    <i class="bi bi-geo fs-1 text-secondary opacity-50 mb-2 d-block"></i>
                    <h6 class="fw-bold text-dark mb-1">No hay parroquias asignadas a este cantón</h6>
                    <p class="small text-muted mb-3" style="max-width: 450px; margin: 0 auto;">
                        Actualmente no existen parroquias registradas pertenecientes a este cantón (<code>idcanton = <?= esc($currentCanton['idcanton']) ?></code>).
                    </p>
                    <a href="<?= site_url('parroquia/create') ?>" class="btn btn-sm btn-primary">
                        <i class="bi bi-plus-circle me-1"></i> Registrar Nueva Parroquia
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 80px;">ID</th>
                                <th>Nombre de la Parroquia</th>
                                <th>Tipo de Parroquia</th>
                                <th>Distrito Electoral</th>
                                <th class="text-center" style="width: 170px;">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($parroquiasAsociadas as $p): ?>
                                <tr>
                                    <td class="text-center fw-semibold text-secondary">#<?= esc($p['idparroquia']) ?></td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="bg-primary text-white rounded-circle p-2 d-inline-flex justify-content-center align-items-center" style="width: 28px; height: 28px; font-size: 0.75rem;">
                                                <i class="bi bi-geo-alt-fill"></i>
                                            </div>
                                            <span class="fw-bold text-dark"><?= esc($p['nombre']) ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if (!empty($p['tipoparroquia_nombre'])): ?>
                                            <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle px-2 py-1 fs-7">
                                                <i class="bi bi-tag-fill me-1"></i><?= esc($p['tipoparroquia_nombre']) ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-light text-muted border px-2 py-1 fs-7">Sin tipo</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($p['distrito_nombre'])): ?>
                                            <span class="badge bg-purple-subtle text-purple border px-2 py-1 fs-7" style="background-color: #f3e8ff; color: #6b21a8; border-color: #e9d5ff;">
                                                <i class="bi bi-map-fill me-1"></i><?= esc($p['distrito_nombre']) ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-light text-muted border px-2 py-1 fs-7">Sin distrito</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <!-- Enlace directo para ver el registro individual de la parroquia -->
                                        <a href="<?= site_url('parroquia/ver/' . $p['idparroquia']) ?>" class="btn btn-outline-primary btn-sm fw-semibold" title="Ir al registro de esta parroquia">
                                            <i class="bi bi-eye me-1"></i> Ver Parroquia
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
            <i class="bi bi-buildings fs-1 d-block mb-3 text-secondary"></i>
            <h4 class="text-dark fw-bold">No existen cantones registrados</h4>
            <p class="text-muted mb-4">Actualmente la base de datos no contiene cantones.</p>
            <a href="<?= site_url('canton/create') ?>" class="btn btn-primary px-4 py-2">
                <i class="bi bi-plus-circle me-1"></i> Registrar el Primer Cantón
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
