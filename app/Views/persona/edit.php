<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-7">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <i class="bi bi-pencil-square text-warning me-2 fs-5"></i>
                    <h5 class="mb-0">Editar Persona #<?= esc($persona['idpersona']) ?></h5>
                </div>
                <a href="<?= site_url('persona/ver/' . $persona['idpersona']) ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Volver
                </a>
            </div>
            <div class="card-body p-4">
                <form action="<?= site_url('persona/update/' . $persona['idpersona']) ?>" method="post" autocomplete="off">
                    <?= csrf_field() ?>

                    <div class="row g-3">
                        <!-- Cédula -->
                        <div class="col-md-6">
                            <label for="cedula" class="form-label fw-semibold">Número de Cédula <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-card-heading"></i></span>
                                <input type="text" 
                                       name="cedula" 
                                       id="cedula" 
                                       class="form-control <?= isset($errors['cedula']) ? 'is-invalid' : '' ?>" 
                                       placeholder="Ej: 0801234567" 
                                       maxlength="15"
                                       value="<?= esc(old('cedula', $persona['cedula'])) ?>" 
                                       required>
                                <?php if (isset($errors['cedula'])): ?>
                                    <div class="invalid-feedback">
                                        <?= esc($errors['cedula']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="form-text">Máximo 15 caracteres.</div>
                        </div>

                        <!-- Sexo -->
                        <div class="col-md-6">
                            <label for="idsexo" class="form-label fw-semibold">Sexo <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-gender-ambiguous"></i></span>
                                <select name="idsexo" 
                                        id="idsexo" 
                                        class="form-select <?= isset($errors['idsexo']) ? 'is-invalid' : '' ?>" 
                                        required>
                                    <option value="">-- Seleccionar Sexo --</option>
                                    <?php foreach ($sexos as $s): ?>
                                        <option value="<?= esc($s['idsexo']) ?>" 
                                            <?= (string)old('idsexo', $persona['idsexo']) === (string)$s['idsexo'] ? 'selected' : '' ?>>
                                            <?= esc($s['nombre']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if (isset($errors['idsexo'])): ?>
                                    <div class="invalid-feedback">
                                        <?= esc($errors['idsexo']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="form-text">Clave foránea hacia <code>sexo</code>.</div>
                        </div>

                        <!-- Nombres -->
                        <div class="col-md-6">
                            <label for="nombre" class="form-label fw-semibold">Nombres <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" 
                                       name="nombre" 
                                       id="nombre" 
                                       class="form-control <?= isset($errors['nombre']) ? 'is-invalid' : '' ?>" 
                                       placeholder="Ej: Juan Carlos" 
                                       maxlength="50"
                                       value="<?= esc(old('nombre', $persona['nombre'])) ?>" 
                                       required>
                                <?php if (isset($errors['nombre'])): ?>
                                    <div class="invalid-feedback">
                                        <?= esc($errors['nombre']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="form-text">Máximo 50 caracteres.</div>
                        </div>

                        <!-- Apellidos -->
                        <div class="col-md-6">
                            <label for="apellidos" class="form-label fw-semibold">Apellidos <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" 
                                       name="apellidos" 
                                       id="apellidos" 
                                       class="form-control <?= isset($errors['apellidos']) ? 'is-invalid' : '' ?>" 
                                       placeholder="Ej: Pérez Rodríguez" 
                                       maxlength="50"
                                       value="<?= esc(old('apellidos', $persona['apellidos'])) ?>" 
                                       required>
                                <?php if (isset($errors['apellidos'])): ?>
                                    <div class="invalid-feedback">
                                        <?= esc($errors['apellidos']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="form-text">Máximo 50 caracteres.</div>
                        </div>

                        <!-- Fecha de Nacimiento -->
                        <div class="col-md-6">
                            <label for="fechanacimiento" class="form-label fw-semibold">Fecha de Nacimiento <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-calendar-date"></i></span>
                                <input type="date" 
                                       name="fechanacimiento" 
                                       id="fechanacimiento" 
                                       class="form-control <?= isset($errors['fechanacimiento']) ? 'is-invalid' : '' ?>" 
                                       value="<?= esc(old('fechanacimiento', $persona['fechanacimiento'])) ?>" 
                                       max="<?= date('Y-m-d') ?>"
                                       required>
                                <?php if (isset($errors['fechanacimiento'])): ?>
                                    <div class="invalid-feedback">
                                        <?= esc($errors['fechanacimiento']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="form-text">Formato: AAAA-MM-DD.</div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                        <a href="<?= site_url('persona/ver/' . $persona['idpersona']) ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-1"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-warning text-dark fw-semibold">
                            <i class="bi bi-check2-circle me-1"></i> Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
