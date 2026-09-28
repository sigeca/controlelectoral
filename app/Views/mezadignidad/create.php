<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-md-7 col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <i class="bi bi-plus-circle-fill text-primary me-2 fs-5"></i>
                    <h5 class="mb-0">Asignar Dignidad a Elegir en Mesa</h5>
                </div>
                <a href="<?= site_url('mezadignidad') ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Volver
                </a>
            </div>
            <div class="card-body p-4">

                <?php if (empty($mezas)): ?>
                    <div class="alert alert-warning d-flex align-items-center mb-3" role="alert">
                        <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
                        <div>
                            <strong>Atención:</strong> No hay mesas electorales registradas.
                            Debe <a href="<?= site_url('meza/create') ?>" class="alert-link">crear mesas electorales</a> primero.
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (empty($dignidades)): ?>
                    <div class="alert alert-warning d-flex align-items-center mb-3" role="alert">
                        <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
                        <div>
                            <strong>Atención:</strong> No hay candidaturas registradas.
                            Debe <a href="<?= site_url('dignidad/create') ?>" class="alert-link">postular candidaturas</a> primero.
                        </div>
                    </div>
                <?php endif; ?>

                <form action="<?= site_url('mezadignidad/store') ?>" method="post" autocomplete="off">
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
                                        <?= (string)($old['idmeza'] ?? old('idmeza')) === (string)$m['idmeza'] ? 'selected' : '' ?>>
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
                                        <?= (string)($old['iddignidad'] ?? old('iddignidad')) === (string)$d['iddignidad'] ? 'selected' : '' ?>>
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
                                    value="<?= esc($old['numeropapeleta'] ?? old('numeropapeleta', '0')) ?>" 
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
                        <button type="submit" class="btn btn-primary" <?= (empty($mezas) || empty($dignidades)) ? 'disabled' : '' ?>>
                            <i class="bi bi-save me-1"></i> Guardar Asignación
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
