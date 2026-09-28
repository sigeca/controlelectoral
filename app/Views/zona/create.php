<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-md-7 col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <i class="bi bi-plus-circle-fill text-primary me-2 fs-5"></i>
                    <h5 class="mb-0">Registrar Nueva Zona Electoral</h5>
                </div>
                <a href="<?= site_url('zona') ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Volver
                </a>
            </div>
            <div class="card-body p-4">

                <?php if (empty($parroquias)): ?>
                    <div class="alert alert-warning d-flex align-items-center mb-3" role="alert">
                        <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
                        <div>
                            <strong>Atención:</strong> No hay parroquias registradas en el sistema.
                            Debe <a href="<?= site_url('parroquia/create') ?>" class="alert-link">crear al menos una parroquia</a> antes de registrar zonas.
                        </div>
                    </div>
                <?php endif; ?>

                <form action="<?= site_url('zona/store') ?>" method="post" autocomplete="off">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label for="idparroquia" class="form-label fw-semibold">Parroquia <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-geo-fill"></i></span>
                            <select name="idparroquia" 
                                    id="idparroquia" 
                                    class="form-select <?= isset($errors['idparroquia']) ? 'is-invalid' : '' ?>" 
                                    required>
                                <option value="">-- Seleccionar Parroquia --</option>
                                <?php foreach ($parroquias as $p): ?>
                                    <option value="<?= esc($p['idparroquia']) ?>" 
                                        <?= (string)($old['idparroquia'] ?? old('idparroquia')) === (string)$p['idparroquia'] ? 'selected' : '' ?>>
                                        <?= esc($p['nombre']) ?> (Cantón: <?= esc($p['canton_nombre'] ?? 'N/A') ?> - Prov: <?= esc($p['provincia_nombre'] ?? 'N/A') ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (isset($errors['idparroquia'])): ?>
                                <div class="invalid-feedback">
                                    <?= esc($errors['idparroquia']) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="form-text">Clave foránea hacia la tabla <code>parroquia</code>.</div>
                    </div>

                    <div class="mb-3">
                        <label for="nombre" class="form-label fw-semibold">Nombre de la Zona <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-pin-map"></i></span>
                            <input type="text" 
                                   name="nombre" 
                                   id="nombre" 
                                   class="form-control <?= isset($errors['nombre']) ? 'is-invalid' : '' ?>" 
                                   placeholder="Ej: Zona 1 - Centro, Sector Norte..." 
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
                        <a href="<?= site_url('zona') ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-1"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary" <?= empty($parroquias) ? 'disabled' : '' ?>>
                            <i class="bi bi-save me-1"></i> Guardar Zona
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
