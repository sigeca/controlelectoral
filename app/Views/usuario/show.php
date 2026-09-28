<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-md-7 col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <i class="bi bi-person-badge-fill text-primary me-2 fs-5"></i>
                    <h5 class="mb-0">Ficha de Usuario de Sistema</h5>
                </div>
                <span class="badge bg-light text-dark border">ID #<?= esc($usuario['idusuario']) ?></span>
            </div>
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle mb-3 shadow-sm" style="width: 80px; height: 80px; font-size: 2.5rem;">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <h4 class="mb-1 text-dark fw-bold font-monospace">@<?= esc($usuario['usuario']) ?></h4>
                    <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle px-3 py-1 fs-6">
                        <i class="bi bi-shield-lock-fill me-1"></i><?= esc($usuario['rol_nombre'] ?? 'Sin Rol') ?>
                    </span>
                </div>

                <div class="list-group list-group-flush border-top border-bottom mb-4">
                    <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                        <span class="text-muted"><i class="bi bi-at me-2"></i>Username:</span>
                        <span class="fw-bold font-monospace"><?= esc($usuario['usuario']) ?></span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                        <span class="text-muted"><i class="bi bi-person-check me-2"></i>Persona Titular:</span>
                        <span class="fw-semibold text-dark">
                            <?= esc(($usuario['persona_apellidos'] ?? '') . ' ' . ($usuario['persona_nombre'] ?? '')) ?>
                        </span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                        <span class="text-muted"><i class="bi bi-card-heading me-2"></i>Cédula de Identidad:</span>
                        <span class="badge bg-light text-dark border font-monospace fs-6"><?= esc($usuario['cedula'] ?? 'N/A') ?></span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                        <span class="text-muted"><i class="bi bi-shield me-2"></i>Nivel / Rol:</span>
                        <span class="fw-semibold text-primary"><?= esc($usuario['rol_nombre'] ?? 'N/A') ?></span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                        <span class="text-muted"><i class="bi bi-key me-2"></i>Contraseña:</span>
                        <span class="badge bg-light text-muted border font-monospace">&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull; (Protegida)</span>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <a href="<?= site_url('usuario') ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Volver a Usuarios
                    </a>
                    <div class="btn-group">
                        <a href="<?= site_url('usuario/edit/' . $usuario['idusuario']) ?>" class="btn btn-warning text-dark">
                            <i class="bi bi-pencil-square me-1"></i> Editar
                        </a>
                        <a href="<?= site_url('usuario/delete/' . $usuario['idusuario']) ?>" 
                           class="btn btn-danger" 
                           onclick="return confirm('¿Está seguro de eliminar este usuario?');">
                            <i class="bi bi-trash3 me-1"></i> Eliminar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
