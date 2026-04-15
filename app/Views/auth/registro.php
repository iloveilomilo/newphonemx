<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registro - NewPhoneMX</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .card-registro {
            width: 100%;
            max-width: 500px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }
        .btn-primary { background-color: #764ba2; border: none; }
        .btn-primary:hover { background-color: #5b3a7d; }
    </style>
  </head>
  <body>

    <div class="card card-registro p-4 bg-white">
        <div class="text-center mb-4">
            <h3 class="fw-bold text-dark">Únete a NewPhoneMX</h3>
            <p class="text-secondary">Crea tu cuenta para comprar</p>
        </div>
        
        <?php if(session()->getFlashdata('error')):?>
            <div class="alert alert-danger text-center">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif;?>

        <form action="<?= base_url('/auth/guardar_registro') ?>" method="post">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nombre(s)</label>
                    <input type="text" name="nombre" class="form-control" required autofocus>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Apellidos</label>
                    <input type="text" name="apellidos" class="form-control" required>
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Correo Electrónico</label>
                <input type="email" name="correo" class="form-control" placeholder="ejemplo@correo.com" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Teléfono (Opcional)</label>
                <input type="tel" name="telefono" class="form-control" placeholder="10 dígitos">
            </div>
            
            <div class="mb-4">
                <label class="form-label">Contraseña</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary py-2 fw-bold">Registrarme</button>
                <a href="<?= base_url('login') ?>" class="btn btn-outline-secondary mt-2">Ya tengo cuenta. Iniciar Sesión</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>