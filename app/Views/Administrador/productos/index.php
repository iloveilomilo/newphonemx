<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Inventario de Productos</h2>
    <a href="<?= base_url('admin/productos/crear') ?>" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Nuevo Producto
    </a>
</div>

<?php if(session()->getFlashdata('msg')):?>
    <div class="alert alert-success"><?= session()->getFlashdata('msg') ?></div>
<?php endif;?>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Imagen</th>
                    <th>Producto</th>
                    <th>Condición</th>
                    <th>Precio</th>
                    <th>Descuento</th>
                    <th>Stock</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($productos as $prod): ?>
                    <tr>
                        <td>
                            <img src="<?= base_url('uploads/productos/'.$prod['imagen_principal']) ?>" 
                                 alt="img" width="50" class="rounded">
                        </td>
                        <td>
                            <strong><?= $prod['nombre'] ?></strong><br>
                            <small class="text-muted">SKU: <?= $prod['sku'] ?? 'N/A' ?></small>
                        </td>
                        <td>
                            <?php if($prod['condicion'] == 'nuevo'): ?>
                                <span class="badge bg-success">Nuevo</span>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark">Reacondicionado</span>
                            <?php endif; ?>
                        </td>
                        
                        <td>
                            <?php if(isset($prod['descuento']) && $prod['descuento'] > 0): ?>
                                <?php 
                                    // Matemáticas: Precio - (Precio * (Descuento / 100))
                                    $descuentoDecimal = $prod['descuento'] / 100;
                                    $precioFinal = $prod['precio'] - ($prod['precio'] * $descuentoDecimal);
                                ?>
                                <span class="text-decoration-line-through text-muted small">$<?= number_format($prod['precio'], 2) ?></span><br>
                                <strong class="text-success">$<?= number_format($precioFinal, 2) ?></strong>
                            <?php else: ?>
                                <strong>$<?= number_format($prod['precio'], 2) ?></strong>
                            <?php endif; ?>
                        </td>

                        <td>
                            <?php if(isset($prod['descuento']) && $prod['descuento'] > 0): ?>
                                <span class="badge bg-danger">-<?= $prod['descuento'] ?>%</span>
                            <?php else: ?>
                                <span class="text-muted">0%</span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <?php if($prod['stock'] < 5): ?>
                                <span class="text-danger fw-bold"><?= $prod['stock'] ?> (Bajo)</span>
                            <?php else: ?>
                                <?= $prod['stock'] ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="#" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>