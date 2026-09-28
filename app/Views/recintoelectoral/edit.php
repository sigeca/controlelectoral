<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-md-7 col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <i class="bi bi-pencil-square text-warning me-2 fs-5"></i>
                    <h5 class="mb-0">Editar Recinto Electoral #<?= esc($recinto['idrecintoelectoral']) ?></h5>
                </div>
                <a href="<?= site_url('recintoelectoral') ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Volver
                </a>
            </div>
            <div class="card-body p-4">
                <form action="<?= site_url('recintoelectoral/update/' . $recinto['idrecintoelectoral']) ?>" method="post" autocomplete="off">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label for="idzona" class="form-label fw-semibold">Zona Electoral <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-pin-map-fill"></i></span>
                            <select name="idzona" 
                                    id="idzona" 
                                    class="form-select <?= isset($errors['idzona']) ? 'is-invalid' : '' ?>" 
                                    required>
                                <option value="">-- Seleccionar Zona Electoral --</option>
                                <?php foreach ($zonas as $z): ?>
                                    <option value="<?= esc($z['idzona']) ?>" 
                                        <?= (string)old('idzona', $recinto['idzona']) === (string)$z['idzona'] ? 'selected' : '' ?>>
                                        <?= esc($z['nombre']) ?> (Parroquia: <?= esc($z['parroquia_nombre'] ?? 'N/A') ?> - Cantón: <?= esc($z['canton_nombre'] ?? 'N/A') ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (isset($errors['idzona'])): ?>
                                <div class="invalid-feedback">
                                    <?= esc($errors['idzona']) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="form-text">Clave foránea hacia la tabla <code>zona</code>.</div>
                    </div>

                    <div class="mb-3">
                        <label for="nombre" class="form-label fw-semibold">Nombre del Recinto Electoral <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-building"></i></span>
                            <input type="text" 
                                   name="nombre" 
                                   id="nombre" 
                                   class="form-control <?= isset($errors['nombre']) ? 'is-invalid' : '' ?>" 
                                   placeholder="Ej: Colegio Benalcázar, Escuela Manuela Cañizares..." 
                                   maxlength="150"
                                   value="<?= esc(old('nombre', $recinto['nombre'])) ?>" 
                                   required 
                                   autofocus>
                            <?php if (isset($errors['nombre'])): ?>
                                <div class="invalid-feedback">
                                    <?= esc($errors['nombre']) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="form-text">Máximo 150 caracteres.</div>
                    </div>

                    <div class="mb-3">
                        <label for="numeroelectores" class="form-label fw-semibold">Número de Electores <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-people-fill"></i></span>
                            <input type="number" 
                                   name="numeroelectores" 
                                   id="numeroelectores" 
                                   class="form-control <?= isset($errors['numeroelectores']) ? 'is-invalid' : '' ?>" 
                                   placeholder="Ej: 2500" 
                                   min="0"
                                   step="1"
                                   value="<?= esc(old('numeroelectores', $recinto['numeroelectores'])) ?>" 
                                   required>
                            <?php if (isset($errors['numeroelectores'])): ?>
                                <div class="invalid-feedback">
                                    <?= esc($errors['numeroelectores']) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="form-text">Cantidad de ciudadanos empadronados para sufragar en este recinto electoral.</div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                        <a href="<?= site_url('recintoelectoral') ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-1"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-warning text-dark fw-semibold">
                            <i class="bi bi-check2-circle me-1"></i> Actualizar Recinto
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
