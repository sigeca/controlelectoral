<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h2 class="h3 mb-0 text-gray-800"><i class="bi bi-gender-ambiguous me-2 text-primary"></i>Catálogo de Sexos</h2>
        <p class="text-muted small mb-0">Gestión de opciones de sexo para el registro de personas en el sistema electoral.</p>
    </div>
    <div>
        <a href="<?= site_url('sexo/create') ?>" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Nuevo Sexo
        </a>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-list-ul me-1"></i> Listado de Registros</span>
        <span class="badge bg-secondary"><?= count($sexos) ?> registros</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 100px;" class="text-center">ID</th>
                        <th>Nombre</th>
                        <th class="text-center">Personas Asociadas</th>
                        <th style="width: 180px;" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($sexos)): ?>
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                No hay registros de sexo creados en la base de datos.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($sexos as $item): ?>
                            <tr>
                                <td class="text-center fw-semibold text-secondary">#<?= esc($item['idsexo']) ?></td>
                                <td>
                                    <span class="fw-bold text-dark"><?= esc($item['nombre']) ?></span>
                                </td>
                                <td class="text-center">
                                    <span class="badge rounded-pill <?= $item['total_personas'] > 0 ? 'bg-info text-dark' : 'bg-light text-muted border' ?>">
                                        <i class="bi bi-people me-1"></i><?= $item['total_personas'] ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="<?= site_url('sexo/edit/' . $item['idsexo']) ?>" class="btn btn-outline-warning" title="Editar">
                                            <i class="bi bi-pencil-square"></i> Editar
                                        </a>
                                        <a href="<?= site_url('sexo/delete/' . $item['idsexo']) ?>" 
                                           class="btn btn-outline-danger" 
                                           title="Eliminar"
                                           onclick="return confirm('¿Está completamente seguro de eliminar el sexo \'<?= esc($item['nombre']) ?>\'?');">
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
