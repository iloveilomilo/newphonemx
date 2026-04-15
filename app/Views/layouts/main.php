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
            --sidebar-width: 250px;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #e0eafc 0%, #cfdef3 100%);
            background-attachment: fixed;
            min-height: 100vh;
            margin: 0;
        }

        #sidebar-wrapper {
            width: var(--sidebar-width);
            background: rgba(255, 255, 255, 0.95);
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
            background: transparent;
            transition: all 0.3s;
            border-radius: 0 50px 50px 0;
            margin-right: 10px;
            margin-bottom: 5px;
        }

        #sidebar-wrapper .list-group-item:hover {
            background-color: rgba(118, 75, 162, 0.1);
            color: var(--primary-color);
            padding-left: 35px;
        }

        #sidebar-wrapper .list-group-item.active {
            background: linear-gradient(45deg, var(--secondary-color), var(--primary-color));
            color: white !important;
        }

        #page-content-wrapper {
            min-width: 0;
            flex: 1;
        }

        #wrapper.toggled #sidebar-wrapper {
            margin-left: 0;
        }

        @media (min-width: 768px) {
            #sidebar-wrapper { margin-left: 0; }
            #wrapper.toggled #sidebar-wrapper { margin-left: calc(-1 * var(--sidebar-width)); }
        }
    </style>
</head>

<body>
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

                    <?php if ($rol == 'atencion_cliente'): ?>
                        <a href="<?= base_url('admin/soporte') ?>" class="list-group-item list-group-item-action fw-bold <?= (strpos(current_url(), 'admin/soporte') !== false && !strpos(current_url(), 'mensajes') && !strpos(current_url(), 'historial') && !strpos(current_url(), 'responder')) ? 'active' : '' ?>">
                            <i class="fas fa-headset me-2"></i>Responder Dudas
                        </a>
                        <a href="<?= base_url('admin/soporte/mensajes') ?>" class="list-group-item list-group-item-action fw-bold <?= (strpos(current_url(), 'mensajes')) ? 'active' : '' ?>">
                            <i class="fas fa-envelope me-2"></i>Ver Mensajes
                        </a>
                        <a href="<?= base_url('admin/soporte/historial') ?>" class="list-group-item list-group-item-action fw-bold <?= (strpos(current_url(), 'historial')) ? 'active' : '' ?>">
                            <i class="fas fa-history me-2"></i>Ver Historial
                        </a>
                        <a href="<?= base_url('admin/soporte/responder') ?>" class="list-group-item list-group-item-action fw-bold <?= (strpos(current_url(), 'responder')) ? 'active' : '' ?>">
                            <i class="fas fa-reply me-2"></i>Responder Mensaje
                        </a>
                    <?php endif; ?>

                    <?php if ($rol == 'admin'): ?>
                        <a href="<?= base_url('admin/panel') ?>" class="list-group-item list-group-item-action fw-bold">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                        <?php endif; ?>

                    <a href="<?= base_url('logout') ?>" class="list-group-item list-group-item-action fw-bold text-danger border-top mt-3">
                        <i class="fas fa-power-off me-2"></i>Cerrar Sesión
                    </a>
                </div>
            </div>
        <?php endif; ?>

        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom py-3 px-4 shadow-sm">
                <i class="fas fa-align-left fs-4 me-3" id="menu-toggle" style="cursor: pointer; color: var(--primary-color);"></i>
                <h2 class="fs-4 m-0 fw-bold">Panel de Control</h2>
                <div class="ms-auto d-flex align-items-center">
                    <span class="me-2 fw-bold"><?= session('nombre') ?></span>
                    <i class="fas fa-user-circle fs-3 text-secondary"></i>
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
        if (toggleButton) {
            toggleButton.onclick = function() { el.classList.toggle("toggled"); };
        }
    </script>
</body>
</html>