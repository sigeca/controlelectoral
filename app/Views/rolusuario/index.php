<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h2 class="h3 mb-0 text-gray-800"><i class="bi bi-shield-lock-fill me-2 text-primary"></i>Roles de Usuario</h2>
        <p class="text-muted small mb-0">Gestión de roles y niveles de acceso para los usuarios del sistema electoral.</p>
    </div>
    <div>
        <a href="<?= site_url('rolusuario/create') ?>" class="btn btn-primary shadow-sm">
            <i class="bi bi-plus-circle me-1"></i> Nuevo Rol
        </a>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-white py-3 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
        <div class="fw-bold text-primary">
            <i class="bi bi-shield-check me-1"></i> Listado de Roles (<?= count($roles) ?>)
        </div>
        <div class="col-12 col-sm-4">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                <input type="text" id="filtroRoles" class="form-control" placeholder="Buscar rol...">
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tablaRoles">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 80px;">ID</th>
                        <th>Nombre del Rol</th>
                        <th class="text-center" style="width: 170px;">Usuarios Asignados</th>
                        <th class="text-center" style="width: 170px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($roles)): ?>
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="bi bi-shield-x fs-1 d-block mb-2 text-secondary"></i>
                                <div class="fs-5">No existen roles registrados.</div>
                                <p class="small text-muted mb-3">Comience registrando un nuevo rol en el sistema.</p>
                                <a href="<?= site_url('rolusuario/create') ?>" class="btn btn-sm btn-primary">
                                    <i class="bi bi-plus-lg me-1"></i> Registrar Rol
                                </a>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($roles as $rol): ?>
                            <tr>
                                <td class="text-center fw-semibold text-secondary">#<?= esc($rol['idrolusuario']) ?></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="bg-primary-subtle text-primary rounded-circle p-2 d-inline-flex">
                                            <i class="bi bi-person-badge"></i>
                                        </div>
                                        <span class="fw-bold text-dark fs-6"><?= esc($rol['nombre']) ?></span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge rounded-pill <?= $rol['total_usuarios'] > 0 ? 'bg-info-subtle text-info-emphasis border border-info-subtle' : 'bg-light text-muted border' ?>">
                                        <i class="bi bi-people me-1"></i><?= $rol['total_usuarios'] ?> usuario(s)
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="<?= site_url('rolusuario/edit/' . $rol['idrolusuario']) ?>" class="btn btn-outline-warning" title="Editar">
                                            <i class="bi bi-pencil-square"></i> Editar
                                        </a>
                                        <a href="<?= site_url('rolusuario/delete/' . $rol['idrolusuario']) ?>" 
                                           class="btn btn-outline-danger" 
                                           title="Eliminar"
                                           onclick="return confirm('¿Está seguro de eliminar el rol \'<?= esc($rol['nombre']) ?>\'?');">
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

<?= $this->section('scripts') ?>
<script>
    document.getElementById('filtroRoles')?.addEventListener('keyup', function() {
        const valor = this.value.toLowerCase().trim();
        const filas = document.querySelectorAll('#tablaRoles tbody tr');
        
        filas.forEach(fila => {
            const textoFila = fila.textContent.toLowerCase();
            fila.style.display = textoFila.includes(valor) ? '' : 'none';
        });
    });
</script>
<?= $this->endSection() ?>
