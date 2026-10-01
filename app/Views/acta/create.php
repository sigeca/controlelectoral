<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-7">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <i class="bi bi-plus-circle-fill text-primary me-2 fs-5"></i>
                    <h5 class="mb-0 fw-bold">Registrar Nueva Acta Electoral</h5>
                </div>
                <a href="<?= site_url('acta') ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Volver
                </a>
            </div>
            <div class="card-body p-4">

                <?php if (empty($mezas)): ?>
                    <div class="alert alert-warning d-flex align-items-center mb-3" role="alert">
                        <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
                        <div>
                            <strong>Atención:</strong> No hay mesas electorales registradas.
                            Debe <a href="<?= site_url('meza/create') ?>" class="alert-link">crear al menos una mesa</a> antes de registrar actas.
                        </div>
                    </div>
                <?php endif; ?>

                <form action="<?= site_url('acta/store') ?>" method="post" autocomplete="off">
                    <?= csrf_field() ?>

                    <!-- Seleccionar Mesa Electoral -->
                    <div class="mb-3">
                        <label for="idmeza" class="form-label fw-semibold">Mesa Electoral <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-inbox-fill"></i></span>
                            <select name="idmeza" 
                                    id="idmeza" 
                                    class="form-select <?= isset($errors['idmeza']) ? 'is-invalid' : '' ?>" 
                                    required>
                                <option value="">-- Seleccionar Mesa Electoral --</option>
                                <?php foreach ($mezas as $m): ?>
                                    <option value="<?= esc($m['idmeza']) ?>" 
                                        <?= (string)($old['idmeza'] ?? old('idmeza')) === (string)$m['idmeza'] ? 'selected' : '' ?>>
                                        Mesa #<?= esc($m['numero']) ?> (<?= esc($m['sexo_nombre'] ?? 'General') ?>) - Recinto: <?= esc($m['recinto_nombre'] ?? 'Sin recinto') ?> (<?= esc($m['canton_nombre'] ?? '') ?>)
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

                    <div class="row g-3">
                        <!-- Total Papeletas -->
                        <div class="col-md-4 mb-3">
                            <label for="totalpapeleta" class="form-label fw-semibold">Total Papeletas <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-primary"><i class="bi bi-files"></i></span>
                                <input type="number" 
                                       name="totalpapeleta" 
                                       id="totalpapeleta" 
                                       class="form-control <?= isset($errors['totalpapeleta']) ? 'is-invalid' : '' ?>" 
                                       placeholder="0" 
                                       min="0"
                                       step="1"
                                       value="<?= esc($old['totalpapeleta'] ?? old('totalpapeleta', '0')) ?>" 
                                       required>
                                <?php if (isset($errors['totalpapeleta'])): ?>
                                    <div class="invalid-feedback">
                                        <?= esc($errors['totalpapeleta']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="form-text">Número total de papeletas recibidas/escrutadas.</div>
                        </div>

                        <!-- Total Blancos -->
                        <div class="col-md-4 mb-3">
                            <label for="totalblancos" class="form-label fw-semibold">Votos en Blanco <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-warning"><i class="bi bi-square"></i></span>
                                <input type="number" 
                                       name="totalblancos" 
                                       id="totalblancos" 
                                       class="form-control <?= isset($errors['totalblancos']) ? 'is-invalid' : '' ?>" 
                                       placeholder="0" 
                                       min="0"
                                       step="1"
                                       value="<?= esc($old['totalblancos'] ?? old('totalblancos', '0')) ?>" 
                                       required>
                                <?php if (isset($errors['totalblancos'])): ?>
                                    <div class="invalid-feedback">
                                        <?= esc($errors['totalblancos']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="form-text">Total de votos en blanco.</div>
                        </div>

                        <!-- Total Nulos -->
                        <div class="col-md-4 mb-3">
                            <label for="totalnulos" class="form-label fw-semibold">Votos Nulos <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-danger"><i class="bi bi-x-square"></i></span>
                                <input type="number" 
                                       name="totalnulos" 
                                       id="totalnulos" 
                                       class="form-control <?= isset($errors['totalnulos']) ? 'is-invalid' : '' ?>" 
                                       placeholder="0" 
                                       min="0"
                                       step="1"
                                       value="<?= esc($old['totalnulos'] ?? old('totalnulos', '0')) ?>" 
                                       required>
                                <?php if (isset($errors['totalnulos'])): ?>
                                    <div class="invalid-feedback">
                                        <?= esc($errors['totalnulos']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="form-text">Total de votos nulos.</div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                        <a href="<?= site_url('acta') ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-1"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary" <?= empty($mezas) ? 'disabled' : '' ?>>
                            <i class="bi bi-save me-1"></i> Guardar Acta
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
