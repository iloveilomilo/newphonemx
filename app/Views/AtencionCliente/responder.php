<?= $this->extend('layouts/main') ?>
<?= $this->section('contenido') ?>

<h2>Responder Mensaje</h2>

<form>
    <div class="mb-3">
        <label>Cliente</label>
        <input type="text" class="form-control" value="María López" readonly>
    </div>

    <div class="mb-3">
        <label>Mensaje</label>
        <textarea class="form-control" rows="3" readonly>
No funciona mi equipo
        </textarea>
    </div>

    <div class="mb-3">
        <label>Respuesta</label>
        <textarea class="form-control" rows="4"></textarea>
    </div>

    <button class="btn btn-success">Enviar Respuesta</button>
</form>

<?= $this->endSection() ?>
