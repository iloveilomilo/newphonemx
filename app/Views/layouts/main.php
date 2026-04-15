<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NewPhoneMX - Panel de Control</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { background-color: #f4f7f6; }
        
        /* Efecto hover (al pasar el mouse) adaptado para el fondo azul pastel */
        .nav-link.menu-item { color: #5a6a85; transition: 0.3s; padding: 12px 15px; border-radius: 8px; font-weight: 600;}
        .nav-link.menu-item:hover { color: #0d6efd; background-color: rgba(13, 110, 253, 0.1); }
        
        /* Botón de cerrar sesión */
        .btn-logout { color: #ff6b6b; transition: 0.3s; padding: 12px 15px; border-radius: 8px; font-weight: 600;}
        .btn-logout:hover { background-color: rgba(255, 107, 107, 0.1); color: #ff4757; }
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
        
        <?php if(session('id')): ?>
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


    <?php if (session('rol') == 'atencion_cliente' || session('rol') == 'admin' || session('rol') == null): ?>
        <a href="<?= base_url('admin/soporte') ?>" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
            <i class="fas fa-headset me-2"></i>Responder Dudas
        </a>
         <!-- <a href="<?= base_url('soporte/alertas') ?>" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
            <i class="fas fa-bell me-2"></i>Alertas de Correo
        </a> -->

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

                <a href="<?= base_url('logout') ?>" class="list-group-item list-group-item-action bg-transparent text-danger fw-bold border-top mt-3">
                    <i class="fas fa-power-off me-2"></i>Cerrar Sesión
                </a>
            </div>
        </div>
        <?php endif; ?>
        <div id="page-content-wrapper" class="w-100">
            <nav class="navbar navbar-expand-lg navbar-light bg-light py-3 px-4 border-bottom">
                
                <div class="d-flex align-items-center">
                    <?php if(session('id')): ?>
                        <i class="fas fa-align-left primary-text fs-4 me-3" id="menu-toggle" style="cursor: pointer;"></i>
                        <h2 class="fs-4 m-0 fw-bold text-dark">Panel de Control</h2>
                    <?php else: ?>
                        <a href="<?= base_url('/') ?>" class="text-decoration-none d-flex align-items-center">
                            <i class="fas fa-mobile-alt fs-3 me-2" style="color: #764ba2;"></i>
                            <h2 class="fs-3 m-0 fw-bold text-uppercase" style="color: #764ba2; letter-spacing: 1px;">NEWPHONEMX</h2>
                        </a>
                    <?php endif; ?>
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
            </div>

            <?= $this->renderSection('contenido') ?>
            
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>