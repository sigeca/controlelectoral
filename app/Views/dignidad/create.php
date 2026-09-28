<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-md-7 col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <i class="bi bi-plus-circle-fill text-primary me-2 fs-5"></i>
                    <h5 class="mb-0">Registrar Candidatura / Dignidad</h5>
                </div>
                <a href="<?= site_url('dignidad') ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Volver
                </a>
            </div>
            <div class="card-body p-4">

                <?php if (empty($personas)): ?>
                    <div class="alert alert-warning d-flex align-items-center mb-3" role="alert">
                        <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
                        <div>
                            <strong>Atención:</strong> No hay personas empadronadas registradas.
                            Debe <a href="<?= site_url('persona/create') ?>" class="alert-link">registrar personas</a> en el padrón antes de postular candidatos.
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (empty($tipos)): ?>
                    <div class="alert alert-warning d-flex align-items-center mb-3" role="alert">
                        <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
                        <div>
                            <strong>Atención:</strong> No hay tipos de dignidad registrados.
                            Debe <a href="<?= site_url('tipodignidad/create') ?>" class="alert-link">crear tipos de dignidad</a> en el catálogo.
                        </div>
                    </div>
                <?php endif; ?>

                <form action="<?= site_url('dignidad/store') ?>" method="post" autocomplete="off">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label for="idpersona" class="form-label fw-semibold">Candidato / Persona <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                            <select name="idpersona" 
                                    id="idpersona" 
                                    class="form-select <?= isset($errors['idpersona']) ? 'is-invalid' : '' ?>" 
                                    required>
                                <option value="">-- Seleccionar Persona / Candidato --</option>
                                <?php foreach ($personas as $p): ?>
                                    <option value="<?= esc($p['idpersona']) ?>" 
                                        <?= (string)($old['idpersona'] ?? old('idpersona')) === (string)$p['idpersona'] ? 'selected' : '' ?>>
                                        <?= esc($p['apellidos'] . ' ' . $p['nombre']) ?> (CI: <?= esc($p['cedula']) ?> - <?= esc($p['sexo_nombre'] ?? 'N/A') ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (isset($errors['idpersona'])): ?>
                                <div class="invalid-feedback">
                                    <?= esc($errors['idpersona']) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="form-text">Clave foránea hacia la tabla <code>persona</code>.</div>
                    </div>

                    <div class="mb-3">
                        <label for="idtipodignidad" class="form-label fw-semibold">Cargo / Dignidad Electoral <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-award-fill text-warning"></i></span>
                            <select name="idtipodignidad" 
                                    id="idtipodignidad" 
                                    class="form-select <?= isset($errors['idtipodignidad']) ? 'is-invalid' : '' ?>" 
                                    required>
                                <option value="">-- Seleccionar Tipo de Dignidad --</option>
                                <?php foreach ($tipos as $t): ?>
                                    <option value="<?= esc($t['idtipodignidad']) ?>" 
                                        <?= (string)($old['idtipodignidad'] ?? old('idtipodignidad')) === (string)$t['idtipodignidad'] ? 'selected' : '' ?>>
                                        <?= esc($t['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (isset($errors['idtipodignidad'])): ?>
                                <div class="invalid-feedback">
                                    <?= esc($errors['idtipodignidad']) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="form-text">Clave foránea hacia la tabla <code>tipodignidad</code>.</div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                        <a href="<?= site_url('dignidad') ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-1"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary" <?= (empty($personas) || empty($tipos)) ? 'disabled' : '' ?>>
                            <i class="bi bi-save me-1"></i> Guardar Candidatura
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
