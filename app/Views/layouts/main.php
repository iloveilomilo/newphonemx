<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NewPhoneMX - Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="<?= base_url('css/dashboard.css') ?>" rel="stylesheet">
</head>

<body>

    <div class="d-flex" id="wrapper">
        <div class="bg-dark text-white" id="sidebar-wrapper">
            <div class="sidebar-heading text-center py-4 primary-text fs-4 fw-bold text-uppercase border-bottom">
                <i class="fas fa-mobile-alt me-2"></i>NewPhoneMX
            </div>
            <div class="list-group list-group-flush my-3">
                <?php $rol = session('rol'); ?>

                <?php if ($rol == 'cliente' || empty($rol)): ?>
                    <a href="<?= base_url('dashboard/cliente') ?>" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                        <i class="fas fa-store me-2"></i>Tienda / Inicio
                    </a>
                <?php endif; ?>

                <?php if ($rol == 'cliente'): ?>
                    <a href="<?= base_url('carrito') ?>" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                        <i class="fas fa-shopping-cart me-2"></i>Mi Carrito
                    </a>
                    <a href="<?= base_url('dashboard/mis-compras') ?>" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                        <i class="fas fa-receipt me-2"></i>Mis Compras
                    </a>
                <?php endif; ?>

                <?php if ($rol == 'admin'): ?>
                    <a href="<?= base_url('admin/panel') ?>" class="list-group-item list-group-item-action bg-transparent second-text active">
                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                    </a>
                    <a href="<?= base_url('admin/productos') ?>" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                        <i class="fas fa-boxes me-2"></i>Inventario
                    </a>
                    <a href="<?= base_url('admin/usuarios') ?>" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                        <i class="fas fa-users me-2"></i>Usuarios
                    </a>
                    <a href="<?= base_url('admin/categorias') ?>" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                        <i class="fas fa-tags me-2"></i>Categorías
                    </a>
                    <a href="<?= base_url('admin/filtros') ?>" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                        <i class="fas fa-filter me-2"></i>Filtros Globales
                    </a>
                    <a href="#" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                      <i class="fas fa-comments me-2"></i>Soporte
                    </a>


                <?php endif; ?>

    <?php if ($rol == 'atencion_cliente'): ?>
        <a href="<?= base_url('soporte/soporte') ?>" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
            <i class="fas fa-headset me-2"></i>Responder Dudas
        </a>
        <!-- <a href="<?= base_url('soporte/alertas') ?>" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
            <i class="fas fa-bell me-2"></i>Alertas de Correo
        </a> -->
    <?php endif; ?>

                <a href="<?= base_url('logout') ?>" class="list-group-item list-group-item-action bg-transparent text-danger fw-bold border-top mt-3">
                    <i class="fas fa-power-off me-2"></i>Cerrar Sesión
                </a>
            </div>
        </div>
        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg navbar-light bg-light py-4 px-4 border-bottom">
                <div class="d-flex align-items-center">
                    <i class="fas fa-align-left primary-text fs-4 me-3" id="menu-toggle" style="cursor: pointer;"></i>
                    <h2 class="fs-2 m-0">Panel de Control</h2>
                </div>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <?php if(session('id')): ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle second-text fw-bold" href="#" id="navbarDropdown"
                                    role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-user me-2"></i><?= session('nombre') ?>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="#">Perfil</a></li>
                                    <li><a class="dropdown-item" href="<?= base_url('logout') ?>">Cerrar Sesión</a></li>
                                </ul>
                            </li>
                        <?php else: ?>
                            <li class="nav-item d-flex align-items-center mt-2 mt-lg-0">
                                <a href="<?= base_url('login') ?>" class="btn btn-outline-primary btn-sm me-2 fw-bold">Iniciar Sesión</a>
                                <a href="<?= base_url('registro') ?>" class="btn btn-primary btn-sm fw-bold">Crear Cuenta</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </nav>

            <div class="container-fluid px-4 py-4">
                <?= $this->renderSection('contenido') ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        var el = document.getElementById("wrapper");
        var toggleButton = document.getElementById("menu-toggle");

        toggleButton.onclick = function() {
            el.classList.toggle("toggled");
        };
    </script>
</body>

</html>