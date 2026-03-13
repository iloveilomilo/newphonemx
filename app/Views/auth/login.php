<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - NewPhoneMX</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #654696; 
            --bg-light: #eaf0f7; 
        }
        body { 
            font-family: 'Poppins', sans-serif; 
            background-color: var(--bg-light); 
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }
        .card-login {
            width: 100%;
            max-width: 400px;
            border-radius: 15px;
            border: none;
            box-shadow: 0 8px 25px rgba(0,0,0,0.05); 
        }
        .form-control {
            border-radius: 10px; 
            padding: 10px 15px;
        }
        .btn-primary {
            background-color: var(--primary-color);
            border: none;
            border-radius: 50px; 
            font-weight: 600;
        }
        .btn-primary:hover {
            background-color: #52397a;
        }
        .btn-outline-secondary {
            border-radius: 50px; 
            font-weight: 600;
        }
        .text-primary-custom {
            color: var(--primary-color) !important;
        }
    </style>
  </head>
  <body>

    <div class="card card-login p-4 bg-white">
        <div class="text-center mb-4">
            <h3 class="fw-bold text-primary-custom">NewPhoneMX</h3>
            <p class="text-secondary">Bienvenido de nuevo</p>
        </div>
        
        <?php if(session()->getFlashdata('msg')):?>
            <div class="alert alert-danger text-center rounded-3">
                <?= session()->getFlashdata('msg') ?>
            </div>
        <?php endif;?>

        <form action="<?= base_url('/auth/login') ?>" method="post">
            <div class="mb-3">
                <label for="email" class="form-label text-muted small fw-bold">Correo Electrónico</label>
                <input type="email" name="email" class="form-control" id="email" placeholder="nombre@correo.com" required autofocus>
            </div>
            <div class="mb-4">
                <label for="password" class="form-label text-muted small fw-bold">Contraseña</label>
                <input type="password" name="password" class="form-control" id="password" required>
            </div>
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary py-2">Iniciar Sesión</button>
            </div>
            <div class="text-center mt-4">
                <p class="text-muted mb-2 small">¿No tienes cuenta?</p>
                <a href="<?= base_url('registro') ?>" class="btn btn-outline-secondary w-100 py-2">Crear cuenta nueva</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>