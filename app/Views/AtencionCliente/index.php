<?= $this->extend('layouts/main') ?>
<?= $this->section('contenido') ?>

<h2>Panel Atención al Cliente</h2>

<div class="row mt-4">
    <div class="col-md-4">
        <div class="card text-center shadow-sm">
            <div class="card-body">
                <h5>Mensajes Nuevos</h5>
                <h3 class="text-danger">5</h3>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card text-center shadow-sm">
            <div class="card-body">
                <h5>En Proceso</h5>
                <h3 class="text-warning">3</h3>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card text-center shadow-sm">
            <div class="card-body">
                <h5>Resueltos</h5>
                <h3 class="text-success">12</h3>
            </div>
        </div>
    </div>
    <div style="margin-top:30px;">
    <a href="<?= base_url('admin/soporte/mensajes') ?>" class="btn btn-primary">
        Ver Mensajes
    </a>

    <a href="<?= base_url('admin/soporte/historial') ?>" class="btn btn-secondary">
        Ver Historial
    </a>

    <a href="<?= base_url('admin/soporte/responder') ?>" class="btn btn-success">
        Responder Mensaje
    </a>
</div>

</div>

<?= $this->endSection() ?>
