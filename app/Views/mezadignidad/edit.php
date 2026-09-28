<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-md-7 col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <i class="bi bi-pencil-square text-warning me-2 fs-5"></i>
                    <h5 class="mb-0">Editar Dignidad a Elegir en Mesa #<?= esc($asignacion['idmezadignidad']) ?></h5>
                </div>
                <a href="<?= site_url('mezadignidad') ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Volver
                </a>
            </div>
            <div class="card-body p-4">
                <form action="<?= site_url('mezadignidad/update/' . $asignacion['idmezadignidad']) ?>" method="post" autocomplete="off">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label for="idmeza" class="form-label fw-semibold">Mesa Receptora del Voto <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-inbox-fill"></i></span>
                            <select name="idmeza" 
                                    id="idmeza" 
                                    class="form-select <?= isset($errors['idmeza']) ? 'is-invalid' : '' ?>" 
                                    required>
                                <option value="">-- Seleccionar Mesa Receptora --</option>
                                <?php foreach ($mezas as $m): ?>
                                    <option value="<?= esc($m['idmeza']) ?>" 
                                        <?= (string)old('idmeza', $asignacion['idmeza']) === (string)$m['idmeza'] ? 'selected' : '' ?>>
                                        Mesa <?= esc($m['numero']) ?> (<?= esc($m['sexo_nombre']) ?>) - <?= esc($m['recinto_nombre']) ?> [<?= esc($m['canton_nombre'] ?? 'N/A') ?>]
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (isset($errors['idmeza'])): ?>
                                <div class="invalid-feedback">
                                    <?= esc($errors['idmeza']) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="form-text">Clave foránea hacia la tabla <code>meza</code>.</div>
                    </div>

                    <div class="mb-3">
                        <label for="iddignidad" class="form-label fw-semibold">Candidatura / Dignidad a Elegir <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person-badge-fill"></i></span>
                            <select name="iddignidad" 
                                    id="iddignidad" 
                                    class="form-select <?= isset($errors['iddignidad']) ? 'is-invalid' : '' ?>" 
                                    required>
                                <option value="">-- Seleccionar Candidatura / Dignidad --</option>
                                <?php foreach ($dignidades as $d): ?>
                                    <option value="<?= esc($d['iddignidad']) ?>" 
                                        <?= (string)old('iddignidad', $asignacion['iddignidad']) === (string)$d['iddignidad'] ? 'selected' : '' ?>>
                                        [<?= esc($d['tipodignidad_nombre']) ?>] <?= esc($d['persona_nombre']) ?> <?= esc($d['persona_apellidos']) ?> (CI: <?= esc($d['persona_cedula'] ?? 'N/A') ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (isset($errors['iddignidad'])): ?>
                                <div class="invalid-feedback">
                                    <?= esc($errors['iddignidad']) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="form-text">Clave foránea hacia la tabla <code>dignidad</code>.</div>
                    </div>

                    <div class="mb-3">
                        <label for="numeropapeleta" class="form-label fw-semibold">Cantidad de Papeletas Contadas <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-file-earmark-check"></i></span>
                            <input type="number" 
                                   name="numeropapeleta" 
                                   id="numeropapeleta" 
                                   class="form-control <?= isset($errors['numeropapeleta']) ? 'is-invalid' : '' ?>" 
                                   placeholder="Ej: 0, 150, 280, 350..." 
                                   min="0"
                                   step="1"
                                   value="<?= esc(old('numeropapeleta', $asignacion['numeropapeleta'])) ?>" 
                                   required>
                            <?php if (isset($errors['numeropapeleta'])): ?>
                                <div class="invalid-feedback">
                                    <?= esc($errors['numeropapeleta']) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="form-text">Cantidad de papeletas que fueron contadas para esta dignidad en la mesa electoral.</div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                        <a href="<?= site_url('mezadignidad') ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-1"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-warning text-dark fw-semibold">
                            <i class="bi bi-check2-circle me-1"></i> Actualizar Asignación
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
