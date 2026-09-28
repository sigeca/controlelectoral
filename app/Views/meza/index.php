<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Título y Descripción del Módulo -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">
    <div>
        <h2 class="h3 mb-0 text-gray-800">
            <i class="bi bi-inbox-fill me-2 text-primary"></i>Mesa Electoral (Navegador Individual)
        </h2>
        <p class="text-muted small mb-0">Visualización registro por registro con barra de navegación y operaciones CRUD.</p>
    </div>
    <?php if ($total > 0): ?>
        <div class="d-flex align-items-center gap-2">
            <label for="jumpMezaSelect" class="form-label small mb-0 text-muted d-none d-sm-inline">Ir a:</label>
            <select id="jumpMezaSelect" class="form-select form-select-sm" style="min-width: 190px;" onchange="if(this.value) window.location.href=this.value;">
                <?php foreach ($allIds as $idx => $id): ?>
                    <option value="<?= site_url('meza/ver/' . $id) ?>" <?= ($idx === $currentIndex) ? 'selected' : '' ?>>
                        Registro <?= ($idx + 1) ?> de <?= $total ?> (Mesa ID #<?= $id ?>)
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
            <div class="btn-group shadow-sm" role="group" aria-label="Navegación de registros">
                <!-- Primer -->
                <a href="<?= ($firstId && $currentIndex > 0) ? site_url('meza/ver/' . $firstId) : '#' ?>" 
                   class="btn btn-outline-primary btn-sm d-inline-flex align-items-center justify-content-center <?= ($currentIndex === 0 || $total === 0) ? 'disabled' : '' ?>"
                   title="Primer Registro (Inicio)">
                    <i class="bi bi-chevron-double-left me-1"></i>
                    <span class="d-none d-sm-inline">Primer</span>
                </a>

                <!-- Anterior -->
                <a href="<?= ($prevId !== null) ? site_url('meza/ver/' . $prevId) : '#' ?>" 
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
                <a href="<?= ($nextId !== null) ? site_url('meza/ver/' . $nextId) : '#' ?>" 
                   class="btn btn-outline-primary btn-sm d-inline-flex align-items-center justify-content-center <?= ($nextId === null) ? 'disabled' : '' ?>"
                   title="Registro Siguiente (Flecha Derecha)" id="btnSiguiente">
                    <span>Siguiente</span>
                    <i class="bi bi-chevron-right ms-1"></i>
                </a>

                <!-- Último -->
                <a href="<?= ($lastId && $currentIndex < $total - 1) ? site_url('meza/ver/' . $lastId) : '#' ?>" 
                   class="btn btn-outline-primary btn-sm d-inline-flex align-items-center justify-content-center <?= ($currentIndex >= $total - 1 || $total === 0) ? 'disabled' : '' ?>"
                   title="Último Registro (Fin)">
                    <span class="d-none d-sm-inline">Último</span>
                    <i class="bi bi-chevron-double-right ms-1"></i>
                </a>
            </div>

            <!-- Grupo de Acciones: Nuevo, Editar, Borrar, Listar -->
            <div class="btn-group shadow-sm" role="group" aria-label="Acciones de registro">
                <!-- Nuevo -->
                <a href="<?= site_url('meza/create') ?>" class="btn btn-success btn-sm d-inline-flex align-items-center justify-content-center" title="Registrar Nueva Mesa">
                    <i class="bi bi-plus-circle me-1"></i>
                    <span>Nuevo</span>
                </a>

                <!-- Editar -->
                <?php if ($currentMeza): ?>
                    <a href="<?= site_url('meza/edit/' . $currentMeza['idmeza']) ?>" class="btn btn-warning btn-sm text-dark d-inline-flex align-items-center justify-content-center" title="Editar este Registro">
                        <i class="bi bi-pencil-square me-1"></i>
                        <span>Editar</span>
                    </a>
                <?php else: ?>
                    <button class="btn btn-outline-secondary btn-sm" disabled>
                        <i class="bi bi-pencil-square me-1"></i> Editar
                    </button>
                <?php endif; ?>

                <!-- Borrar -->
                <?php if ($currentMeza): ?>
                    <a href="<?= site_url('meza/delete/' . $currentMeza['idmeza']) ?>" 
                       class="btn btn-danger btn-sm d-inline-flex align-items-center justify-content-center" 
                       title="Eliminar este Registro"
                       onclick="return confirm('¿Está seguro de eliminar la Mesa #<?= esc($currentMeza['numero']) ?> (ID: <?= esc($currentMeza['idmeza']) ?>)?');">
                        <i class="bi bi-trash3 me-1"></i>
                        <span>Borrar</span>
                    </a>
                <?php else: ?>
                    <button class="btn btn-outline-secondary btn-sm" disabled>
                        <i class="bi bi-trash3 me-1"></i> Borrar
                    </button>
                <?php endif; ?>

                <!-- Listar -->
                <a href="<?= site_url('meza/listar') ?>" class="btn btn-info btn-sm text-white d-inline-flex align-items-center justify-content-center" title="Ver Listado Completo en Tabla">
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
<?php if ($currentMeza): ?>
    <?php
        $sexoLower = strtolower($currentMeza['sexo_nombre'] ?? '');
        $badgeClass = 'bg-secondary-subtle text-secondary border';
        $badgeColor = '#0d6efd';
        $iconoSexo = 'bi-gender-ambiguous';
        if (str_contains($sexoLower, 'masc')) {
            $badgeClass = 'bg-info-subtle text-info-emphasis border border-info-subtle';
            $badgeColor = '#0dcaf0';
            $iconoSexo = 'bi-gender-male';
        } elseif (str_contains($sexoLower, 'fem')) {
            $badgeClass = 'bg-danger-subtle text-danger-emphasis border border-danger-subtle';
            $badgeColor = '#d63384';
            $iconoSexo = 'bi-gender-female';
        }

        // Verificación de Acta de Escrutinio en repositorio/actaescrutinio/{idmeza}.jpg
        $rutaActaFisica = ROOTPATH . 'repositorio/actaescrutinio/' . $currentMeza['idmeza'] . '.jpg';
        $tieneActa = file_exists($rutaActaFisica);
        $urlActa = $tieneActa ? site_url('meza/acta/' . $currentMeza['idmeza']) . '?v=' . filemtime($rutaActaFisica) : null;

        // Suma total de papeletas contadas en esta mesa como respaldo de evidencia
        $totalPapeletasContadas = 0;
        foreach ($papeletas as $p) {
            $totalPapeletasContadas += (int)($p['numeropapeleta'] ?? 0);
        }
    ?>

    <div class="row">
        <!-- Tarjeta Principal con Información de la Mesa -->
        <div class="col-lg-7 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-primary text-white px-2 py-1 fs-6">
                            <i class="bi bi-inbox-fill me-1"></i> Mesa #<?= esc($currentMeza['numero']) ?>
                        </span>
                        <span class="badge bg-light text-secondary border">
                            ID: #<?= esc($currentMeza['idmeza']) ?>
                        </span>
                        <?php if ($tieneActa): ?>
                            <a href="#seccionActa" class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 text-decoration-none">
                                <i class="bi bi-file-earmark-check-fill me-1"></i>Con Acta
                            </a>
                        <?php else: ?>
                            <a href="#seccionActa" class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-1 text-decoration-none">
                                <i class="bi bi-file-earmark-x me-1"></i>Sin Acta
                            </a>
                        <?php endif; ?>
                    </div>
                    <span class="badge <?= $badgeClass ?> px-3 py-1 fs-7">
                        <i class="bi <?= $iconoSexo ?> me-1"></i><?= esc($currentMeza['sexo_nombre'] ?? 'Sin Género') ?>
                    </span>
                </div>

                <div class="card-body p-4">
                    <!-- Cabecera visual del registro -->
                    <div class="d-flex align-items-center gap-3 p-3 bg-light rounded-3 border mb-4">
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-sm" 
                             style="width: 64px; height: 64px; font-size: 1.75rem; background: linear-gradient(135deg, #0d6efd, #0b5ed7);">
                            <?= esc($currentMeza['numero']) ?>
                        </div>
                        <div>
                            <h4 class="mb-1 text-dark fw-bold">Junta Receptora del Voto N° <?= esc($currentMeza['numero']) ?></h4>
                            <p class="text-muted small mb-0">
                                <i class="bi bi-geo-alt-fill text-danger me-1"></i>
                                <?= esc($currentMeza['recinto_nombre'] ?? 'Sin Recinto') ?> &bull; 
                                <span class="fw-semibold text-dark"><?= esc($currentMeza['canton_nombre'] ?? 'N/A') ?>, <?= esc($currentMeza['provincia_nombre'] ?? 'N/A') ?></span>
                            </p>
                        </div>
                    </div>

                    <!-- Campos del registro -->
                    <div class="list-group list-group-flush rounded-3 border">
                        <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <span class="text-muted fw-semibold">
                                <i class="bi bi-hash text-primary me-2"></i>Identificador Interno (ID):
                            </span>
                            <span class="fw-bold font-monospace fs-6">#<?= esc($currentMeza['idmeza']) ?></span>
                        </div>

                        <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <span class="text-muted fw-semibold">
                                <i class="bi bi-123 text-primary me-2"></i>Número de Mesa:
                            </span>
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1 fs-6 fw-bold">
                                Mesa <?= esc($currentMeza['numero']) ?>
                            </span>
                        </div>

                        <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <span class="text-muted fw-semibold">
                                <i class="bi <?= $iconoSexo ?> text-primary me-2"></i>Género Asignado:
                            </span>
                            <span class="badge <?= $badgeClass ?> px-3 py-1 fs-6">
                                <i class="bi <?= $iconoSexo ?> me-1"></i><?= esc($currentMeza['sexo_nombre'] ?? 'No Asignado') ?>
                            </span>
                        </div>

                        <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <span class="text-muted fw-semibold">
                                <i class="bi bi-building text-primary me-2"></i>Recinto Electoral:
                            </span>
                            <div class="text-end">
                                <span class="fw-bold text-dark d-block"><?= esc($currentMeza['recinto_nombre'] ?? 'No Asignado') ?></span>
                                <?php if (!empty($currentMeza['recinto_electores'])): ?>
                                    <small class="text-muted"><?= number_format((int)$currentMeza['recinto_electores']) ?> electores empadronados</small>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <span class="text-muted fw-semibold">
                                <i class="bi bi-pin-map text-primary me-2"></i>Zona Electoral:
                            </span>
                            <span class="fw-semibold text-dark"><?= esc($currentMeza['zona_nombre'] ?? 'N/A') ?></span>
                        </div>

                        <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <span class="text-muted fw-semibold">
                                <i class="bi bi-geo text-primary me-2"></i>Parroquia:
                            </span>
                            <span class="fw-semibold text-dark"><?= esc($currentMeza['parroquia_nombre'] ?? 'N/A') ?></span>
                        </div>

                        <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <span class="text-muted fw-semibold">
                                <i class="bi bi-buildings text-primary me-2"></i>Cantón y Provincia:
                            </span>
                            <span class="badge bg-light text-dark border px-2 py-1 fs-7">
                                <?= esc($currentMeza['canton_nombre'] ?? 'N/A') ?> &bull; <?= esc($currentMeza['provincia_nombre'] ?? 'N/A') ?>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-white border-top py-3 d-flex justify-content-between align-items-center">
                    <span class="small text-muted">
                        <i class="bi bi-info-circle me-1"></i> Navegación activa: registro <?= ($currentIndex + 1) ?> de <?= $total ?>.
                    </span>
                    <div class="d-flex gap-2">
                        <a href="<?= site_url('meza/edit/' . $currentMeza['idmeza']) ?>" class="btn btn-sm btn-outline-warning text-dark">
                            <i class="bi bi-pencil-square me-1"></i> Modificar
                        </a>
                        <a href="<?= site_url('meza/listar') ?>" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-list-ul me-1"></i> Ver Todas
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tarjeta Secundaria: Dignidades a Elegir en esta Mesa -->
        <div class="col-lg-5 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                    <div class="fw-bold text-dark d-flex align-items-center gap-2">
                        <i class="bi bi-ui-checks text-primary"></i>
                        <span>Dignidades a Elegir en esta Mesa</span>
                    </div>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">
                        <?= count($papeletas) ?> dignidad(es)
                    </span>
                </div>

                <div class="card-body p-0">
                    <?php if (empty($papeletas)): ?>
                        <div class="text-center py-4 px-3 text-muted">
                            <i class="bi bi-card-checklist fs-1 text-secondary opacity-50 mb-2 d-block"></i>
                            <p class="mb-2 fw-semibold">No hay dignidades a elegir asignadas a esta mesa.</p>
                            <p class="small text-muted mb-3">Puede vincular candidatos y el conteo de papeletas a través del módulo de dignidades por mesa.</p>
                            <a href="<?= site_url('mezadignidad/create') ?>" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-plus-circle me-1"></i> Asignar Dignidad
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($papeletas as $p): ?>
                                <?php 
                                    $cedula = $p['persona_cedula'] ?? '';
                                    $rutaFotoFisica = ROOTPATH . 'repositorio/fotos/' . $cedula . '.jpg';
                                    $tieneFoto = !empty($cedula) && file_exists($rutaFotoFisica);
                                    $urlFoto = $tieneFoto ? site_url('persona/foto/' . $p['idpersona']) . '?v=' . filemtime($rutaFotoFisica) : null;
                                ?>
                                <div class="list-group-item p-3 d-flex align-items-center justify-content-between gap-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <!-- Foto de la dignidad / candidatura -->
                                        <div class="position-relative flex-shrink-0">
                                            <?php if ($tieneFoto): ?>
                                                <a href="<?= $urlFoto ?>" target="_blank" title="Ver foto de <?= esc($p['persona_nombre']) ?>" class="d-block">
                                                    <img src="<?= $urlFoto ?>" 
                                                         alt="Foto de <?= esc($p['persona_nombre']) ?>" 
                                                         class="rounded-circle border border-2 border-white shadow-sm" 
                                                         style="width: 52px; height: 52px; object-fit: cover; box-shadow: 0 2px 6px rgba(0,0,0,0.15) !important;">
                                                </a>
                                            <?php else: ?>
                                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-sm" 
                                                     style="width: 52px; height: 52px; font-size: 1.3rem; background: linear-gradient(135deg, #0d6efd, #0b5ed7);">
                                                    <?= mb_strtoupper(mb_substr($p['persona_nombre'] ?? 'C', 0, 1) . mb_substr($p['persona_apellidos'] ?? '', 0, 1)) ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Información del candidato y cargo -->
                                        <div>
                                            <div class="fw-bold text-dark fs-6 mb-1">
                                                <?= esc($p['persona_nombre'] . ' ' . $p['persona_apellidos']) ?>
                                            </div>
                                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                                <span class="badge bg-warning-subtle text-dark border border-warning-subtle px-2 py-0 fw-semibold" style="font-size: 0.76rem;">
                                                    <i class="bi bi-award-fill text-warning me-1"></i><?= esc($p['tipodignidad_nombre'] ?? 'Sin cargo') ?>
                                                </span>
                                                <span class="text-muted small" style="font-size: 0.78rem;">
                                                    <i class="bi bi-card-text me-1"></i>CI: <code class="text-secondary"><?= esc($p['persona_cedula'] ?? 'N/A') ?></code>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Papeletas contadas y acciones -->
                                    <div class="text-end d-flex flex-column align-items-end gap-2 flex-shrink-0">
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 font-monospace fw-bold" title="Cantidad de papeletas contadas">
                                            <i class="bi bi-file-earmark-check me-1"></i><?= number_format((int)$p['numeropapeleta']) ?> papeletas
                                        </span>
                                        <div class="btn-group btn-group-sm">
                                            <a href="<?= site_url('dignidad?id=' . $p['iddignidad']) ?>" class="btn btn-outline-primary btn-sm px-2 py-0" title="Ver ficha de candidatura">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="<?= site_url('mezadignidad/edit/' . $p['idmezadignidad']) ?>" class="btn btn-outline-secondary btn-sm px-2 py-0" title="Editar dignidad en mesa">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="card-footer bg-white border-top py-2 d-flex justify-content-between align-items-center">
                    <a href="<?= site_url('mezadignidad') ?>" class="btn btn-sm btn-link text-decoration-none px-0">
                        <i class="bi bi-box-arrow-up-right me-1"></i> Dignidades por Mesa
                    </a>
                    <a href="<?= site_url('mezadignidad/create') ?>" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-plus-circle me-1"></i> Asignar Dignidad
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================================== -->
    <!-- SECCIÓN DE EVIDENCIA: ACTA DE ESCRUTINIO DE LA MESA         -->
    <!-- ========================================================== -->
    <div class="row" id="seccionActa">
        <div class="col-12 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 border-bottom">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="bi bi-file-earmark-ruled-fill fs-5"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 text-dark fw-bold">Acta de Escrutinio (Evidencia Oficial de Votos)</h5>
                            <small class="text-muted">Respaldo fotográfico y digitalizado de los votos escrutados en la Junta N° <?= esc($currentMeza['numero']) ?></small>
                        </div>
                    </div>
                    <div>
                        <?php if ($tieneActa): ?>
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 fs-7 fw-bold">
                                <i class="bi bi-check-circle-fill me-1"></i>Acta Digitalizada Registrada
                            </span>
                        <?php else: ?>
                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-3 py-2 fs-7 fw-semibold">
                                <i class="bi bi-exclamation-triangle-fill me-1"></i>Pendiente de Digitalizar
                            </span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="card-body p-4">
                    <div class="row g-4 align-items-center">
                        <!-- Columna Izquierda: Formulario de Carga y Resumen de Votos -->
                        <div class="col-lg-5">
                            <!-- Formulario de subida de acta -->
                            <div class="p-3 bg-light rounded-3 border mb-3">
                                <h6 class="fw-bold text-dark mb-2 d-flex align-items-center gap-2">
                                    <i class="bi bi-cloud-arrow-up text-primary fs-5"></i>
                                    <span><?= $tieneActa ? 'Reemplazar Acta Digitalizada' : 'Subir Imagen del Acta' ?></span>
                                </h6>
                                <p class="text-muted small mb-3">
                                    Cargue la fotografía o escaneo del acta oficial de escrutinio. Se guardará automáticamente como <strong><?= esc($currentMeza['idmeza']) ?>.jpg</strong> en el directorio <code>repositorio/actaescrutinio/</code>.
                                </p>

                                <form action="<?= site_url('meza/subirActa/' . $currentMeza['idmeza']) ?>" 
                                      method="post" 
                                      enctype="multipart/form-data">
                                    <?= csrf_field() ?>
                                    <div class="mb-3">
                                        <label for="inputActaMeza" class="form-label small fw-semibold text-secondary">
                                            Seleccionar Archivo de Imagen:
                                        </label>
                                        <input type="file" 
                                               name="acta" 
                                               id="inputActaMeza" 
                                               class="form-control form-control-sm" 
                                               accept=".jpg,.jpeg,.png,.webp,image/*" 
                                               required>
                                        <div class="form-text small" style="font-size: 0.76rem;">
                                            Formatos admitidos: JPG, PNG, WEBP (hasta 8MB).
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm w-100 py-2 fw-semibold d-flex align-items-center justify-content-center gap-1">
                                        <i class="bi bi-cloud-arrow-up-fill me-1"></i>
                                        <?= $tieneActa ? 'Actualizar / Reemplazar Acta' : 'Guardar y Vincular Acta' ?>
                                    </button>
                                </form>
                            </div>

                            <!-- Respaldo de Votos Obtenidos -->
                            <div class="card border border-primary-subtle bg-primary-subtle bg-opacity-10 mb-3">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="small fw-bold text-primary">
                                            <i class="bi bi-shield-check me-1"></i>Votos Respaldados en Acta:
                                        </span>
                                        <span class="badge bg-primary text-white font-monospace fs-7 fw-bold">
                                            <i class="bi bi-file-earmark-check me-1"></i><?= number_format($totalPapeletasContadas) ?> papeletas
                                        </span>
                                    </div>
                                    <div class="small text-muted" style="font-size: 0.8rem;">
                                        Total acumulado en <?= count($papeletas) ?> dignidad(es) registradas en esta junta receptora del voto. Esta imagen constituye la evidencia física legal del escrutinio.
                                    </div>
                                </div>
                            </div>

                            <!-- Acciones adicionales cuando existe el acta -->
                            <?php if ($tieneActa): ?>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-outline-primary btn-sm flex-fill" data-bs-toggle="modal" data-bs-target="#modalVerActa">
                                        <i class="bi bi-zoom-in me-1"></i> Ver en Detalle
                                    </button>
                                    <a href="<?= $urlActa ?>" target="_blank" class="btn btn-outline-secondary btn-sm" title="Abrir imagen en pestaña nueva">
                                        <i class="bi bi-box-arrow-up-right me-1"></i> Abrir
                                    </a>
                                    <a href="<?= site_url('meza/eliminarActa/' . $currentMeza['idmeza']) ?>" 
                                       class="btn btn-outline-danger btn-sm" 
                                       title="Eliminar acta del repositorio"
                                       onclick="return confirm('¿Está seguro de eliminar el acta de escrutinio de la Mesa <?= esc($currentMeza['numero']) ?> (<?= esc($currentMeza['idmeza']) ?>.jpg)?');">
                                        <i class="bi bi-trash3 me-1"></i> Eliminar
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Columna Derecha: Visor de Imagen / Evidencia -->
                        <div class="col-lg-7">
                            <?php if ($tieneActa): ?>
                                <div class="position-relative text-center bg-dark bg-opacity-10 p-2 rounded-3 border">
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#modalVerActa" class="d-block text-decoration-none" title="Clic para ampliar el acta oficial">
                                        <img src="<?= $urlActa ?>" 
                                             alt="Acta de Escrutinio Mesa <?= esc($currentMeza['numero']) ?>" 
                                             class="img-fluid rounded shadow-sm" 
                                             style="max-height: 440px; object-fit: contain; width: 100%; background: #ffffff;">
                                        <div class="position-absolute bottom-0 start-50 translate-middle-x mb-3">
                                            <span class="badge bg-dark bg-opacity-75 text-white px-3 py-2 shadow border border-light border-opacity-25">
                                                <i class="bi bi-zoom-in me-1"></i> Clic para Ampliar Acta Oficial
                                            </span>
                                        </div>
                                    </a>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-5 px-3 bg-light rounded-3 border border-dashed">
                                    <div class="rounded-circle bg-secondary bg-opacity-10 text-secondary p-3 d-inline-flex justify-content-center align-items-center mb-3" style="width: 72px; height: 72px;">
                                        <i class="bi bi-file-earmark-ruled fs-1 opacity-75"></i>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1">Sin Acta de Escrutinio Digitalizada</h6>
                                    <p class="text-muted small mb-0" style="max-width: 420px; margin: 0 auto;">
                                        Utilice el formulario de la izquierda para subir la fotografía del acta de escrutinio de la <strong>Mesa N° <?= esc($currentMeza['numero']) ?></strong> como evidencia de los votos obtenidos.
                                    </p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Visualizar Acta en Tamaño Completo -->
    <?php if ($tieneActa): ?>
        <div class="modal fade" id="modalVerActa" tabindex="-1" aria-labelledby="modalVerActaLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header bg-dark text-white py-3">
                        <h5 class="modal-title d-flex align-items-center gap-2" id="modalVerActaLabel">
                            <i class="bi bi-file-earmark-ruled-fill text-warning"></i>
                            <span>Acta Oficial de Escrutinio &bull; Mesa N° <?= esc($currentMeza['numero']) ?> (<?= esc($currentMeza['sexo_nombre']) ?>)</span>
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body text-center p-3 bg-secondary bg-opacity-10">
                        <img src="<?= $urlActa ?>" 
                             class="img-fluid rounded shadow" 
                             alt="Acta Oficial Mesa <?= esc($currentMeza['numero']) ?>"
                             style="max-height: 80vh; object-fit: contain;">
                    </div>
                    <div class="modal-footer justify-content-between bg-light py-2">
                        <div class="small text-muted">
                            <i class="bi bi-folder-check text-primary me-1"></i>
                            Archivo: <code>repositorio/actaescrutinio/<?= esc($currentMeza['idmeza']) ?>.jpg</code>
                            &bull; <span class="fw-semibold text-dark">Recinto: <?= esc($currentMeza['recinto_nombre']) ?></span>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="<?= $urlActa ?>" target="_blank" download="Acta_Mesa_<?= esc($currentMeza['numero']) ?>.jpg" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-download me-1"></i> Descargar Imagen
                            </a>
                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

<?php else: ?>
    <!-- Estado Vacío cuando no existen registros -->
    <div class="card shadow-sm border-0 py-5 text-center">
        <div class="card-body">
            <i class="bi bi-inbox fs-1 d-block mb-3 text-secondary"></i>
            <h4 class="text-dark fw-bold">No existen mesas electorales registradas</h4>
            <p class="text-muted mb-4">Actualmente la base de datos no contiene juntas receptoras del voto.</p>
            <a href="<?= site_url('meza/create') ?>" class="btn btn-primary px-4 py-2">
                <i class="bi bi-plus-circle me-1"></i> Registrar la Primera Mesa
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
