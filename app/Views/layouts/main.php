<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NewPhoneMX - Panel</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: #764ba2;
            --secondary-color: #667eea;
            --bg-light: #f4f7f6;
            --sidebar-width: 250px;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #e0eafc 0%, #cfdef3 100%);
            background-attachment: fixed;
            min-height: 100vh;
            margin: 0;
        }

        /* Sidebar Estilizado */
        #sidebar-wrapper {
            width: var(--sidebar-width);
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-right: 1px solid rgba(0, 0, 0, 0.05);
            transition: margin .25s ease-out;
            min-height: 100vh;
            box-shadow: 4px 0 15px rgba(0, 0, 0, 0.03);
            margin-left: calc(-1 * var(--sidebar-width));
        }

        .sidebar-heading {
            padding: 2rem 1.25rem;
            font-size: 1.2rem;
            color: var(--primary-color);
            letter-spacing: 1px;
        }

        #sidebar-wrapper .list-group-item {
            border: none;
            padding: 12px 25px;
            color: #555;
            font-size: 0.95rem;
            background: transparent;
            transition: all 0.3s;
            border-radius: 0 50px 50px 0;
            margin-right: 10px;
            margin-bottom: 5px;
        }

        #sidebar-wrapper .list-group-item i {
            width: 25px;
            color: var(--secondary-color);
        }

        #sidebar-wrapper .list-group-item:hover {
            background-color: rgba(118, 75, 162, 0.1);
            color: var(--primary-color);
            padding-left: 35px;
        }

        #sidebar-wrapper .list-group-item.active {
            background: linear-gradient(45deg, var(--secondary-color), var(--primary-color));
            color: white !important;
            box-shadow: 0 4px 12px rgba(118, 75, 162, 0.3);
        }

        /* --- Navbar y Contenido --- */
        .navbar {
            background: rgba(255, 255, 255, 0.7) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.5) !important;
            z-index: 1000;
        }

        #page-content-wrapper {
            min-width: 0;
            flex: 1;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .logout-item {
            color: #dc3545 !important;
            margin-top: 20px;
        }

        .logout-item:hover {
            background-color: #fff1f0 !important;
        }

        #wrapper.toggled #sidebar-wrapper {
            margin-left: 0;
        }

        @media (min-width: 768px) {
            #sidebar-wrapper {
                margin-left: 0;
            }

            #wrapper.toggled #sidebar-wrapper {
                margin-left: calc(-1 * var(--sidebar-width));
            }
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row flex-nowrap">
        
        <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 min-vh-100 d-flex flex-column justify-content-between" 
             style="background: linear-gradient(180deg, #d4e4ff 0%, #e8f0fe 100%); box-shadow: 4px 0 10px rgba(0,0,0,0.03); z-index: 10;">
            
            <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-4 w-100">
                
                <a href="<?= base_url('admin/soporte') ?>" class="d-flex align-items-center pb-3 mb-md-0 me-md-auto text-decoration-none w-100 justify-content-center flex-column text-center">
                    <i class="fas fa-mobile-alt fs-1 mb-2 text-primary"></i>
                    <span class="fs-5 fw-bold text-primary" style="letter-spacing: 1px;">NEWPHONEMX</span>
                </a>
                
                <hr class="w-100 border-secondary opacity-25 my-3">

                <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start w-100" id="menu">
                    
                    <?php 
                        $miRol = $rol ?? session()->get('rol') ?? 'atencion_cliente';
                        if ($miRol == 'atencion_cliente' || $miRol == 'admin'): 
                    ?>
                        
                        <li class="nav-item w-100 mb-1">
                            <a href="<?= base_url('admin/soporte') ?>" class="nav-link menu-item align-middle w-100">
                                <i class="fs-5 fas fa-headset me-2 text-center" style="width: 25px;"></i> 
                                <span class="d-none d-sm-inline">Panel de Control</span>
                            </a>
                        </li>
                        
                        <li class="nav-item w-100 mb-1">
                            <a href="<?= base_url('admin/soporte/mensajes') ?>" class="nav-link menu-item align-middle w-100">
                                <i class="fs-5 fas fa-envelope me-2 text-center" style="width: 25px;"></i> 
                                <span class="d-none d-sm-inline">Ver Mensajes</span>
                            </a>
                        </li>
                        
                        <li class="nav-item w-100 mb-1">
                            <a href="<?= base_url('admin/soporte/historial') ?>" class="nav-link menu-item align-middle w-100">
                                <i class="fs-5 fas fa-archive me-2 text-center" style="width: 25px;"></i> 
                                <span class="d-none d-sm-inline">Ver Historial</span>
                            </a>
                        </li>

                    <?php endif; ?>
                </ul>
            </div>

            <div class="px-3 pb-4 w-100">
                <hr class="border-secondary opacity-25">
                <a href="<?= base_url('logout') ?>" class="nav-link btn-logout align-middle text-center text-sm-start w-100">
                    <i class="fs-5 fas fa-power-off me-2 text-center" style="width: 25px;"></i> 
                    <span class="d-none d-sm-inline">Cerrar Sesión</span>
                </a>
            </div>
        </div>

        <div class="col py-3 px-4" style="background-color: #f4f7f6;">
            
            <div class="d-flex justify-content-between align-items-center bg-white p-3 rounded shadow-sm mb-4">
                <h5 class="mb-0 text-dark fw-bold d-flex align-items-center">
                    <i class="fas fa-bars text-primary me-3"></i> Menú Principal
                </h5>
                
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                         <div class="text-end me-2">
                             <span class="d-block fw-bold text-secondary lh-1"><?= session()->get('nombre') ?? 'Paola' ?></span>
                             <small class="text-primary fw-bold" style="font-size: 0.75rem;">Atención al Cliente</small>
                          </div>
                         <i class="fas fa-user-circle fs-2 text-primary"></i>
                     </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="dropdownUser">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-user text-muted me-2"></i> Mi Perfil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger fw-bold" href="<?= base_url('logout') ?>"><i class="fas fa-power-off me-2"></i> Cerrar Sesión</a></li>
    <div class="d-flex" id="wrapper">

        <?php if (session('id')): ?>
            <div id="sidebar-wrapper">
                <div class="sidebar-heading text-center fw-bold text-uppercase border-bottom">
                    <i class="fas fa-mobile-alt me-2"></i>NewPhoneMX
                </div>
                <div class="list-group list-group-flush my-3">
                    <?php $rol = session('rol'); ?>

                    <a href="<?= base_url('dashboard/cliente') ?>" class="list-group-item list-group-item-action fw-bold <?= (current_url() == base_url('dashboard/cliente')) ? 'active' : '' ?>">
                        <i class="fas fa-store me-2"></i>Tienda / Inicio
                    </a>

                    <?php if ($rol == 'cliente'): ?>
                        <a href="<?= base_url('carrito') ?>" class="list-group-item list-group-item-action fw-bold <?= (current_url() == base_url('carrito')) ? 'active' : '' ?>">
                            <i class="fas fa-shopping-cart me-2"></i>Mi Carrito
                        </a>
                        <a href="<?= base_url('mis-compras') ?>" class="list-group-item list-group-item-action fw-bold <?= (current_url() == base_url('mis-compras')) ? 'active' : '' ?>">
                            <i class="fas fa-receipt me-2"></i>Mis Compras
                        </a>
                        <a href="<?= base_url('mis-preguntas') ?>" class="list-group-item list-group-item-action fw-bold <?= (current_url() == base_url('mis-preguntas')) ? 'active' : '' ?>">
                            <i class="fas fa-question-circle me-2"></i>Mis Preguntas
                        </a>
                    <?php endif; ?>

                    <?php if ($rol == 'admin'): ?>


                        <a href="<?= base_url('admin/panel') ?>" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
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

                        <div class="list-group-item bg-transparent text-muted fw-bold text-uppercase mt-3 border-0" style="font-size: 0.70rem; letter-spacing: 0.05rem;">
                            Soporte
                        </div>

                        <a href="<?= base_url('admin/soporte') ?>" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                            <i class="fas fa-envelope me-2"></i>Ver Mensajes
                        </a>
                    <?php endif; ?>


                    <?php if (session('rol') == 'atencion_cliente' || session('rol') == null): ?>
                        <a href="<?= base_url('admin/soporte') ?>" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                            <i class="fas fa-headset me-2"></i>Responder Dudas
                        </a>
                        <a href="<?= base_url('admin/soporte/mensajes') ?>" class="list-group-item list-group-item-action bg-transparent second-text fw-bold" style="padding-left: 2.5rem;">
                            <i class="fas fa-envelope me-2"></i>Ver Mensajes
                        </a>

                        <a href="<?= base_url('admin/soporte/historial') ?>" class="list-group-item list-group-item-action bg-transparent second-text fw-bold" style="padding-left: 2.5rem;">
                            <i class="fas fa-history me-2"></i>Ver Historial
                        </a>

                        <a href="<?= base_url('admin/soporte/responder') ?>" class="list-group-item list-group-item-action bg-transparent second-text fw-bold" style="padding-left: 2.5rem;">
                            <i class="fas fa-reply me-2"></i>Responder Mensaje
                        </a>
                    <?php endif; ?>

                    <?php if ($rol == 'atencion_cliente'): ?>
                        <a href="<?= base_url('soporte/soporte') ?>" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                            <i class="fas fa-headset me-2"></i>Responder Dudas
                        </a>
                    <?php endif; ?>
                <?php if (session('rol') == 'atencion_cliente' || session('rol') == null): ?>
                    <a href="<?= base_url('admin/soporte') ?>" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                        <i class="fas fa-headset me-2"></i>Responder Dudas
                    </a>
                    <a href="<?= base_url('admin/soporte/mensajes') ?>" class="list-group-item list-group-item-action bg-transparent second-text fw-bold" style="padding-left: 2.5rem;">
                        <i class="fas fa-envelope me-2"></i>Ver Mensajes
                    </a>
                    
                    <a href="<?= base_url('admin/soporte/historial') ?>" class="list-group-item list-group-item-action bg-transparent second-text fw-bold" style="padding-left: 2.5rem;">
                        <i class="fas fa-history me-2"></i>Ver Historial
                    </a>
                    
                    <a href="<?= base_url('admin/soporte/responder') ?>" class="list-group-item list-group-item-action bg-transparent second-text fw-bold" style="padding-left: 2.5rem;">
                        <i class="fas fa-reply me-2"></i>Responder Mensaje
                    </a>
                <?php endif; ?>
                
                <?php if ($rol == 'atencion_cliente'): ?>
                    <a href="<?= base_url('soporte/soporte') ?>" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                        <i class="fas fa-headset me-2"></i>Responder Dudas
                    </a>
                <?php endif; ?>
                    <?php if (in_array($rol, ['atencion_cliente', 'admin'])): ?>

                    <?php endif; ?>


                    <?php if (session('rol') == 'atencion_cliente' || session('rol') == null): ?>
                        <a href="<?= base_url('admin/soporte') ?>" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                            <i class="fas fa-headset me-2"></i>Responder Dudas
                        </a>
                        <a href="<?= base_url('admin/soporte/mensajes') ?>" class="list-group-item list-group-item-action bg-transparent second-text fw-bold" style="padding-left: 2.5rem;">
                            <i class="fas fa-envelope me-2"></i>Ver Mensajes
                        </a>

                        <a href="<?= base_url('admin/soporte/historial') ?>" class="list-group-item list-group-item-action bg-transparent second-text fw-bold" style="padding-left: 2.5rem;">
                            <i class="fas fa-history me-2"></i>Ver Historial
                        </a>

                        <a href="<?= base_url('admin/soporte/responder') ?>" class="list-group-item list-group-item-action bg-transparent second-text fw-bold" style="padding-left: 2.5rem;">
                            <i class="fas fa-reply me-2"></i>Responder Mensaje
                        </a>
                    <?php endif; ?>

                    <?php if ($rol == 'atencion_cliente'): ?>
                        <a href="<?= base_url('soporte/soporte') ?>" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                            <i class="fas fa-headset me-2"></i>Responder Dudas
                        </a>
                    <?php endif; ?>

                    <a href="<?= base_url('logout') ?>" class="list-group-item list-group-item-action fw-bold logout-item border-top mt-3">
                        <i class="fas fa-power-off me-2"></i>Cerrar Sesión
                    </a>
                </div>
            </div>
        <?php endif; ?>

        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg navbar-light py-3 px-4">
                <div class="d-flex align-items-center">
                    <?php if (session('id')): ?>
                        <i class="fas fa-align-left fs-4 me-3" id="menu-toggle" style="cursor: pointer; color: var(--primary-color);"></i>
                        <h2 class="fs-4 m-0 fw-bold text-dark">Panel de Control</h2>
                    <?php else: ?>
                        <a href="<?= base_url('/') ?>" class="text-decoration-none d-flex align-items-center">
                            <i class="fas fa-mobile-alt fs-3 me-2" style="color: var(--primary-color);"></i>
                            <h2 class="fs-3 m-0 fw-bold text-uppercase" style="color: var(--primary-color); letter-spacing: 1px;">NEWPHONEMX</h2>
                        </a>
                    <?php endif; ?>
                </div>

                <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
                        <?php if (session('id')): ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle fw-bold text-dark d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <?php
                                    $foto_nav = session('foto_perfil') ? session('foto_perfil') : 'default.png';
                                    ?>
                                    <img src="<?= base_url('uploads/perfiles/' . $foto_nav) ?>" alt="Perfil" class="rounded-circle border border-primary shadow-sm me-2" style="width: 35px; height: 35px; object-fit: cover;">
                                    <?= session('nombre') ?>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="navbarDropdown">
                                    <li>
                                        <a class="dropdown-item" href="<?= (session('rol') == 'admin' || session('rol') == 'atencion_cliente') ? base_url('admin/perfil') : base_url('perfil') ?>">
                                            <i class="fas fa-user me-2"></i> Perfil
                                        </a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item text-danger" href="<?= base_url('logout') ?>"><i class="fas fa-sign-out-alt me-2"></i> Salir</a></li>
                                </ul>
                            </li>
                        <?php else: ?>
                            <li class="nav-item me-2 mb-2 mb-lg-0">
                                <a href="<?= base_url('login') ?>" class="btn btn-outline-primary fw-bold px-4 rounded-pill">
                                    Iniciar Sesión
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('registro') ?>" class="btn btn-primary fw-bold px-4 rounded-pill shadow-sm" style="background-color: var(--primary-color); border-color: var(--primary-color);">
                                    Crear Cuenta
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </nav>

            <div class="container-fluid px-4 py-4">

                <?php if(session()->getFlashdata('alerta_intruso')): ?>
    <div class="alert alert-danger alert-dismissible fade show fw-bold text-center shadow-sm mb-4" role="alert">
        <i class="fas fa-user-secret me-2 fs-4 align-middle"></i>
        <?= session()->getFlashdata('alerta_intruso') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>
                <?= $this->renderSection('contenido') ?>
            </div>

            <footer class="mt-auto py-3 text-center border-top" style="background: rgba(255, 255, 255, 0.5); backdrop-filter: blur(10px);">
                <div class="container-fluid">
                    <span class="text-muted small fw-bold">
                        &copy; <?= date('Y') ?> NewPhoneMX. Todos los derechos reservados.
                    </span>
                </div>
            </footer>
        </div>
    </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        var el = document.getElementById("wrapper");
        var toggleButton = document.getElementById("menu-toggle");
        if (toggleButton) {
            toggleButton.onclick = function() {
                el.classList.toggle("toggled");
            };
        }
    </script>
</body>

</body>
</html>