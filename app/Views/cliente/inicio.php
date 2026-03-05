<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4 mt-2">
        <div>
            <h2 class="fw-bold text-dark mb-0">Tienda / Catálogo</h2>
            <p class="text-muted small">Celulares disponibles para entrega inmediata.</p>
        </div>
        
        <form class="d-flex" role="search" action="<?= base_url('dashboard/cliente') ?>" method="get">
            <input class="form-control me-2" type="search" name="q" value="<?= esc($busqueda ?? '') ?>" placeholder="Buscar..." aria-label="Search">
            <button class="btn btn-outline-primary" type="submit"><i class="fas fa-search"></i></button>
        </form>
    </div>

    <div class="row">
        <?php if (!empty($productos)): ?>
            <?php foreach ($productos as $prod): ?>
                <?php 
                    // Cálculo de precios
                    $precioOriginal = $prod['precio'];
                    $descuento = $prod['descuento'] ?? 0;
                    $precioFinal = $precioOriginal;
                    $tieneDescuento = $descuento > 0;

                    if ($tieneDescuento) {
                        $precioFinal = $precioOriginal - ($precioOriginal * ($descuento / 100));
                    }
                ?>
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card h-100 shadow-sm border-0 hover-effect">
                        
                        <div class="position-absolute top-0 end-0 m-2 d-flex flex-column align-items-end">
                            <span class="badge <?= strtolower($prod['condicion']) == 'nuevo' ? 'bg-success' : 'bg-warning text-dark' ?> mb-1">
                                <?= strtoupper($prod['condicion']) ?>
                            </span>
                            <?php if ($tieneDescuento): ?>
                                <span class="badge bg-danger">
                                    -<?= $descuento ?>%
                                </span>
                            <?php endif; ?>
                        </div>

                        <div class="text-center p-3 bg-light rounded-top">
                            <img src="<?= base_url('uploads/productos/' . $prod['imagen_principal']) ?>" 
                                 class="img-fluid" 
                                 style="height: 200px; object-fit: contain;">
                        </div>
                        
                        <div class="card-body d-flex flex-column">
                            <small class="text-muted text-uppercase fw-bold"><?= $prod['marca'] ?> | SKU: <?= $prod['sku'] ?></small>
                            <h5 class="card-title fw-bold text-dark"><?= $prod['nombre'] ?></h5>
                            
                            <div class="mt-auto">
                                <?php if ($tieneDescuento): ?>
                                    <small class="text-muted text-decoration-line-through">$<?= number_format($precioOriginal, 2) ?></small>
                                    <h4 class="text-success fw-bold mb-3">$<?= number_format($precioFinal, 2) ?></h4>
                                <?php else: ?>
                                    <h4 class="text-primary fw-bold mb-3">$<?= number_format($precioFinal, 2) ?></h4>
                                <?php endif; ?>
                                
                                <div class="d-grid gap-2">
                                    <button type="button" class="btn btn-outline-secondary btn-sm btn-detalle"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#productoModal"
                                            data-id="<?= $prod['inventario_id'] ?>" 
                                            data-nombre="<?= $prod['nombre'] ?>"
                                            data-marca="<?= $prod['marca'] ?>"
                                            data-sku="<?= $prod['sku'] ?>"
                                            data-precio-original="<?= number_format($precioOriginal, 2) ?>"
                                            data-precio-final="<?= number_format($precioFinal, 2, '.', '') ?>"
                                            data-descuento="<?= $descuento ?>"
                                            data-desc="<?= $prod['descripcion'] ?>"
                                            data-condicion="<?= $prod['condicion'] ?>"
                                            data-stock="<?= $prod['stock'] ?>"
                                            data-img="<?= $prod['imagen_principal'] ?>"> Ver Detalles
                                    </button>
                                    
                                    <button class="btn btn-primary btn-sm btn-agregar-carrito" 
                                            data-id="<?= $prod['inventario_id'] ?>" 
                                            data-nombre="<?= $prod['nombre'] ?>" 
                                            data-precio="<?= number_format($precioFinal, 2, '.', '') ?>" 
                                            data-img="<?= $prod['imagen_principal'] ?>">
                                        <i class="fas fa-cart-plus me-2"></i>Agregar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<div class="modal fade" id="productoModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header border-0">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body pb-5">
        <div class="row">
            <div class="col-md-6 text-center mb-3 mb-md-0">
                <img id="modalImg" src="" class="img-fluid rounded" style="max-height: 350px;">
            </div>
            <div class="col-md-6">
                <small id="modalMarca" class="text-uppercase text-muted fw-bold"></small> <small class="text-muted">| SKU: <span id="modalSku"></span></small>
                <h2 id="modalNombre" class="fw-bold mb-2"></h2>
                
                <div class="mb-3">
                    <span id="modalCondicion" class="badge bg-light text-dark border"></span>
                    <span class="text-success fw-bold ms-2"><i class="fas fa-check-circle"></i> Stock: <span id="modalStock"></span></span>
                </div>

                <div id="modalPriceContainer"></div>
                
                <h6 class="fw-bold mt-3">Descripción:</h6>
                <p id="modalDesc" class="text-muted small" style="line-height: 1.6;"></p>

                <div class="d-grid gap-2 mt-4">
                    <button id="btnModalAgregar" class="btn btn-primary btn-lg btn-agregar-carrito">
                        <i class="fas fa-shopping-cart me-2"></i> Añadir al Carrito
                    </button>
                    <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Seguir viendo</button>
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- 1. Lógica Visual del Modal (Lo que tú ya tenías) ---
        const botonesDetalle = document.querySelectorAll('.btn-detalle');
        
        botonesDetalle.forEach(boton => {
            boton.addEventListener('click', function() {
                const nombre = this.getAttribute('data-nombre');
                const marca = this.getAttribute('data-marca');
                const sku = this.getAttribute('data-sku');
                const precioOriginal = this.getAttribute('data-precio-original');
                const precioFinal = this.getAttribute('data-precio-final');
                const descuento = parseInt(this.getAttribute('data-descuento'));
                const desc = this.getAttribute('data-desc');
                const condicion = this.getAttribute('data-condicion');
                const stock = this.getAttribute('data-stock');
                const img = this.getAttribute('data-img');
                const id = this.getAttribute('data-id'); // NUEVO: Extraemos el ID

                document.getElementById('modalNombre').textContent = nombre;
                document.getElementById('modalMarca').textContent = marca;
                document.getElementById('modalSku').textContent = sku;
                document.getElementById('modalDesc').textContent = desc;
                document.getElementById('modalStock').textContent = stock;
                document.getElementById('modalImg').src = img;
                document.getElementById('modalImg').src = '<?= base_url("uploads/productos/") ?>' + img;
                // Lógica de Precios para el Modal
                const priceContainer = document.getElementById('modalPriceContainer');
                if (descuento > 0) {
                    priceContainer.innerHTML = `
                        <div class="d-flex align-items-center mb-4">
                            <h3 class="text-success fw-bold mb-0 me-2">$${precioFinal}</h3>
                            <h5 class="text-muted text-decoration-line-through mb-0">$${precioOriginal}</h5>
                            <span class="badge bg-danger ms-2">-${descuento}%</span>
                        </div>
                    `;
                } else {
                    priceContainer.innerHTML = `<h3 class="text-primary fw-bold mb-4">$${precioFinal}</h3>`;
                }
                
                // Ajustar badge de condición
                const badge = document.getElementById('modalCondicion');
                badge.textContent = condicion.toUpperCase();
                badge.className = condicion.toLowerCase() === 'nuevo' 
                    ? 'badge bg-success' 
                    : 'badge bg-warning text-dark';

                // --- NUEVO: Pasar los datos al botón "Agregar" dentro del Modal ---
                const btnModal = document.getElementById('btnModalAgregar');
                btnModal.setAttribute('data-id', id);
                btnModal.setAttribute('data-nombre', nombre);
                btnModal.setAttribute('data-precio', precioFinal);
                btnModal.setAttribute('data-img', img);
            });
        });

        // --- 2. Lógica Funcional para Agregar al Carrito vía AJAX ---
        const botonesAgregar = document.querySelectorAll('.btn-agregar-carrito');
        
        botonesAgregar.forEach(boton => {
            boton.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const nombre = this.getAttribute('data-nombre');
                const precio = this.getAttribute('data-precio');
                const img = this.getAttribute('data-img');

                // Si no hay ID, no hacemos nada (por seguridad)
                if(!id) return;

                const formData = new FormData();
                formData.append('id', id);
                formData.append('nombre', nombre);
                formData.append('precio', precio);
                formData.append('imagen', img);

                fetch('<?= base_url('carrito/agregar') ?>', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                    fetch('<?= base_url('carrito/agregar') ?>', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        // REEMPLAZAMOS EL alert() POR SWEETALERT2
                        Swal.fire({
                            title: '¡Agregado!',
                            text: nombre + ' se añadió a tu carrito.',
                            icon: 'success',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true
                        });
                    }
                })
                .catch(error => console.error('Error:', error));
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        });
    });
</script>

<style>
    .hover-effect { transition: all 0.3s ease; }
    .hover-effect:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important; }
    .modal-content { border-radius: 15px; overflow: hidden; }
</style>

<?= $this->endSection() ?>