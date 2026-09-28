<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <i class="bi bi-pencil-square text-warning me-2 fs-5"></i>
                    <h5 class="mb-0">Editar Provincia #<?= esc($provincia['idprovincia']) ?></h5>
                </div>
                <a href="<?= site_url('provincia') ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Volver
                </a>
            </div>
            <div class="card-body p-4">
                <form action="<?= site_url('provincia/update/' . $provincia['idprovincia']) ?>" method="post" autocomplete="off">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label for="nombre" class="form-label fw-semibold">Nombre de la Provincia <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                            <input type="text" 
                                   name="nombre" 
                                   id="nombre" 
                                   class="form-control <?= isset($errors['nombre']) ? 'is-invalid' : '' ?>" 
                                   placeholder="Ej: Pichincha, Guayas..." 
                                   maxlength="50"
                                   value="<?= esc(old('nombre', $provincia['nombre'])) ?>" 
                                   required 
                                   autofocus>
                            <?php if (isset($errors['nombre'])): ?>
                                <div class="invalid-feedback">
                                    <?= esc($errors['nombre']) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="form-text">Máximo 50 caracteres alfabéticos.</div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                        <a href="<?= site_url('provincia') ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-1"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-warning text-dark fw-semibold">
                            <i class="bi bi-check2-circle me-1"></i> Actualizar Provincia
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
