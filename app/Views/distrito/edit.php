<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex align-items-center">
                <i class="bi bi-pencil-square text-warning me-2 fs-5"></i>
                <h5 class="mb-0">Editar Distrito #<?= esc($distrito['iddistrito']) ?></h5>
            </div>
            <div class="card-body p-4">
                <form action="<?= site_url('distrito/update/' . $distrito['iddistrito']) ?>" method="post" autocomplete="off">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label for="nombre" class="form-label fw-semibold">Nombre del Distrito <span class="text-danger">*</span></label>
                        <input type="text" 
                               name="nombre" 
                               id="nombre" 
                               class="form-control <?= isset($errors['nombre']) ? 'is-invalid' : '' ?>" 
                               maxlength="50"
                               value="<?= esc(old('nombre', $distrito['nombre'])) ?>" 
                               required 
                               autofocus>
                        <?php if (isset($errors['nombre'])): ?>
                            <div class="invalid-feedback">
                                <?= esc($errors['nombre']) ?>
                            </div>
                        <?php else: ?>
                            <div class="form-text">Máximo 50 caracteres.</div>
                        <?php endif; ?>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <a href="<?= site_url('distrito') ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Actualizar Distrito
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
