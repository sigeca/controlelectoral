<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-md-7 col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <i class="bi bi-plus-circle-fill text-primary me-2 fs-5"></i>
                    <h5 class="mb-0">Registrar Nueva Parroquia</h5>
                </div>
                <a href="<?= site_url('parroquia') ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Volver
                </a>
            </div>
            <div class="card-body p-4">

                <?php if (empty($cantones)): ?>
                    <div class="alert alert-warning d-flex align-items-center mb-3" role="alert">
                        <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
                        <div>
                            <strong>Atención:</strong> No hay cantones registrados en el sistema.
                            Debe <a href="<?= site_url('canton/create') ?>" class="alert-link">crear al menos un cantón</a> antes de registrar parroquias.
                        </div>
                    </div>
                <?php endif; ?>

                <form action="<?= site_url('parroquia/store') ?>" method="post" autocomplete="off">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label for="idcanton" class="form-label fw-semibold">Cantón <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-buildings"></i></span>
                            <select name="idcanton" 
                                     id="idcanton" 
                                     class="form-select <?= isset($errors['idcanton']) ? 'is-invalid' : '' ?>" 
                                     required>
                                <option value="">-- Seleccionar Cantón --</option>
                                <?php foreach ($cantones as $c): ?>
                                    <option value="<?= esc($c['idcanton']) ?>" 
                                        <?= (string)($old['idcanton'] ?? old('idcanton')) === (string)$c['idcanton'] ? 'selected' : '' ?>>
                                        <?= esc($c['nombre']) ?> (Provincia: <?= esc($c['provincia_nombre'] ?? 'N/A') ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (isset($errors['idcanton'])): ?>
                                <div class="invalid-feedback">
                                    <?= esc($errors['idcanton']) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="form-text">Clave foránea hacia la tabla <code>canton</code>.</div>
                    </div>

                    <div class="mb-3">
                        <label for="idtipoparroquia" class="form-label fw-semibold">Tipo de Parroquia</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-tag"></i></span>
                            <select name="idtipoparroquia" 
                                     id="idtipoparroquia" 
                                     class="form-select <?= isset($errors['idtipoparroquia']) ? 'is-invalid' : '' ?>">
                                <option value="">-- Seleccionar Tipo (Opcional) --</option>
                                <?php foreach ($tiposParroquia as $tp): ?>
                                    <option value="<?= esc($tp['idtipoparroquia']) ?>" 
                                        <?= (string)($old['idtipoparroquia'] ?? old('idtipoparroquia')) === (string)$tp['idtipoparroquia'] ? 'selected' : '' ?>>
                                        <?= esc($tp['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (isset($errors['idtipoparroquia'])): ?>
                                <div class="invalid-feedback">
                                    <?= esc($errors['idtipoparroquia']) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="form-text">Clave foránea opcional hacia <code>tipoparroquia</code>.</div>
                    </div>

                    <div class="mb-3">
                        <label for="iddistrito" class="form-label fw-semibold">Distrito Electoral</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-map"></i></span>
                            <select name="iddistrito" 
                                     id="iddistrito" 
                                     class="form-select <?= isset($errors['iddistrito']) ? 'is-invalid' : '' ?>">
                                <option value="">-- Seleccionar Distrito (Opcional) --</option>
                                <?php foreach ($distritos as $d): ?>
                                    <option value="<?= esc($d['iddistrito']) ?>" 
                                        <?= (string)($old['iddistrito'] ?? old('iddistrito')) === (string)$d['iddistrito'] ? 'selected' : '' ?>>
                                        <?= esc($d['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (isset($errors['iddistrito'])): ?>
                                <div class="invalid-feedback">
                                    <?= esc($errors['iddistrito']) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="form-text">Clave foránea opcional hacia <code>distrito</code>.</div>
                    </div>

                    <div class="mb-3">
                        <label for="nombre" class="form-label fw-semibold">Nombre de la Parroquia <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-geo"></i></span>
                            <input type="text" 
                                   name="nombre" 
                                   id="nombre" 
                                   class="form-control <?= isset($errors['nombre']) ? 'is-invalid' : '' ?>" 
                                   placeholder="Ej: Iñaquito, Tarqui, Cumbayá..." 
                                   maxlength="50"
                                   value="<?= esc($old['nombre'] ?? old('nombre')) ?>" 
                                   required 
                                   autofocus>
                            <?php if (isset($errors['nombre'])): ?>
                                <div class="invalid-feedback">
                                    <?= esc($errors['nombre']) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="form-text">Máximo 50 caracteres.</div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                        <a href="<?= site_url('parroquia') ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-1"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary" <?= empty($cantones) ? 'disabled' : '' ?>>
                            <i class="bi bi-save me-1"></i> Guardar Parroquia
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
