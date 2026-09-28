<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-7">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <i class="bi bi-pencil-square text-warning me-2 fs-5"></i>
                    <h5 class="mb-0">Editar Usuario: <?= esc($usuario['usuario']) ?></h5>
                </div>
                <a href="<?= site_url('usuario') ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Volver a la Lista
                </a>
            </div>
            <div class="card-body p-4">
                <form action="<?= site_url('usuario/update/' . $usuario['idusuario']) ?>" method="post" autocomplete="off">
                    <?= csrf_field() ?>

                    <div class="row g-3">
                        <!-- Persona Asociada -->
                        <div class="col-12">
                            <label for="idpersona" class="form-label fw-semibold">Persona Titular <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <select name="idpersona" 
                                        id="idpersona" 
                                        class="form-select <?= isset($errors['idpersona']) ? 'is-invalid' : '' ?>" 
                                        required>
                                    <option value="">-- Seleccionar Persona --</option>
                                    <?php foreach ($personas as $p): ?>
                                        <option value="<?= esc($p['idpersona']) ?>" 
                                            <?= (string)old('idpersona', $usuario['idpersona']) === (string)$p['idpersona'] ? 'selected' : '' ?>>
                                            <?= esc($p['apellidos'] . ' ' . $p['nombre']) ?> (Cédula: <?= esc($p['cedula']) ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if (isset($errors['idpersona'])): ?>
                                    <div class="invalid-feedback">
                                        <?= esc($errors['idpersona']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Nombre de Usuario -->
                        <div class="col-md-6">
                            <label for="usuario" class="form-label fw-semibold">Nombre de Usuario (Username) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-at"></i></span>
                                <input type="text" 
                                       name="usuario" 
                                       id="usuario" 
                                       class="form-control <?= isset($errors['usuario']) ? 'is-invalid' : '' ?>" 
                                       placeholder="Ej: jperez" 
                                       maxlength="20"
                                       value="<?= esc(old('usuario', $usuario['usuario'])) ?>" 
                                       required>
                                <?php if (isset($errors['usuario'])): ?>
                                    <div class="invalid-feedback">
                                        <?= esc($errors['usuario']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="form-text">Máximo 20 caracteres.</div>
                        </div>

                        <!-- Rol de Usuario -->
                        <div class="col-md-6">
                            <label for="idrolusuario" class="form-label fw-semibold">Rol del Sistema <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-shield-lock"></i></span>
                                <select name="idrolusuario" 
                                        id="idrolusuario" 
                                        class="form-select <?= isset($errors['idrolusuario']) ? 'is-invalid' : '' ?>" 
                                        required>
                                    <option value="">-- Seleccionar Rol --</option>
                                    <?php foreach ($roles as $r): ?>
                                        <option value="<?= esc($r['idrolusuario']) ?>" 
                                            <?= (string)old('idrolusuario', $usuario['idrolusuario']) === (string)$r['idrolusuario'] ? 'selected' : '' ?>>
                                            <?= esc($r['nombre']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if (isset($errors['idrolusuario'])): ?>
                                    <div class="invalid-feedback">
                                        <?= esc($errors['idrolusuario']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Contraseña (Opcional en edición) -->
                        <div class="col-md-12">
                            <label for="password" class="form-label fw-semibold">Nueva Contraseña <span class="text-muted fw-normal">(opcional)</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-key"></i></span>
                                <input type="password" 
                                       name="password" 
                                       id="password" 
                                       class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" 
                                       placeholder="Dejar vacío para conservar la contraseña actual" 
                                       maxlength="50">
                                <button class="btn btn-outline-secondary" type="button" id="btnTogglePassword" title="Mostrar/Ocultar Contraseña">
                                    <i class="bi bi-eye" id="iconToggle"></i>
                                </button>
                                <?php if (isset($errors['password'])): ?>
                                    <div class="invalid-feedback">
                                        <?= esc($errors['password']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="form-text">Si no desea cambiar la clave actual, deje este campo en blanco.</div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                        <a href="<?= site_url('usuario') ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-1"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-warning text-dark fw-semibold">
                            <i class="bi bi-check2-circle me-1"></i> Actualizar Usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.getElementById('btnTogglePassword')?.addEventListener('click', function() {
        const input = document.getElementById('password');
        const icon = document.getElementById('iconToggle');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        }
    });
</script>
<?= $this->endSection() ?>
