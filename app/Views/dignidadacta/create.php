<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-7">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <i class="bi bi-plus-circle-fill text-primary me-2 fs-5"></i>
                    <h5 class="mb-0 fw-bold">Registrar Votación por Dignidad en Acta</h5>
                </div>
                <a href="<?= site_url('dignidadacta') ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Volver
                </a>
            </div>
            <div class="card-body p-4">

                <?php if (empty($actas)): ?>
                    <div class="alert alert-warning d-flex align-items-center mb-3" role="alert">
                        <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
                        <div>
                            <strong>Atención:</strong> No hay actas electorales registradas.
                            Debe <a href="<?= site_url('acta/create') ?>" class="alert-link">crear al menos un acta electoral</a> antes de registrar votos por dignidad.
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (empty($dignidades)): ?>
                    <div class="alert alert-warning d-flex align-items-center mb-3" role="alert">
                        <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
                        <div>
                            <strong>Atención:</strong> No hay dignidades/candidatos registrados.
                            Debe <a href="<?= site_url('dignidad/create') ?>" class="alert-link">registrar candidaturas</a> previamente.
                        </div>
                    </div>
                <?php endif; ?>

                <form action="<?= site_url('dignidadacta/store') ?>" method="post" autocomplete="off">
                    <?= csrf_field() ?>

                    <!-- Seleccionar Acta Electoral -->
                    <div class="mb-3">
                        <label for="idacta" class="form-label fw-semibold">Acta Electoral <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-file-earmark-text"></i></span>
                            <select name="idacta" 
                                    id="idacta" 
                                    class="form-select <?= isset($errors['idacta']) ? 'is-invalid' : '' ?>" 
                                    required>
                                <option value="">-- Seleccionar Acta Electoral --</option>
                                <?php foreach ($actas as $a): ?>
                                    <option value="<?= esc($a['idacta']) ?>" 
                                        <?= (string)($old['idacta'] ?? old('idacta')) === (string)$a['idacta'] ? 'selected' : '' ?>>
                                        Acta #<?= esc($a['idacta']) ?> (Mesa #<?= esc($a['meza_numero'] ?? 'N/A') ?> - Recinto: <?= esc($a['recinto_nombre'] ?? 'Sin recinto') ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (isset($errors['idacta'])): ?>
                                <div class="invalid-feedback">
                                    <?= esc($errors['idacta']) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="form-text">Clave foránea hacia la tabla <code>acta</code>.</div>
                    </div>

                    <!-- Seleccionar Dignidad / Candidato -->
                    <div class="mb-3">
                        <label for="iddignidad" class="form-label fw-semibold">Dignidad / Candidato <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                            <select name="iddignidad" 
                                    id="iddignidad" 
                                    class="form-select <?= isset($errors['iddignidad']) ? 'is-invalid' : '' ?>" 
                                    required>
                                <option value="">-- Seleccionar Candidatura --</option>
                                <?php foreach ($dignidades as $d): ?>
                                    <option value="<?= esc($d['iddignidad']) ?>" 
                                        <?= (string)($old['iddignidad'] ?? old('iddignidad')) === (string)$d['iddignidad'] ? 'selected' : '' ?>>
                                        <?= esc(($d['persona_apellidos'] ?? '') . ' ' . ($d['persona_nombre'] ?? 'Sin Nombre')) ?> - Cargo: <?= esc($d['tipodignidad_nombre'] ?? 'N/A') ?> (Cédula: <?= esc($d['persona_cedula'] ?? 'N/A') ?>)
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

                    <!-- Votación (Número de Votos) -->
                    <div class="mb-3">
                        <label for="votacion" class="form-label fw-semibold">Número de Votos <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-success"><i class="bi bi-check-circle-fill"></i></span>
                            <input type="number" 
                                   name="votacion" 
                                   id="votacion" 
                                   class="form-control <?= isset($errors['votacion']) ? 'is-invalid' : '' ?>" 
                                   placeholder="0" 
                                   min="0"
                                   step="1"
                                   value="<?= esc($old['votacion'] ?? old('votacion', '0')) ?>" 
                                   required>
                            <?php if (isset($errors['votacion'])): ?>
                                <div class="invalid-feedback">
                                    <?= esc($errors['votacion']) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="form-text">Cantidad total de votos obtenidos por el candidato en esta acta.</div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                        <a href="<?= site_url('dignidadacta') ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-1"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary" <?= (empty($actas) || empty($dignidades)) ? 'disabled' : '' ?>>
                            <i class="bi bi-save me-1"></i> Guardar Votación
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
