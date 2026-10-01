<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Sistema de Control Electoral') ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --sidebar-width: 270px;
            --topbar-height: 60px;
            --primary-color: #0d6efd;
            --sidebar-bg: #1e293b;
            --sidebar-hover: #334155;
            --sidebar-active: #0d6efd;
        }

        body {
            background-color: #f1f5f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            margin: 0;
            overflow-x: hidden;
        }

        /* Top Navbar */
        .top-navbar {
            height: var(--topbar-height);
            z-index: 1030;
            background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
        }

        /* App Wrapper */
        #app-wrapper {
            display: flex;
            min-height: calc(100vh - var(--topbar-height));
            position: relative;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Sidebar Styling */
        #sidebar {
            width: var(--sidebar-width);
            min-width: var(--sidebar-width);
            background-color: var(--sidebar-bg);
            color: #e2e8f0;
            display: flex;
            flex-direction: column;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1025;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.08);
        }

        /* Sidebar collapsed state */
        #app-wrapper.sidebar-collapsed #sidebar {
            margin-left: calc(-1 * var(--sidebar-width));
        }

        /* Sidebar Items */
        .sidebar-heading {
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #94a3b8;
            padding: 1rem 1.25rem 0.4rem;
            font-weight: 700;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0.5rem 0.75rem;
            margin: 0;
        }

        .sidebar-menu .nav-item {
            margin-bottom: 0.25rem;
        }

        .sidebar-menu .nav-link {
            display: flex;
            align-items: center;
            padding: 0.65rem 1rem;
            color: #cbd5e1;
            border-radius: 0.5rem;
            text-decoration: none;
            font-size: 0.93rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .sidebar-menu .nav-link i {
            font-size: 1.15rem;
            width: 26px;
            margin-right: 0.65rem;
            display: inline-flex;
            justify-content: center;
        }

        .sidebar-menu .nav-link:hover {
            color: #ffffff;
            background-color: var(--sidebar-hover);
            transform: translateX(3px);
        }

        .sidebar-menu .nav-link.active {
            color: #ffffff;
            background-color: var(--sidebar-active);
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.35);
        }

        .sidebar-footer {
            margin-top: auto;
            padding: 1rem 1.25rem;
            background-color: rgba(0, 0, 0, 0.2);
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            font-size: 0.82rem;
            color: #94a3b8;
        }

        /* Main Content */
        #content-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .main-container {
            flex: 1;
            padding: 1.75rem 1.5rem 2.5rem;
        }

        .card {
            border: none;
            border-radius: 0.75rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .card-header {
            background-color: #ffffff;
            border-bottom: 1px solid #edf2f7;
            font-weight: 600;
            padding: 1rem 1.25rem;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(13, 110, 253, 0.04);
        }

        .badge-male {
            background-color: #e0f2fe;
            color: #0369a1;
        }

        .badge-female {
            background-color: #fce7f3;
            color: #be185d;
        }

        footer {
            background-color: #ffffff;
            border-top: 1px solid #e2e8f0;
            padding: 1rem 0;
            color: #64748b;
            font-size: 0.88rem;
        }

        /* Overlay for mobile */
        #sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.45);
            z-index: 1020;
            backdrop-filter: blur(2px);
        }

        /* Responsive Breakpoints */
        @media (max-width: 991.98px) {
            #sidebar {
                position: fixed;
                top: 0;
                bottom: 0;
                left: 0;
                height: 100vh;
                margin-left: calc(-1 * var(--sidebar-width));
                z-index: 1040;
            }

            #app-wrapper.mobile-open #sidebar {
                margin-left: 0;
            }

            #app-wrapper.mobile-open #sidebar-overlay {
                display: block;
            }
        }
    </style>
</head>
<body>

    <!-- Barra de Navegación Superior -->
    <header class="navbar navbar-dark sticky-top top-navbar px-3">
        <div class="d-flex align-items-center gap-2">
            <!-- Botón Superior para Ocultar / Mostrar el Menú Vertical -->
            <button id="sidebarToggle" 
                    class="btn btn-outline-light btn-sm d-flex align-items-center justify-content-center p-2 rounded-2" 
                    type="button" 
                    aria-label="Alternar Menú" 
                    title="Alternar Menú Lateral">
                <i class="bi bi-list fs-5"></i>
            </button>

            <!-- Logotipo y Nombre del Sistema -->
            <a class="navbar-brand d-flex align-items-center gap-2 text-white text-decoration-none fw-bold ms-1" href="<?= site_url('persona') ?>">
                <i class="bi bi-box-seam-fill fs-5 text-warning"></i>
                <span class="d-none d-sm-inline">Control Electoral</span>
            </a>
        </div>

        <!-- Información del Sistema en la Barra Superior -->
        <div class="d-flex align-items-center gap-2 text-white-50 small">
            <span class="d-none d-md-inline text-light"><i class="bi bi-shield-check text-success me-1"></i> Panel Electoral</span>
            <span class="badge bg-white bg-opacity-10 text-white border border-light border-opacity-25 px-2 py-1">
                <i class="bi bi-database me-1"></i>dbcontrolelectoral
            </span>
        </div>
    </header>

    <!-- Contenedor Principal de la Aplicación -->
    <div id="app-wrapper">

        <!-- Overlay para pantallas pequeñas / móviles -->
        <div id="sidebar-overlay"></div>

        <!-- Menú Vertical Lateral Izquierdo -->
        <aside id="sidebar">
            <div class="p-3 d-flex align-items-center justify-content-between border-bottom border-secondary border-opacity-25">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-diagram-3-fill text-primary fs-5"></i>
                    <span class="fw-bold text-white">Módulos del Sistema</span>
                </div>
                <!-- Botón de cierre visible sólo en móviles -->
                <button id="sidebarCloseBtn" class="btn btn-sm btn-outline-secondary text-white d-lg-none" type="button" aria-label="Cerrar menú">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <!-- Navegación con los módulos implementados -->
            <div class="overflow-y-auto flex-grow-1 py-2">
                <div class="sidebar-heading">Padrón Electoral</div>
                <ul class="sidebar-menu">
                    <li class="nav-item">
                        <a href="<?= site_url('persona') ?>" class="nav-link <?= (url_is('persona*') || current_url() == site_url()) ? 'active' : '' ?>">
                            <i class="bi bi-people-fill"></i>
                            <span>Personas</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= site_url('sexo') ?>" class="nav-link <?= url_is('sexo*') ? 'active' : '' ?>">
                            <i class="bi bi-gender-ambiguous"></i>
                            <span>Sexos</span>
                        </a>
                    </li>
                </ul>

                <div class="sidebar-heading">División Territorial</div>
                <ul class="sidebar-menu">
                    <li class="nav-item">
                        <a href="<?= site_url('provincia') ?>" class="nav-link <?= url_is('provincia*') ? 'active' : '' ?>">
                            <i class="bi bi-geo-alt-fill"></i>
                            <span>Provincias</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= site_url('canton') ?>" class="nav-link <?= url_is('canton*') ? 'active' : '' ?>">
                            <i class="bi bi-buildings-fill"></i>
                            <span>Cantones</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= site_url('parroquia') ?>" class="nav-link <?= url_is('parroquia*') ? 'active' : '' ?>">
                            <i class="bi bi-geo-fill"></i>
                            <span>Parroquias</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= site_url('tipoparroquia') ?>" class="nav-link <?= url_is('tipoparroquia*') ? 'active' : '' ?>">
                            <i class="bi bi-tag-fill"></i>
                            <span>Tipos de Parroquia</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= site_url('zona') ?>" class="nav-link <?= url_is('zona*') ? 'active' : '' ?>">
                            <i class="bi bi-pin-map-fill"></i>
                            <span>Zonas</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= site_url('distrito') ?>" class="nav-link <?= url_is('distrito*') ? 'active' : '' ?>">
                            <i class="bi bi-map"></i>
                            <span>Distritos</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= site_url('recintoelectoral') ?>" class="nav-link <?= url_is('recintoelectoral*') ? 'active' : '' ?>">
                            <i class="bi bi-building-check"></i>
                            <span>Recintos Electorales</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= site_url('meza') ?>" class="nav-link <?= url_is('meza*') ? 'active' : '' ?>">
                            <i class="bi bi-inbox-fill"></i>
                            <span>Mesas Electorales</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= site_url('acta') ?>" class="nav-link <?= url_is('acta*') ? 'active' : '' ?>">
                            <i class="bi bi-file-earmark-text-fill"></i>
                            <span>Actas Electorales</span>
                        </a>
                    </li>
                </ul>

                <div class="sidebar-heading">Candidaturas y Dignidades</div>
                <ul class="sidebar-menu">
                    <li class="nav-item">
                        <a href="<?= site_url('tipodignidad') ?>" class="nav-link <?= url_is('tipodignidad*') ? 'active' : '' ?>">
                            <i class="bi bi-award-fill"></i>
                            <span>Tipos de Dignidad</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= site_url('dignidad') ?>" class="nav-link <?= url_is('dignidad*') ? 'active' : '' ?>">
                            <i class="bi bi-person-badge-fill"></i>
                            <span>Dignidades / Candidaturas</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= site_url('dignidadacta') ?>" class="nav-link <?= url_is('dignidadacta*') ? 'active' : '' ?>">
                            <i class="bi bi-box-seam-fill"></i>
                            <span>Votos por Dignidad (Acta)</span>
                        </a>
                    </li>
                </ul>

                <div class="sidebar-heading">Seguridad y Acceso</div>
                <ul class="sidebar-menu">
                    <li class="nav-item">
                        <a href="<?= site_url('usuario') ?>" class="nav-link <?= url_is('usuario*') ? 'active' : '' ?>">
                            <i class="bi bi-person-lock"></i>
                            <span>Usuarios</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= site_url('rolusuario') ?>" class="nav-link <?= url_is('rolusuario*') ? 'active' : '' ?>">
                            <i class="bi bi-shield-lock-fill"></i>
                            <span>Roles de Usuario</span>
                        </a>
                    </li>
                </ul>

                <div class="sidebar-heading">Base de Datos</div>
                <ul class="sidebar-menu">
                    <li class="nav-item">
                        <div class="px-3 py-2 text-muted small">
                            <div class="text-light fw-semibold mb-1"><i class="bi bi-database-check text-success me-1"></i> MySQL Local</div>
                            <div>BD: <code class="text-info bg-dark px-1 rounded">dbcontrolelectoral</code></div>
                            <div class="mt-1">Tablas: <code>persona</code>, <code>sexo</code>, <code>provincia</code>, <code>canton</code>, <code>parroquia</code>, <code>zona</code>, <code>recintoelectoral</code>, <code>meza</code>, <code>acta</code>, <code>tipodignidad</code>, <code>dignidad</code>, <code>dignidadacta</code>, <code>usuario</code>, <code>rolusuario</code></div>
                        </div>
                    </li>
                </ul>
            </div>

            <!-- Pie del Menú Lateral -->
            <div class="sidebar-footer">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-success rounded-circle p-1"> </span>
                    <span class="text-truncate">CodeIgniter 4 &bull; MVC</span>
                </div>
            </div>
        </aside>

        <!-- Contenedor del Contenido Central -->
        <div id="content-wrapper">
            <main class="main-container">
                <div class="container-fluid">
                    <!-- Mensajes Flash de Notificación -->
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            <?= session()->getFlashdata('success') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <?= session()->getFlashdata('error') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('errors')): ?>
                        <div class="alert alert-warning alert-dismissible fade show shadow-sm" role="alert">
                            <div class="fw-bold mb-1"><i class="bi bi-exclamation-circle-fill me-2"></i>Por favor verifique los siguientes errores:</div>
                            <ul class="mb-0 ps-3">
                                <?php foreach (session()->getFlashdata('errors') as $err): ?>
                                    <li><?= esc($err) ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Inyección de la vista del módulo -->
                    <?= $this->renderSection('content') ?>
                </div>
            </main>

            <!-- Pie de página -->
            <footer class="text-center mt-auto">
                <div class="container-fluid">
                    <span>Sistema de Control Electoral &bull; Arquitectura MVC con CodeIgniter 4 y MySQL (<code>dbcontrolelectoral</code>)</span>
                </div>
            </footer>
        </div>
    </div>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script para control del menú vertical (desplegar / esconder y persistir estado) -->
    <script>
        (function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarCloseBtn = document.getElementById('sidebarCloseBtn');
            const appWrapper = document.getElementById('app-wrapper');
            const overlay = document.getElementById('sidebar-overlay');
            const storageKey = 'controlelectoral_sidebar_collapsed';

            // Restaurar estado guardado en Desktop
            if (window.innerWidth >= 992) {
                const isCollapsed = localStorage.getItem(storageKey) === 'true';
                if (isCollapsed) {
                    appWrapper.classList.add('sidebar-collapsed');
                }
            }

            // Alternar Menú
            function toggleSidebar() {
                if (window.innerWidth < 992) {
                    // Modo Móvil / Tablet
                    appWrapper.classList.toggle('mobile-open');
                } else {
                    // Modo Escritorio
                    appWrapper.classList.toggle('sidebar-collapsed');
                    const isCollapsed = appWrapper.classList.contains('sidebar-collapsed');
                    localStorage.setItem(storageKey, isCollapsed);
                }
            }

            function closeMobileSidebar() {
                appWrapper.classList.remove('mobile-open');
            }

            sidebarToggle?.addEventListener('click', toggleSidebar);
            sidebarCloseBtn?.addEventListener('click', closeMobileSidebar);
            overlay?.addEventListener('click', closeMobileSidebar);

            // Ajustar al redimensionar ventana
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 992) {
                    appWrapper.classList.remove('mobile-open');
                }
            });
        })();
    </script>

    <?= $this->renderSection('scripts') ?>
</body>
</html>
