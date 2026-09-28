<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Título y Selector Rápido del Módulo -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">
    <div>
        <h2 class="h3 mb-0 text-gray-800">
            <i class="bi bi-people-fill me-2 text-primary"></i>Padrón de Personas (Navegador Individual)
        </h2>
        <p class="text-muted small mb-0">Visualización registro por registro con barra de navegación y operaciones CRUD.</p>
    </div>
    <?php if ($total > 0): ?>
        <div class="d-flex align-items-center gap-2">
            <label for="jumpPersonaSelect" class="form-label small mb-0 text-muted d-none d-sm-inline">Ir a:</label>
            <select id="jumpPersonaSelect" class="form-select form-select-sm" style="min-width: 220px;" onchange="if(this.value) window.location.href=this.value;">
                <?php foreach ($allIds as $idx => $id): ?>
                    <option value="<?= site_url('persona/ver/' . $id) ?>" <?= ($idx === $currentIndex) ? 'selected' : '' ?>>
                        Registro <?= ($idx + 1) ?> de <?= $total ?> (Persona ID #<?= $id ?>)
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
            <div class="btn-group shadow-sm" role="group" aria-label="Navegación de personas">
                <!-- Primer -->
                <a href="<?= ($firstId && $currentIndex > 0) ? site_url('persona/ver/' . $firstId) : '#' ?>" 
                   class="btn btn-outline-primary btn-sm d-inline-flex align-items-center justify-content-center <?= ($currentIndex === 0 || $total === 0) ? 'disabled' : '' ?>"
                   title="Primer Registro (Inicio)">
                    <i class="bi bi-chevron-double-left me-1"></i>
                    <span class="d-none d-sm-inline">Primer</span>
                </a>

                <!-- Anterior -->
                <a href="<?= ($prevId !== null) ? site_url('persona/ver/' . $prevId) : '#' ?>" 
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
                <a href="<?= ($nextId !== null) ? site_url('persona/ver/' . $nextId) : '#' ?>" 
                   class="btn btn-outline-primary btn-sm d-inline-flex align-items-center justify-content-center <?= ($nextId === null) ? 'disabled' : '' ?>"
                   title="Registro Siguiente (Flecha Derecha)" id="btnSiguiente">
                    <span>Siguiente</span>
                    <i class="bi bi-chevron-right ms-1"></i>
                </a>

                <!-- Último -->
                <a href="<?= ($lastId && $currentIndex < $total - 1) ? site_url('persona/ver/' . $lastId) : '#' ?>" 
                   class="btn btn-outline-primary btn-sm d-inline-flex align-items-center justify-content-center <?= ($currentIndex >= $total - 1 || $total === 0) ? 'disabled' : '' ?>"
                   title="Último Registro (Fin)">
                    <span class="d-none d-sm-inline">Último</span>
                    <i class="bi bi-chevron-double-right ms-1"></i>
                </a>
            </div>

            <!-- Grupo de Acciones: Nuevo, Editar, Borrar, Listar -->
            <div class="btn-group shadow-sm" role="group" aria-label="Acciones de registro">
                <!-- Nuevo -->
                <a href="<?= site_url('persona/create') ?>" class="btn btn-success btn-sm d-inline-flex align-items-center justify-content-center" title="Registrar Nueva Persona">
                    <i class="bi bi-person-plus-fill me-1"></i>
                    <span>Nuevo</span>
                </a>

                <!-- Editar -->
                <?php if ($currentPersona): ?>
                    <a href="<?= site_url('persona/edit/' . $currentPersona['idpersona']) ?>" class="btn btn-warning btn-sm text-dark d-inline-flex align-items-center justify-content-center" title="Editar este Registro">
                        <i class="bi bi-pencil-square me-1"></i>
                        <span>Editar</span>
                    </a>
                <?php else: ?>
                    <button class="btn btn-outline-secondary btn-sm" disabled>
                        <i class="bi bi-pencil-square me-1"></i> Editar
                    </button>
                <?php endif; ?>

                <!-- Borrar -->
                <?php if ($currentPersona): ?>
                    <a href="<?= site_url('persona/delete/' . $currentPersona['idpersona']) ?>" 
                       class="btn btn-danger btn-sm d-inline-flex align-items-center justify-content-center" 
                       title="Eliminar este Registro"
                       onclick="return confirm('¿Está seguro de eliminar a la persona <?= esc($currentPersona['nombre'] . ' ' . $currentPersona['apellidos']) ?> (Cédula: <?= esc($currentPersona['cedula']) ?>)?');">
                        <i class="bi bi-trash3 me-1"></i>
                        <span>Borrar</span>
                    </a>
                <?php else: ?>
                    <button class="btn btn-outline-secondary btn-sm" disabled>
                        <i class="bi bi-trash3 me-1"></i> Borrar
                    </button>
                <?php endif; ?>

                <!-- Listar -->
                <a href="<?= site_url('persona/listar') ?>" class="btn btn-info btn-sm text-white d-inline-flex align-items-center justify-content-center" title="Ver Listado Completo en Tabla">
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
<?php if ($currentPersona): ?>
    <?php
        // Cálculo de edad
        $edad = '';
        if (!empty($currentPersona['fechanacimiento'])) {
            $nac = new DateTime($currentPersona['fechanacimiento']);
            $hoy = new DateTime();
            $diff = $hoy->diff($nac);
            $edad = $diff->y . ' años, ' . $diff->m . ' meses';
        }

        // Estilos de género
        $sexoLower = strtolower($currentPersona['sexo_nombre'] ?? '');
        $badgeClass = 'bg-secondary-subtle text-secondary border';
        $iconoSexo = 'bi-gender-ambiguous';
        if (str_contains($sexoLower, 'masc')) {
            $badgeClass = 'bg-info-subtle text-info-emphasis border border-info-subtle';
            $iconoSexo = 'bi-gender-male';
        } elseif (str_contains($sexoLower, 'fem')) {
            $badgeClass = 'bg-danger-subtle text-danger-emphasis border border-danger-subtle';
            $iconoSexo = 'bi-gender-female';
        }

        // Verificación de foto en repositorio/fotos/{cedula}.jpg
        $rutaFotoFisica = ROOTPATH . 'repositorio/fotos/' . $currentPersona['cedula'] . '.jpg';
        $tieneFoto = file_exists($rutaFotoFisica);
        $urlFoto = $tieneFoto ? site_url('persona/foto/' . $currentPersona['idpersona']) . '?v=' . filemtime($rutaFotoFisica) : null;
    ?>

    <div class="row">
        <!-- Ficha de Datos Personales -->
        <div class="col-lg-7 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-primary text-white px-2 py-1 fs-6">
                            <i class="bi bi-person-badge-fill me-1"></i> Ficha Electoral
                        </span>
                        <span class="badge bg-light text-secondary border">
                            ID: #<?= esc($currentPersona['idpersona']) ?>
                        </span>
                    </div>
                    <span class="badge <?= $badgeClass ?> px-3 py-1 fs-7">
                        <i class="bi <?= $iconoSexo ?> me-1"></i><?= esc($currentPersona['sexo_nombre'] ?? 'No Asignado') ?>
                    </span>
                </div>

                <div class="card-body p-4">
                    <!-- Cabecera visual del ciudadano con su Foto -->
                    <div class="d-flex align-items-center gap-3 p-3 bg-light rounded-3 border mb-3">
                        <?php if ($tieneFoto): ?>
                            <div class="position-relative">
                                <img src="<?= $urlFoto ?>" 
                                     alt="Foto de <?= esc($currentPersona['nombre']) ?>" 
                                     class="rounded-circle shadow-sm border border-3 border-white" 
                                     style="width: 76px; height: 76px; object-fit: cover;">
                            </div>
                        <?php else: ?>
                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-sm" 
                                 style="width: 76px; height: 76px; font-size: 2.2rem; background: linear-gradient(135deg, #0d6efd, #0b5ed7);">
                                <i class="bi bi-person"></i>
                            </div>
                        <?php endif; ?>
                        <div class="flex-grow-1">
                            <h4 class="mb-1 text-dark fw-bold"><?= esc($currentPersona['nombre'] . ' ' . $currentPersona['apellidos']) ?></h4>
                            <p class="text-muted small mb-0">
                                <i class="bi bi-card-text text-primary me-1"></i>
                                Cédula: <strong class="font-monospace text-dark fs-6"><?= esc($currentPersona['cedula']) ?></strong>
                            </p>
                        </div>
                    </div>

                    <!-- Sección de Carga de Foto en repositorio/fotos/{cedula}.jpg -->
                    <div class="p-3 bg-light rounded-3 border mb-4">
                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mb-2">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-camera-fill text-primary fs-5"></i>
                                <div>
                                    <span class="fw-bold text-dark small d-block">Foto de la Persona (repositorio/fotos/)</span>
                                    <span class="text-muted small" style="font-size: 0.78rem;">
                                        Archivo: <code class="text-primary bg-white px-1 py-0.5 rounded border"><?= esc($currentPersona['cedula']) ?>.jpg</code>
                                    </span>
                                </div>
                            </div>
                            <?php if ($tieneFoto): ?>
                                <div class="d-flex gap-2 align-items-center">
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 small">
                                        <i class="bi bi-check2-circle me-1"></i>Foto Cargada
                                    </span>
                                    <a href="<?= site_url('persona/eliminarFoto/' . $currentPersona['idpersona']) ?>" 
                                       class="btn btn-outline-danger btn-sm py-1 px-2" 
                                       title="Eliminar foto del repositorio"
                                       onclick="return confirm('¿Está seguro de eliminar la foto de <?= esc($currentPersona['nombre']) ?> (<?= esc($currentPersona['cedula']) ?>.jpg)?');">
                                        <i class="bi bi-trash3 me-1"></i>Eliminar Foto
                                    </a>
                                </div>
                            <?php else: ?>
                                <span class="badge bg-secondary-subtle text-secondary border px-2 py-1 small">
                                    <i class="bi bi-exclamation-circle me-1"></i>Sin Foto
                                </span>
                            <?php endif; ?>
                        </div>

                        <!-- Formulario para cargar o cambiar la foto -->
                        <form action="<?= site_url('persona/subirFoto/' . $currentPersona['idpersona']) ?>" 
                              method="post" 
                              enctype="multipart/form-data" 
                              class="d-flex flex-column flex-sm-row gap-2 mt-2">
                            <?= csrf_field() ?>
                            <div class="flex-grow-1">
                                <input type="file" 
                                       name="foto" 
                                       id="inputFotoPersona" 
                                       class="form-control form-control-sm" 
                                       accept=".jpg,.jpeg,.png,.webp,image/*" 
                                       required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm text-nowrap d-flex align-items-center justify-content-center">
                                <i class="bi bi-upload me-1"></i> <?= $tieneFoto ? 'Cambiar Foto' : 'Cargar Foto' ?>
                            </button>
                        </form>
                        <div class="form-text small text-muted mt-1" style="font-size: 0.76rem;">
                            Formatos permitidos: JPG, PNG, WEBP. La imagen se almacenará automáticamente como <strong><?= esc($currentPersona['cedula']) ?>.jpg</strong> en el directorio <code>repositorio/fotos/</code>.
                        </div>
                    </div>

                    <!-- Campos del registro -->
                    <div class="list-group list-group-flush rounded-3 border">
                        <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <span class="text-muted fw-semibold">
                                <i class="bi bi-hash text-primary me-2"></i>Identificador Interno (ID):
                            </span>
                            <span class="fw-bold font-monospace fs-6">#<?= esc($currentPersona['idpersona']) ?></span>
                        </div>

                        <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <span class="text-muted fw-semibold">
                                <i class="bi bi-credit-card-2-front text-primary me-2"></i>Número de Cédula:
                            </span>
                            <span class="badge bg-light text-dark border font-monospace px-3 py-1 fs-6">
                                <?= esc($currentPersona['cedula']) ?>
                            </span>
                        </div>

                        <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <span class="text-muted fw-semibold">
                                <i class="bi bi-person text-primary me-2"></i>Nombres Completos:
                            </span>
                            <span class="fw-semibold text-dark"><?= esc($currentPersona['nombre']) ?></span>
                        </div>

                        <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <span class="text-muted fw-semibold">
                                <i class="bi bi-person text-primary me-2"></i>Apellidos Completos:
                            </span>
                            <span class="fw-semibold text-dark"><?= esc($currentPersona['apellidos']) ?></span>
                        </div>

                        <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <span class="text-muted fw-semibold">
                                <i class="bi <?= $iconoSexo ?> text-primary me-2"></i>Sexo / Género:
                            </span>
                            <span class="badge <?= $badgeClass ?> px-3 py-1 fs-6">
                                <i class="bi <?= $iconoSexo ?> me-1"></i><?= esc($currentPersona['sexo_nombre'] ?? 'No Asignado') ?>
                            </span>
                        </div>

                        <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <span class="text-muted fw-semibold">
                                <i class="bi bi-calendar-event text-primary me-2"></i>Fecha de Nacimiento:
                            </span>
                            <span class="fw-semibold text-dark">
                                <i class="bi bi-calendar3 me-1 text-muted"></i><?= date('d/m/Y', strtotime($currentPersona['fechanacimiento'])) ?>
                            </span>
                        </div>

                        <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <span class="text-muted fw-semibold">
                                <i class="bi bi-hourglass-split text-primary me-2"></i>Edad Actual:
                            </span>
                            <span class="badge bg-secondary-subtle text-secondary border px-3 py-1 fs-7">
                                <?= $edad ?>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-white border-top py-3 d-flex justify-content-between align-items-center">
                    <span class="small text-muted">
                        <i class="bi bi-info-circle me-1"></i> Navegación activa: registro <?= ($currentIndex + 1) ?> de <?= $total ?>.
                    </span>
                    <div class="d-flex gap-2">
                        <a href="<?= site_url('persona/edit/' . $currentPersona['idpersona']) ?>" class="btn btn-sm btn-outline-warning text-dark">
                            <i class="bi bi-pencil-square me-1"></i> Modificar
                        </a>
                        <a href="<?= site_url('persona/listar') ?>" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-list-ul me-1"></i> Ver Todas
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel Lateral: Cuenta de Usuario y Candidaturas Asignadas -->
        <div class="col-lg-5 mb-4">
            <div class="d-flex flex-column gap-4 h-100">
                
                <!-- Tarjeta: Cuenta de Usuario -->
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                        <div class="fw-bold text-dark d-flex align-items-center gap-2">
                            <i class="bi bi-person-lock text-primary"></i>
                            <span>Acceso al Sistema</span>
                        </div>
                        <?php if ($usuarioInfo): ?>
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                                <i class="bi bi-check-circle me-1"></i>Usuario Activo
                            </span>
                        <?php else: ?>
                            <span class="badge bg-secondary-subtle text-secondary border px-2 py-1">
                                Sin Cuenta
                            </span>
                        <?php endif; ?>
                    </div>
                    <div class="card-body p-3">
                        <?php if ($usuarioInfo): ?>
                            <div class="d-flex align-items-center gap-3 p-2 bg-light rounded-3 border">
                                <div class="bg-success text-white rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                                    <i class="bi bi-shield-check fs-5"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-bold text-dark fs-6"><?= esc($usuarioInfo['usuario']) ?></div>
                                    <div class="small text-muted">
                                        <i class="bi bi-person-gear me-1"></i>Rol: <span class="badge bg-primary-subtle text-primary border border-primary-subtle"><?= esc($usuarioInfo['rol_nombre'] ?? 'Sin Rol') ?></span>
                                    </div>
                                </div>
                                <div>
                                    <a href="<?= site_url('usuario/show/' . $usuarioInfo['idusuario']) ?>" class="btn btn-sm btn-outline-primary" title="Ver ficha de usuario">
                                        <i class="bi bi-box-arrow-up-right"></i>
                                    </a>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-3 text-muted">
                                <i class="bi bi-person-slash fs-2 text-secondary opacity-50 d-block mb-2"></i>
                                <p class="small mb-2">Esta persona no posee un usuario de acceso al sistema.</p>
                                <a href="<?= site_url('usuario/create') ?>" class="btn btn-sm btn-outline-success">
                                    <i class="bi bi-plus-circle me-1"></i> Crear Usuario
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Tarjeta: Candidaturas y Dignidades -->
                <div class="card shadow-sm border-0 flex-grow-1">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                        <div class="fw-bold text-dark d-flex align-items-center gap-2">
                            <i class="bi bi-award-fill text-warning"></i>
                            <span>Candidaturas / Dignidades</span>
                        </div>
                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-1">
                            <?= count($dignidades) ?> asignada(s)
                        </span>
                    </div>
                    <div class="card-body p-3">
                        <?php if (empty($dignidades)): ?>
                            <div class="text-center py-4 text-muted">
                                <i class="bi bi-person-badge fs-2 text-secondary opacity-50 d-block mb-2"></i>
                                <p class="small mb-2">Esta persona no está registrada como candidata a ninguna dignidad.</p>
                                <a href="<?= site_url('dignidad/create') ?>" class="btn btn-sm btn-outline-warning text-dark">
                                    <i class="bi bi-plus-circle me-1"></i> Asignar Candidatura
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="list-group list-group-flush rounded-3 border">
                                <?php foreach ($dignidades as $d): ?>
                                    <div class="list-group-item d-flex justify-content-between align-items-center py-2">
                                        <div>
                                            <div class="fw-semibold text-dark small">
                                                <i class="bi bi-award text-warning me-1"></i><?= esc($d['tipodignidad_nombre'] ?? 'Sin Cargo') ?>
                                            </div>
                                            <small class="text-muted">Dignidad ID #<?= esc($d['iddignidad']) ?></small>
                                        </div>
                                        <a href="<?= site_url('dignidad/edit/' . $d['iddignidad']) ?>" class="btn btn-sm btn-outline-secondary p-1" title="Editar dignidad">
                                            <i class="bi bi-pencil" style="font-size: 0.75rem;"></i>
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="card-footer bg-white border-top py-2 d-flex justify-content-between align-items-center">
                        <a href="<?= site_url('dignidad') ?>" class="btn btn-sm btn-link text-decoration-none px-0">
                            <i class="bi bi-box-arrow-up-right me-1"></i> Módulo Dignidades
                        </a>
                        <a href="<?= site_url('dignidad/create') ?>" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-plus-circle me-1"></i> Postular
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>

<?php else: ?>
    <!-- Estado Vacío cuando no existen registros -->
    <div class="card shadow-sm border-0 py-5 text-center">
        <div class="card-body">
            <i class="bi bi-person-x fs-1 d-block mb-3 text-secondary"></i>
            <h4 class="text-dark fw-bold">No existen personas registradas</h4>
            <p class="text-muted mb-4">Actualmente la base de datos no contiene personas en el padrón electoral.</p>
            <a href="<?= site_url('persona/create') ?>" class="btn btn-primary px-4 py-2">
                <i class="bi bi-person-plus-fill me-1"></i> Registrar la Primera Persona
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
