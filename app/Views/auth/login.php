<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - NewPhoneMX</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { 
            background: linear-gradient(135deg, #9aaaf1 0%, #ece0f8 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card-login {
            width: 100%;
            max-width: 400px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }
        .btn-primary {
            background-color: #764ba2;
            border: none;
        }
        .btn-primary:hover {
            background-color: #5b3a7d;
        }
    </style>
  </head>
  <body>

    <div class="card card-login p-4 bg-white">
        <div class="text-center mb-4">
            <h3 class="fw-bold text-dark">NewPhoneMX</h3>
            <p class="text-secondary">Bienvenido de nuevo</p>
        </div>
        
        <?php if(session()->getFlashdata('msg')):?>
            <div class="alert alert-danger text-center">
                <?= session()->getFlashdata('msg') ?>
            </div>
        <?php endif;?>

        <form action="<?= base_url('/auth/login') ?>" method="post">
            <div class="mb-3">
                <label for="email" class="form-label">Correo Electrónico</label>
                <input type="email" name="email" class="form-control" id="email" placeholder="nombre@correo.com" required autofocus>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" name="password" class="form-control" id="password" required>
            </div>
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary py-2">Iniciar Sesión</button>
            </div>
            <div class="text-center mt-4">
                <p class="text-muted mb-1">¿No tienes cuenta?</p>
                <a href="<?= base_url('registro') ?>" class="btn btn-outline-secondary w-100">Crear cuenta nueva</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>