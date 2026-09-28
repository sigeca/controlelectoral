<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h2 class="h3 mb-0 text-gray-800"><i class="bi bi-person-lock me-2 text-primary"></i>Gestión de Usuarios</h2>
        <p class="text-muted small mb-0">Cuentas de usuario, credenciales de acceso y asignación de roles para el personal electoral.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= site_url('usuario/create') ?>" class="btn btn-primary shadow-sm">
            <i class="bi bi-person-plus-fill me-1"></i> Nuevo Usuario
        </a>
        <a href="<?= site_url('rolusuario') ?>" class="btn btn-outline-secondary shadow-sm">
            <i class="bi bi-shield-check me-1"></i> Ver Roles
        </a>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-white py-3 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
        <div class="fw-bold text-primary">
            <i class="bi bi-people-fill me-1"></i> Cuentas de Usuario Registradas (<?= count($usuarios) ?>)
        </div>
        <div class="col-12 col-sm-4">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                <input type="text" id="filtroUsuarios" class="form-control" placeholder="Buscar por usuario, persona o rol...">
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tablaUsuarios">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 70px;">ID</th>
                        <th>Nombre de Usuario</th>
                        <th>Persona Asignada</th>
                        <th>Rol de Sistema</th>
                        <th>Contraseña</th>
                        <th class="text-center" style="width: 170px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($usuarios)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-person-x fs-1 d-block mb-2 text-secondary"></i>
                                <div class="fs-5">No existen usuarios registrados actualmente.</div>
                                <p class="small text-muted mb-3">Comience registrando una cuenta de usuario.</p>
                                <a href="<?= site_url('usuario/create') ?>" class="btn btn-sm btn-primary">
                                    <i class="bi bi-plus-lg me-1"></i> Registrar Usuario
                                </a>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($usuarios as $u): ?>
                            <tr>
                                <td class="text-center fw-semibold text-secondary">#<?= esc($u['idusuario']) ?></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="bg-primary text-white rounded-circle p-2 d-inline-flex justify-content-center align-items-center" style="width: 36px; height: 36px;">
                                            <i class="bi bi-person-fill fs-6"></i>
                                        </div>
                                        <div>
                                            <span class="fw-bold text-dark font-monospace fs-6"><?= esc($u['usuario']) ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <?php if (!empty($u['persona_nombre'])): ?>
                                        <div class="fw-semibold text-dark"><?= esc($u['persona_apellidos'] . ', ' . $u['persona_nombre']) ?></div>
                                        <div class="small text-muted"><i class="bi bi-card-heading me-1"></i>CI: <?= esc($u['cedula']) ?></div>
                                    <?php else: ?>
                                        <span class="text-danger small"><i class="bi bi-exclamation-circle me-1"></i>Sin Persona Vinculada</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php
                                        $rolNombre = $u['rol_nombre'] ?? 'Sin Rol';
                                        $badgeStyle = 'bg-secondary';
                                        if (stripos($rolNombre, 'admin') !== false) {
                                            $badgeStyle = 'bg-danger-subtle text-danger border border-danger-subtle';
                                        } elseif (stripos($rolNombre, 'operador') !== false) {
                                            $badgeStyle = 'bg-primary-subtle text-primary border border-primary-subtle';
                                        } elseif (stripos($rolNombre, 'supervis') !== false) {
                                            $badgeStyle = 'bg-warning-subtle text-warning-emphasis border border-warning-subtle';
                                        } elseif (stripos($rolNombre, 'audit') !== false) {
                                            $badgeStyle = 'bg-info-subtle text-info-emphasis border border-info-subtle';
                                        }
                                    ?>
                                    <span class="badge <?= $badgeStyle ?> px-3 py-2 fs-7">
                                        <i class="bi bi-shield-lock-fill me-1"></i><?= esc($rolNombre) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-muted border font-monospace px-2 py-1">
                                        &bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="<?= site_url('usuario/show/' . $u['idusuario']) ?>" class="btn btn-outline-info" title="Ver Detalles">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="<?= site_url('usuario/edit/' . $u['idusuario']) ?>" class="btn btn-outline-warning" title="Editar">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <a href="<?= site_url('usuario/delete/' . $u['idusuario']) ?>" 
                                           class="btn btn-outline-danger" 
                                           title="Eliminar"
                                           onclick="return confirm('¿Está seguro de eliminar al usuario \'<?= esc($u['usuario']) ?>\'?');">
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
    document.getElementById('filtroUsuarios')?.addEventListener('keyup', function() {
        const valor = this.value.toLowerCase().trim();
        const filas = document.querySelectorAll('#tablaUsuarios tbody tr');
        
        filas.forEach(fila => {
            const textoFila = fila.textContent.toLowerCase();
            fila.style.display = textoFila.includes(valor) ? '' : 'none';
        });
    });
</script>
<?= $this->endSection() ?>
