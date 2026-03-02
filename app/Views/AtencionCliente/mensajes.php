<?= $this->extend('layouts/main') ?>
<?= $this->section('contenido') ?>

<h2>Bandeja de Mensajes</h2>

<table class="table table-hover mt-3">
    <thead>
        <tr>
            <th>ID</th>
            <th>Cliente</th>
            <th>Asunto</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>1</td>
            <td>María López</td>
            <td>No funciona mi equipo</td>
            <td><span class="badge bg-danger">Nuevo</span></td>
        </tr>
        <tr>
            <td>2</td>
            <td>Juan Pérez</td>
            <td>Garantía</td>
            <td><span class="badge bg-warning">En proceso</span></td>
        </tr>
    </tbody>
</table>

<?= $this->endSection() ?>
