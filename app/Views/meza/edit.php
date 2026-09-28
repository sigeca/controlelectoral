<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-md-7 col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <i class="bi bi-pencil-square text-warning me-2 fs-5"></i>
                    <h5 class="mb-0">Editar Mesa #<?= esc($meza['idmeza']) ?> (Mesa <?= esc($meza['numero']) ?>)</h5>
                </div>
                <a href="<?= site_url('meza/ver/' . $meza['idmeza']) ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Volver
                </a>
            </div>
            <div class="card-body p-4">
                <form action="<?= site_url('meza/update/' . $meza['idmeza']) ?>" method="post" autocomplete="off">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label for="idrecintoelectoral" class="form-label fw-semibold">Recinto Electoral <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-building"></i></span>
                            <select name="idrecintoelectoral" 
                                    id="idrecintoelectoral" 
                                    class="form-select <?= isset($errors['idrecintoelectoral']) ? 'is-invalid' : '' ?>" 
                                    required>
                                <option value="">-- Seleccionar Recinto Electoral --</option>
                                <?php foreach ($recintos as $r): ?>
                                    <option value="<?= esc($r['idrecintoelectoral']) ?>" 
                                        <?= (string)old('idrecintoelectoral', $meza['idrecintoelectoral']) === (string)$r['idrecintoelectoral'] ? 'selected' : '' ?>>
                                        <?= esc($r['nombre']) ?> (Zona: <?= esc($r['zona_nombre'] ?? 'N/A') ?> - Cantón: <?= esc($r['canton_nombre'] ?? 'N/A') ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (isset($errors['idrecintoelectoral'])): ?>
                                <div class="invalid-feedback">
                                    <?= esc($errors['idrecintoelectoral']) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="form-text">Clave foránea hacia la tabla <code>recintoelectoral</code>.</div>
                    </div>

                    <div class="mb-3">
                        <label for="idsexo" class="form-label fw-semibold">Género / Sexo de la Mesa <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-gender-ambiguous"></i></span>
                            <select name="idsexo" 
                                    id="idsexo" 
                                    class="form-select <?= isset($errors['idsexo']) ? 'is-invalid' : '' ?>" 
                                    required>
                                <option value="">-- Seleccionar Sexo / Género --</option>
                                <?php foreach ($sexos as $s): ?>
                                    <option value="<?= esc($s['idsexo']) ?>" 
                                        <?= (string)old('idsexo', $meza['idsexo']) === (string)$s['idsexo'] ? 'selected' : '' ?>>
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
                        <div class="form-text">Clave foránea hacia la tabla <code>sexo</code>.</div>
                    </div>

                    <div class="mb-3">
                        <label for="numero" class="form-label fw-semibold">Número de Mesa <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-123"></i></span>
                            <input type="number" 
                                   name="numero" 
                                   id="numero" 
                                   class="form-control <?= isset($errors['numero']) ? 'is-invalid' : '' ?>" 
                                   placeholder="Ej: 1, 2, 3..." 
                                   min="1"
                                   step="1"
                                   value="<?= esc(old('numero', $meza['numero'])) ?>" 
                                   required 
                                   autofocus>
                            <?php if (isset($errors['numero'])): ?>
                                <div class="invalid-feedback">
                                    <?= esc($errors['numero']) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="form-text">Número correlativo de la junta receptora del voto.</div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                        <a href="<?= site_url('meza/ver/' . $meza['idmeza']) ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-1"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-warning text-dark fw-semibold">
                            <i class="bi bi-check2-circle me-1"></i> Actualizar Mesa
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
