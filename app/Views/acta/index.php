<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Título y Descripción del Módulo -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">
    <div>
        <h2 class="h3 mb-0 text-gray-800">
            <i class="bi bi-file-earmark-text me-2 text-primary"></i>Acta Electoral (Navegador Individual)
        </h2>
        <p class="text-muted small mb-0">Visualización registro por registro de las actas de escrutinio, totales de votación y desglose de candidatos por dignidad.</p>
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

    <?php 
        $validos = $currentActa['totalpapeleta'] - ($currentActa['totalblancos'] + $currentActa['totalnulos']);
        $rutaActaFisica = ROOTPATH . 'repositorio/actaescrutinio/' . $currentActa['idacta'] . '.jpg';
        $tieneActaFoto = file_exists($rutaActaFisica);
        $urlActaFoto = $tieneActaFoto ? site_url('acta/foto/' . $currentActa['idacta']) . '?v=' . filemtime($rutaActaFisica) : null;
    ?>

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
                    <div class="d-flex align-items-center gap-1">
                        <?php if ($tieneActaFoto): ?>
                            <a href="#seccionActaFoto" class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 text-decoration-none">
                                <i class="bi bi-file-earmark-check-fill me-1"></i>Con Foto
                            </a>
                        <?php else: ?>
                            <a href="#seccionActaFoto" class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2.5 py-1 text-decoration-none">
                                <i class="bi bi-file-earmark-x me-1"></i>Sin Foto
                            </a>
                        <?php endif; ?>
                        <a href="<?= site_url('acta/edit/' . $currentActa['idacta']) ?>" class="btn btn-sm btn-outline-warning text-dark ms-1">
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

                    <!-- Votos Calculados y Suma de Candidatos -->
                    <div class="mt-4 p-3 bg-success-subtle border border-success-subtle rounded-3 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
                        <div>
                            <span class="fw-bold text-success-emphasis d-block"><i class="bi bi-check-circle-fill me-1"></i> Votos Válidos Calculados</span>
                            <span class="text-muted small">Papeletas Totales (<?= number_format($currentActa['totalpapeleta'], 0, ',', '.') ?>) menos Blancos y Nulos</span>
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

    <!-- ========================================================== -->
    <!-- SECCIÓN DE EVIDENCIA: IMAGEN FÍSICA DEL ACTA DE ESCRUTINIO  -->
    <!-- ========================================================== -->
    <div class="row mb-4" id="seccionActaFoto">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 border-bottom">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="bi bi-file-earmark-image-fill fs-5"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 text-dark fw-bold">Imagen Física del Acta (Evidencia Digitalizada)</h5>
                            <small class="text-muted">Fotografía / Escaneo del acta física oficial cargada en el repositorio (<code>repositorio/actaescrutinio/<?= esc($currentActa['idacta']) ?>.jpg</code>)</small>
                        </div>
                    </div>
                    <div>
                        <?php if ($tieneActaFoto): ?>
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 fs-7 fw-bold">
                                <i class="bi bi-check-circle-fill me-1"></i>Foto Registrada
                            </span>
                        <?php else: ?>
                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-3 py-2 fs-7 fw-semibold">
                                <i class="bi bi-exclamation-triangle-fill me-1"></i>Foto Pendiente
                            </span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="card-body p-4">
                    <div class="row g-4 align-items-center">
                        <!-- Columna Izquierda: Formulario de Subida y Detalles -->
                        <div class="col-lg-5">
                            <div class="p-3 bg-light rounded-3 border mb-3">
                                <h6 class="fw-bold text-dark mb-2 d-flex align-items-center gap-2">
                                    <i class="bi bi-cloud-arrow-up text-primary fs-5"></i>
                                    <span><?= $tieneActaFoto ? 'Reemplazar Foto del Acta' : 'Subir Foto del Acta' ?></span>
                                </h6>
                                <p class="text-muted small mb-3">
                                    Cargue la fotografía o escaneo del acta física de escrutinio. Se guardará como <strong><?= esc($currentActa['idacta']) ?>.jpg</strong> en <code>repositorio/actaescrutinio/</code>.
                                </p>

                                <form action="<?= site_url('acta/subirFoto/' . $currentActa['idacta']) ?>" 
                                      method="post" 
                                      enctype="multipart/form-data">
                                    <?= csrf_field() ?>
                                    <div class="mb-3">
                                        <label for="inputFotoActa" class="form-label small fw-semibold text-secondary">
                                            Seleccionar Archivo de Imagen:
                                        </label>
                                        <input type="file" 
                                               name="foto" 
                                               id="inputFotoActa" 
                                               class="form-control form-control-sm" 
                                               accept=".jpg,.jpeg,.png,.webp,image/*" 
                                               required>
                                        <div class="form-text small" style="font-size: 0.76rem;">
                                            Formatos admitidos: JPG, PNG, WEBP (hasta 8MB).
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm w-100 py-2 fw-semibold d-flex align-items-center justify-content-center gap-1">
                                        <i class="bi bi-cloud-arrow-up-fill me-1"></i>
                                        <?= $tieneActaFoto ? 'Actualizar / Reemplazar Imagen' : 'Guardar y Vincular Imagen' ?>
                                    </button>
                                </form>
                            </div>

                            <!-- Acciones adicionales cuando existe foto -->
                            <?php if ($tieneActaFoto): ?>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-outline-primary btn-sm flex-fill" data-bs-toggle="modal" data-bs-target="#modalVerActaFoto">
                                        <i class="bi bi-zoom-in me-1"></i> Ver en Detalle
                                    </button>
                                    <a href="<?= $urlActaFoto ?>" target="_blank" class="btn btn-outline-secondary btn-sm" title="Abrir imagen en pestaña nueva">
                                        <i class="bi bi-box-arrow-up-right me-1"></i> Abrir
                                    </a>
                                    <a href="<?= site_url('acta/eliminarFoto/' . $currentActa['idacta']) ?>" 
                                       class="btn btn-outline-danger btn-sm" 
                                       title="Eliminar imagen del repositorio"
                                       onclick="return confirm('¿Está seguro de eliminar la imagen física del Acta #<?= esc($currentActa['idacta']) ?> (<?= esc($currentActa['idacta']) ?>.jpg)?');">
                                        <i class="bi bi-trash3 me-1"></i> Eliminar
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Columna Derecha: Visor de Imagen -->
                        <div class="col-lg-7">
                            <?php if ($tieneActaFoto): ?>
                                <div class="position-relative text-center bg-dark bg-opacity-10 p-2 rounded-3 border">
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#modalVerActaFoto" class="d-block text-decoration-none" title="Clic para ampliar el acta física">
                                        <img src="<?= $urlActaFoto ?>" 
                                             alt="Imagen de Acta #<?= esc($currentActa['idacta']) ?>" 
                                             class="img-fluid rounded shadow-sm" 
                                             style="max-height: 440px; object-fit: contain; width: 100%; background: #ffffff;">
                                        <div class="position-absolute bottom-0 start-50 translate-middle-x mb-3">
                                            <span class="badge bg-dark bg-opacity-75 text-white px-3 py-2 shadow border border-light border-opacity-25">
                                                <i class="bi bi-zoom-in me-1"></i> Clic para Ampliar Imagen Oficial
                                            </span>
                                        </div>
                                    </a>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-5 px-3 bg-light rounded-3 border border-dashed">
                                    <div class="rounded-circle bg-secondary bg-opacity-10 text-secondary p-3 d-inline-flex justify-content-center align-items-center mb-3" style="width: 72px; height: 72px;">
                                        <i class="bi bi-file-earmark-image fs-1 opacity-75"></i>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1">Sin Imagen Física Digitalizada</h6>
                                    <p class="text-muted small mb-0" style="max-width: 420px; margin: 0 auto;">
                                        Utilice el formulario de la izquierda para subir la fotografía del acta física de la <strong>Mesa N° <?= esc($currentActa['meza_numero'] ?? 'N/A') ?></strong> como respaldo.
                                    </p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Visualizar Foto del Acta en Tamaño Completo -->
    <?php if ($tieneActaFoto): ?>
        <div class="modal fade" id="modalVerActaFoto" tabindex="-1" aria-labelledby="modalVerActaFotoLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header bg-dark text-white py-3">
                        <h5 class="modal-title d-flex align-items-center gap-2" id="modalVerActaFotoLabel">
                            <i class="bi bi-file-earmark-image-fill text-warning"></i>
                            <span>Imagen Oficial del Acta N° <?= esc($currentActa['idacta']) ?> &bull; Mesa <?= esc($currentActa['meza_numero'] ?? 'N/A') ?></span>
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body text-center p-3 bg-secondary bg-opacity-10">
                        <img src="<?= $urlActaFoto ?>" 
                             class="img-fluid rounded shadow" 
                             alt="Imagen Oficial Acta #<?= esc($currentActa['idacta']) ?>"
                             style="max-height: 80vh; object-fit: contain;">
                    </div>
                    <div class="modal-footer justify-content-between bg-light py-2">
                        <div class="small text-muted">
                            <i class="bi bi-folder-check text-primary me-1"></i>
                            Archivo: <code>repositorio/actaescrutinio/<?= esc($currentActa['idacta']) ?>.jpg</code>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="<?= $urlActaFoto ?>" target="_blank" download="Acta_Escrutinio_ID_<?= esc($currentActa['idacta']) ?>.jpg" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-download me-1"></i> Descargar Imagen
                            </a>
                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- ========================================================== -->
    <!-- GESTIÓN DE VOTACIÓN POR DIGNIDADES / CANDIDATURAS          -->
    <!-- (Tabla DignidadActa asociada a este Acta)                  -->
    <!-- ========================================================== -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 border-bottom">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-box-seam-fill text-primary fs-5"></i>
                <h5 class="fw-bold text-dark mb-0">Votación por Dignidades / Candidaturas (<?= count($dignidadesEnActa) ?>)</h5>
            </div>
            <button type="button" class="btn btn-primary btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#modalAgregarVotoDignidad">
                <i class="bi bi-plus-circle me-1"></i> Registrar Voto de Candidato
            </button>
        </div>

        <div class="card-body p-0">
            <?php if (empty($dignidadesEnActa)): ?>
                <div class="text-center py-5 px-3 text-muted">
                    <i class="bi bi-box-seam fs-1 text-secondary opacity-50 mb-2 d-block"></i>
                    <h6 class="fw-bold text-dark mb-1">No hay votos de candidatos registrados en esta acta</h6>
                    <p class="small text-muted mb-3" style="max-width: 500px; margin: 0 auto;">
                        Puede ingresar la votación individual obtenida por cada candidato o dignidad en este acta (<code>idacta = <?= esc($currentActa['idacta']) ?></code>).
                    </p>
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalAgregarVotoDignidad">
                        <i class="bi bi-plus-circle me-1"></i> Añadir Primera Votación
                    </button>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 80px;">ID</th>
                                <th>Candidato / Persona</th>
                                <th>Dignidad / Cargo</th>
                                <th class="text-center">Votos Obtenidos</th>
                                <th class="text-center" style="width: 160px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dignidadesEnActa as $item): ?>
                                <?php
                                    $cedula = $item['persona_cedula'] ?? '';
                                    $idPersona = $item['idpersona'] ?? $item['persona_id'] ?? 0;
                                    $rutaFotoFisica = ROOTPATH . 'repositorio/fotos/' . $cedula . '.jpg';
                                    $tieneFoto = !empty($cedula) && file_exists($rutaFotoFisica);
                                    $urlFoto = $tieneFoto ? site_url('persona/foto/' . $idPersona) . '?v=' . filemtime($rutaFotoFisica) : null;
                                ?>
                                <tr>
                                    <td class="text-center fw-semibold text-secondary">#<?= esc($item['iddignidadacta']) ?></td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2.5">
                                            <?php if ($tieneFoto): ?>
                                                <img src="<?= $urlFoto ?>" 
                                                     alt="Foto de <?= esc($item['persona_nombre']) ?>" 
                                                     class="rounded-circle shadow-sm border border-2 border-white flex-shrink-0" 
                                                     style="width: 38px; height: 38px; object-fit: cover;">
                                            <?php else: ?>
                                                <div class="bg-primary-subtle text-primary rounded-circle p-2 d-inline-flex justify-content-center align-items-center flex-shrink-0" style="width: 38px; height: 38px; font-size: 0.9rem;">
                                                    <i class="bi bi-person-fill"></i>
                                                </div>
                                            <?php endif; ?>
                                            <div>
                                                <div class="fw-bold text-dark">
                                                    <?= esc(($item['persona_apellidos'] ?? '') . ' ' . ($item['persona_nombre'] ?? 'Sin Candidato')) ?>
                                                </div>
                                                <div class="small text-muted">Cédula: <?= esc($item['persona_cedula'] ?? 'N/A') ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1 fs-7">
                                            <i class="bi bi-award-fill me-1"></i><?= esc($item['tipodignidad_nombre'] ?? 'Sin Tipo') ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 fs-6 fw-bold">
                                            <i class="bi bi-check-circle-fill me-1"></i><?= number_format($item['votacion'], 0, ',', '.') ?> votos
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button type="button" 
                                                    class="btn btn-outline-warning" 
                                                    title="Editar votos de este candidato"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#modalEditarVoto<?= $item['iddignidadacta'] ?>">
                                                <i class="bi bi-pencil-square"></i> Editar
                                            </button>
                                            <a href="<?= site_url('dignidadacta/delete/' . $item['iddignidadacta'] . '?redirect_to_acta=1') ?>" 
                                               class="btn btn-outline-danger" 
                                               title="Eliminar registro de votos"
                                               onclick="return confirm('¿Está seguro de eliminar el conteo de votos de <?= esc($item['persona_apellidos']) ?> en este acta?');">
                                                <i class="bi bi-trash3"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot class="table-group-divider bg-light">
                            <tr>
                                <td colspan="3" class="text-end fw-bold text-dark py-3">Suma Total de Votos de Candidatos Registrados:</td>
                                <td class="text-center py-3">
                                    <span class="badge bg-primary fs-6 px-3 py-1.5 fw-bold">
                                        <?= number_format($totalVotosCandidatos, 0, ',', '.') ?> votos
                                    </span>
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Cuadro de Consistencia del Escrutinio -->
                <div class="p-3 bg-light border-top d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
                    <div class="small text-muted">
                        <i class="bi bi-calculator me-1"></i>
                        Votos válidos calculados del acta: <strong><?= number_format(max(0, $validos), 0, ',', '.') ?></strong> | Suma candidatos: <strong><?= number_format($totalVotosCandidatos, 0, ',', '.') ?></strong>
                    </div>
                    <div>
                        <?php if ($totalVotosCandidatos === max(0, $validos)): ?>
                            <span class="badge bg-success px-3 py-1.5 fs-7"><i class="bi bi-check-all me-1"></i> Escrutinio Cuadrado Exacto</span>
                        <?php else: ?>
                            <span class="badge bg-warning text-dark px-3 py-1.5 fs-7">
                                <i class="bi bi-exclamation-triangle me-1"></i> Diferencia de <?= abs(max(0, $validos) - $totalVotosCandidatos) ?> voto(s)
                            </span>
                        <?php endif; ?>
                    </div>
                </div>

            <?php endif; ?>
        </div>
    </div>

    <!-- ========================================================== -->
    <!-- MODAL PARA AGREGAR VOTACIÓN DE CANDIDATO AL ACTA           -->
    <!-- ========================================================== -->
    <div class="modal fade" id="modalAgregarVotoDignidad" tabindex="-1" aria-labelledby="modalAgregarVotoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="<?= site_url('dignidadacta/store') ?>" method="post">
                    <?= csrf_field() ?>
                    <input type="hidden" name="idacta" value="<?= esc($currentActa['idacta']) ?>">
                    <input type="hidden" name="redirect_to_acta" value="1">

                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title fw-bold" id="modalAgregarVotoLabel">
                            <i class="bi bi-plus-circle me-1"></i> Registrar Votación de Candidato
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>

                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label text-muted small mb-1">Acta Destino</label>
                            <div class="form-control bg-light fw-bold text-dark">
                                Acta #<?= esc($currentActa['idacta']) ?> - Mesa #<?= esc($currentActa['meza_numero'] ?? 'N/A') ?> (Recinto: <?= esc($currentActa['recinto_nombre'] ?? 'N/A') ?>)
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="modal_iddignidad" class="form-label fw-semibold">Seleccionar Candidatura / Dignidad <span class="text-danger">*</span></label>
                            <select name="iddignidad" id="modal_iddignidad" class="form-select" required>
                                <option value="">-- Seleccionar Candidato --</option>
                                <?php foreach ($todasLasDignidades as $d): ?>
                                    <option value="<?= esc($d['iddignidad']) ?>">
                                        <?= esc(($d['persona_apellidos'] ?? '') . ' ' . ($d['persona_nombre'] ?? 'Sin Nombre')) ?> - Cargo: <?= esc($d['tipodignidad_nombre'] ?? 'N/A') ?> (Cédula: <?= esc($d['persona_cedula'] ?? 'N/A') ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="modal_votacion" class="form-label fw-semibold">Número de Votos Obtenidos <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-success"><i class="bi bi-check-circle-fill"></i></span>
                                <input type="number" 
                                       name="votacion" 
                                       id="modal_votacion" 
                                       class="form-control" 
                                       placeholder="0" 
                                       min="0"
                                       step="1"
                                       value="0" 
                                       required>
                            </div>
                            <div class="form-text">Votos totales registrados para este candidato en el acta.</div>
                        </div>
                    </div>

                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary fw-semibold">
                            <i class="bi bi-save me-1"></i> Guardar Voto
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ========================================================== -->
    <!-- MODALES DE EDICIÓN RÁPIDA DE VOTACIÓN POR REGISTRO          -->
    <!-- ========================================================== -->
    <?php foreach ($dignidadesEnActa as $item): ?>
        <div class="modal fade" id="modalEditarVoto<?= $item['iddignidadacta'] ?>" tabindex="-1" aria-labelledby="modalEditarVotoLabel<?= $item['iddignidadacta'] ?>" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="<?= site_url('dignidadacta/update/' . $item['iddignidadacta']) ?>" method="post">
                        <?= csrf_field() ?>
                        <input type="hidden" name="idacta" value="<?= esc($currentActa['idacta']) ?>">
                        <input type="hidden" name="redirect_to_acta" value="1">

                        <div class="modal-header bg-warning text-dark">
                            <h5 class="modal-title fw-bold" id="modalEditarVotoLabel<?= $item['iddignidadacta'] ?>">
                                <i class="bi bi-pencil-square me-1"></i> Editar Votación del Candidato
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        </div>

                        <div class="modal-body p-4">
                            <div class="mb-3">
                                <label class="form-label text-muted small mb-1">Candidato / Dignidad</label>
                                <div class="form-control bg-light fw-bold text-dark">
                                    <?= esc(($item['persona_apellidos'] ?? '') . ' ' . ($item['persona_nombre'] ?? 'Sin Nombre')) ?>
                                    <span class="badge bg-primary-subtle text-primary ms-1"><?= esc($item['tipodignidad_nombre'] ?? 'N/A') ?></span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="edit_iddignidad_<?= $item['iddignidadacta'] ?>" class="form-label fw-semibold">Cambiar Candidato / Dignidad <span class="text-danger">*</span></label>
                                <select name="iddignidad" id="edit_iddignidad_<?= $item['iddignidadacta'] ?>" class="form-select" required>
                                    <?php foreach ($todasLasDignidades as $d): ?>
                                        <option value="<?= esc($d['iddignidad']) ?>" <?= ((string)$item['iddignidad'] === (string)$d['iddignidad']) ? 'selected' : '' ?>>
                                            <?= esc(($d['persona_apellidos'] ?? '') . ' ' . ($d['persona_nombre'] ?? 'Sin Nombre')) ?> - Cargo: <?= esc($d['tipodignidad_nombre'] ?? 'N/A') ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="edit_votacion_<?= $item['iddignidadacta'] ?>" class="form-label fw-semibold">Número de Votos Obtenidos <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-success"><i class="bi bi-check-circle-fill"></i></span>
                                    <input type="number" 
                                           name="votacion" 
                                           id="edit_votacion_<?= $item['iddignidadacta'] ?>" 
                                           class="form-control" 
                                           min="0"
                                           step="1"
                                           value="<?= esc($item['votacion']) ?>" 
                                           required>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer bg-light">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-warning text-dark fw-semibold">
                                <i class="bi bi-save me-1"></i> Actualizar Voto
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

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
        if (['INPUT', 'SELECT', 'TEXTAREA', 'BUTTON'].includes(document.activeElement.tagName) || document.querySelector('.modal.show')) {
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
