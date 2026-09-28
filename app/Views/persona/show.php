<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<?php
    $edad = '';
    if (!empty($persona['fechanacimiento'])) {
        $nac = new DateTime($persona['fechanacimiento']);
        $hoy = new DateTime();
        $diff = $hoy->diff($nac);
        $edad = $diff->y . ' años, ' . $diff->m . ' meses';
    }
?>

<div class="row justify-content-center">
    <div class="col-md-7 col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <i class="bi bi-person-badge-fill text-info me-2 fs-5"></i>
                    <h5 class="mb-0">Ficha de Información Electoral</h5>
                </div>
                <span class="badge bg-light text-dark border">ID #<?= esc($persona['idpersona']) ?></span>
            </div>
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center bg-primary-subtle text-primary rounded-circle mb-3" style="width: 80px; height: 80px; font-size: 2.5rem;">
                        <i class="bi bi-person"></i>
                    </div>
                    <h4 class="mb-1 text-dark fw-bold"><?= esc($persona['nombre'] . ' ' . $persona['apellidos']) ?></h4>
                    <p class="text-muted mb-0">Cédula de Identidad: <strong><?= esc($persona['cedula']) ?></strong></p>
                </div>

                <div class="list-group list-group-flush border-top border-bottom mb-4">
                    <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                        <span class="text-muted"><i class="bi bi-card-text me-2"></i>Cédula:</span>
                        <span class="fw-bold font-monospace fs-6"><?= esc($persona['cedula']) ?></span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                        <span class="text-muted"><i class="bi bi-person me-2"></i>Nombres:</span>
                        <span class="fw-semibold"><?= esc($persona['nombre']) ?></span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                        <span class="text-muted"><i class="bi bi-person me-2"></i>Apellidos:</span>
                        <span class="fw-semibold"><?= esc($persona['apellidos']) ?></span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                        <span class="text-muted"><i class="bi bi-calendar3 me-2"></i>Fecha de Nacimiento:</span>
                        <span><?= date('d/m/Y', strtotime($persona['fechanacimiento'])) ?></span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                        <span class="text-muted"><i class="bi bi-hourglass-split me-2"></i>Edad:</span>
                        <span class="badge bg-secondary-subtle text-secondary border"><?= $edad ?></span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                        <span class="text-muted"><i class="bi bi-gender-ambiguous me-2"></i>Sexo:</span>
                        <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle px-3 py-1">
                            <?= esc($persona['sexo_nombre'] ?? 'No asignado') ?>
                        </span>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <a href="<?= site_url('persona') ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Volver
                    </a>
                    <div class="btn-group">
                        <a href="<?= site_url('persona/edit/' . $persona['idpersona']) ?>" class="btn btn-warning text-dark">
                            <i class="bi bi-pencil-square me-1"></i> Editar
                        </a>
                        <a href="<?= site_url('persona/delete/' . $persona['idpersona']) ?>" 
                           class="btn btn-danger" 
                           onclick="return confirm('¿Está seguro de eliminar esta persona?');">
                            <i class="bi bi-trash3 me-1"></i> Eliminar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
