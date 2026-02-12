<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>

<div class="row g-3 my-2">
    <div class="col-md-3">
        <div class="p-3 bg-white shadow-sm d-flex justify-content-around align-items-center rounded">
            <div>
                <h3 class="fs-2">0</h3>
                <p class="fs-5">Productos</p>
            </div>
            <i class="fas fa-mobile-alt fs-1 primary-text border rounded-full secondary-bg p-3"></i>
        </div>
    </div>

    <div class="col-md-3">
        <div class="p-3 bg-white shadow-sm d-flex justify-content-around align-items-center rounded">
            <div>
                <h3 class="fs-2">0</h3>
                <p class="fs-5">Ventas</p>
            </div>
            <i class="fas fa-hand-holding-usd fs-1 primary-text border rounded-full secondary-bg p-3"></i>
        </div>
    </div>
</div>

<div class="row my-5">
    <h3 class="fs-4 mb-3">Órdenes Recientes</h3>
    <div class="col">
        <div class="table-responsive bg-white shadow-sm rounded p-4">
            <p>Aquí irá la tabla de pedidos...</p>
        </div>
    </div>
</div>

<?= $this->endSection() ?>