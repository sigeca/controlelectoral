<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h2 class="h3 mb-0 text-gray-800"><i class="bi bi-tag-fill me-2 text-primary"></i>Gestión de Tipos de Parroquia</h2>
        <p class="text-muted small mb-0">Catálogo de clasificación de parroquias (Urbana, Rural, etc.).</p>
    </div>
    <div>
        <a href="<?= site_url('tipoparroquia/create') ?>" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Nuevo Tipo de Parroquia
        </a>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-list-ul me-1"></i> Listado de Tipos de Parroquia</span>
        <span class="badge bg-secondary"><?= count($tiposParroquia) ?> registros</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 100px;" class="text-center">ID</th>
                        <th>Nombre del Tipo de Parroquia</th>
                        <th style="width: 180px;" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($tiposParroquia)): ?>
                        <tr>
                            <td colspan="3" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                No hay tipos de parroquia registrados en la base de datos.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($tiposParroquia as $item): ?>
                            <tr>
                                <td class="text-center fw-semibold text-secondary">#<?= esc($item['idtipoparroquia']) ?></td>
                                <td>
                                    <span class="fw-bold text-dark"><?= esc($item['nombre']) ?></span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="<?= site_url('tipoparroquia/edit/' . $item['idtipoparroquia']) ?>" class="btn btn-outline-warning" title="Editar">
                                            <i class="bi bi-pencil-square"></i> Editar
                                        </a>
                                        <a href="<?= site_url('tipoparroquia/delete/' . $item['idtipoparroquia']) ?>" 
                                           class="btn btn-outline-danger" 
                                           title="Eliminar"
                                           onclick="return confirm('¿Está seguro de eliminar el tipo de parroquia \'<?= esc($item['nombre']) ?>\'?');">
                                            <i class="bi bi-trash3"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
