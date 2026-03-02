<?= $this->extend('layouts/main') ?>
<?= $this->section('contenido') ?>

<h2>Historial de Atenciones</h2>

<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Cliente</th>
            <th>Fecha</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>10</td>
            <td>Carlos Ruiz</td>
            <td>18/02/2026</td>
            <td><span class="badge bg-success">Resuelto</span></td>
        </tr>
    </tbody>
</table>

<?= $this->endSection() ?>
