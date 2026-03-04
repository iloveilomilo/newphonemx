<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>

<div class="d-flex justify-content-between mb-3">
    <h3>Nuevo Producto</h3>
    <a href="<?= base_url('admin/productos') ?>" class="btn btn-secondary">Volver</a>
</div>

<?php if(session()->getFlashdata('msg')):?>
    <div class="alert alert-danger"><?= session()->getFlashdata('msg') ?></div>
<?php endif;?>

<form action="<?= base_url('admin/productos/guardar') ?>" method="post" enctype="multipart/form-data">
    
    <div class="row">
        <div class="col-md-8">
            <div class="card p-4 shadow-sm mb-4">
                <h5 class="card-title mb-3">Información General</h5>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Nombre del Producto</label>
                        <input type="text" name="nombre" class="form-control" required placeholder="Ej: iPhone 15 Pro">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Marca</label>
                        <input type="text" name="marca" class="form-control" required placeholder="Ej: Apple">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Categoría</label>
                        <select name="categoria_id" class="form-select" required>
                            <option value="">Selecciona...</option>
                            <?php foreach($categorias as $cat): ?>
                                <option value="<?= $cat['id'] ?>"><?= $cat['nombre'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">SKU (Código único)</label>
                        <input type="text" class="form-control bg-light text-muted" value="Automático (Ej: NP-APP-N-0001)" readonly>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Descripción</label>
                    <textarea name="descripcion" class="form-control" rows="3"></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Imagen Principal</label>
                    <input type="file" name="imagen" class="form-control" accept="image/*" required>
                </div>
            </div>

            <div class="card p-4 shadow-sm">
                <h5 class="card-title mb-3">Especificaciones Técnicas</h5>
                <p class="text-muted small">Llena solo las que apliquen.</p>
                <div class="row">
                    <?php foreach($filtros as $filtro): ?>
                        <div class="col-md-6 mb-3 contenedor-filtro" data-nombre="<?= strtolower($filtro['nombre']) ?>">
                            <label class="form-label"><?= $filtro['nombre'] ?></label>
                            <input type="text" name="filtro_<?= $filtro['id'] ?>" class="form-control" placeholder="Valor...">
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-4 shadow-sm bg-light">
                <h5 class="card-title">Inventario</h5>
                <hr>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Condición</label>
                    <select name="condicion" id="selectCondicion" class="form-select" required onchange="toggleReacondicionado()">
                        <option value="nuevo">Nuevo</option>
                        <option value="reacondicionado">Reacondicionado</option>
                    </select>
                </div>

                <div class="mb-4 bg-white p-3 border rounded">
                    <label class="form-label fw-bold text-primary mb-2">¿Qué incluye?</label>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="caja_original" value="1" id="cajaCheck">
                        <label class="form-check-label" for="cajaCheck">
                            Caja original
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="cable_cargador" value="1" id="cableCheck">
                        <label class="form-check-label" for="cableCheck">
                            Cable cargador
                        </label>
                    </div>
                </div>

                <div class="mb-4 bg-white p-3 border rounded">
                    <label class="form-label fw-bold text-primary mb-2">Conectividad</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="esim" value="1" id="esimCheck">
                        <label class="form-check-label" for="esimCheck">
                            Solo eSIM (Sin ranura para chip físico)
                        </label>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Precio ($)</label>
                    <input type="number" step="0.01" name="precio" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Descuento (%)</label>
                    <input type="number" name="descuento" class="form-control" value="0">
                </div>

                <div class="mb-3">
                    <label class="form-label">Stock Disponible</label>
                    <input type="number" name="stock" class="form-control" required>
                </div>

                <div class="d-grid mt-4">
                    <button type="submit" class="btn btn-primary btn-lg">Publicar Producto</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    function toggleReacondicionado() {
        let condicion = document.getElementById('selectCondicion').value;
        let filtros = document.querySelectorAll('.contenedor-filtro');

        filtros.forEach(div => {
            let nombre = div.getAttribute('data-nombre');
            if ((nombre.includes('estética') || nombre.includes('estetica') || nombre.includes('batería') || nombre.includes('bateria')) && condicion === 'nuevo') {
                div.style.display = 'none';
                div.querySelector('input').value = ''; 
            } else {
                div.style.display = 'block';
            }
        });
    }

    // Ejecutar al cargar por si acaso
    document.addEventListener("DOMContentLoaded", toggleReacondicionado);
</script>

<?= $this->endSection() ?>