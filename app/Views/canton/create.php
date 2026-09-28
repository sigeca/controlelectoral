<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-md-7 col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <i class="bi bi-plus-circle-fill text-primary me-2 fs-5"></i>
                    <h5 class="mb-0">Registrar Nuevo Cantón</h5>
                </div>
                <a href="<?= site_url('canton') ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Volver
                </a>
            </div>
            <div class="card-body p-4">

                <?php if (empty($provincias)): ?>
                    <div class="alert alert-warning d-flex align-items-center" role="alert">
                        <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
                        <div>
                            <strong>Atención:</strong> No hay provincias registradas en el sistema.
                            Debe <a href="<?= site_url('provincia/create') ?>" class="alert-link">crear al menos una provincia</a> antes de poder registrar cantones.
                        </div>
                    </div>
                <?php endif; ?>

                <form action="<?= site_url('canton/store') ?>" method="post" autocomplete="off">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label for="idprovincia" class="form-label fw-semibold">Provincia <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                            <select name="idprovincia" 
                                    id="idprovincia" 
                                    class="form-select <?= isset($errors['idprovincia']) ? 'is-invalid' : '' ?>" 
                                    required>
                                <option value="">-- Seleccionar Provincia --</option>
                                <?php foreach ($provincias as $p): ?>
                                    <option value="<?= esc($p['idprovincia']) ?>" 
                                        <?= (string)($old['idprovincia'] ?? old('idprovincia')) === (string)$p['idprovincia'] ? 'selected' : '' ?>>
                                        <?= esc($p['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (isset($errors['idprovincia'])): ?>
                                <div class="invalid-feedback">
                                    <?= esc($errors['idprovincia']) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="form-text">Clave foránea hacia la tabla <code>provincia</code>.</div>
                    </div>

                    <div class="mb-3">
                        <label for="nombre" class="form-label fw-semibold">Nombre del Cantón <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-buildings"></i></span>
                            <input type="text" 
                                   name="nombre" 
                                   id="nombre" 
                                   class="form-control <?= isset($errors['nombre']) ? 'is-invalid' : '' ?>" 
                                   placeholder="Ej: Quito, Guayaquil, Manta, Cuenca..." 
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
                        <a href="<?= site_url('canton') ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-1"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary" <?= empty($provincias) ? 'disabled' : '' ?>>
                            <i class="bi bi-save me-1"></i> Guardar Cantón
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
