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
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card h-100 shadow-sm border-0 hover-effect">
                        
                        <div class="position-absolute top-0 end-0 m-2">
                            <span class="badge <?= strtolower($prod['condicion']) == 'nuevo' ? 'bg-success' : 'bg-warning text-dark' ?>">
                                <?= strtoupper($prod['condicion']) ?>
                            </span>
                        </div>

                        <div class="text-center p-3 bg-light rounded-top">
                            <img src="<?= base_url('uploads/productos/' . $prod['imagen_principal']) ?>" 
                                 class="img-fluid" 
                                 style="height: 200px; object-fit: contain;">
                        </div>
                        
                        <div class="card-body d-flex flex-column">
                            <small class="text-muted text-uppercase fw-bold"><?= $prod['marca'] ?></small>
                            <h5 class="card-title fw-bold text-dark"><?= $prod['nombre'] ?></h5>
                            
                            <div class="mt-auto">
                                <h4 class="text-primary fw-bold mb-3">$<?= number_format($prod['precio'], 2) ?></h4>
                                
                                <div class="d-grid gap-2">
                                    <button type="button" 
                                            class="btn btn-outline-secondary btn-sm btn-detalle"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#productoModal"
                                            data-nombre="<?= $prod['nombre'] ?>"
                                            data-marca="<?= $prod['marca'] ?>"
                                            data-precio="<?= number_format($prod['precio'], 2) ?>"
                                            data-desc="<?= $prod['descripcion'] ?>"
                                            data-condicion="<?= $prod['condicion'] ?>"
                                            data-stock="<?= $prod['stock'] ?>"
                                            data-img="<?= base_url('uploads/productos/' . $prod['imagen_principal']) ?>">
                                        Ver Detalles
                                    </button>
                                    
                                    <button class="btn btn-primary btn-sm">
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
                <small id="modalMarca" class="text-uppercase text-muted fw-bold"></small>
                <h2 id="modalNombre" class="fw-bold mb-2"></h2>
                
                <div class="mb-3">
                    <span id="modalCondicion" class="badge bg-light text-dark border"></span>
                    <span class="text-success fw-bold ms-2"><i class="fas fa-check-circle"></i> Stock: <span id="modalStock"></span></span>
                </div>

                <h3 class="text-primary fw-bold mb-4">$<span id="modalPrecio"></span></h3>
                
                <h6 class="fw-bold">Descripción:</h6>
                <p id="modalDesc" class="text-muted small" style="line-height: 1.6;"></p>

                <div class="d-grid gap-2 mt-4">
                    <button class="btn btn-primary btn-lg">
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Seleccionamos todos los botones de detalle
        const botones = document.querySelectorAll('.btn-detalle');
        
        botones.forEach(boton => {
            boton.addEventListener('click', function() {
                // Obtenemos los datos guardados en el botón
                const nombre = this.getAttribute('data-nombre');
                const marca = this.getAttribute('data-marca');
                const precio = this.getAttribute('data-precio');
                const desc = this.getAttribute('data-desc');
                const condicion = this.getAttribute('data-condicion');
                const stock = this.getAttribute('data-stock');
                const img = this.getAttribute('data-img');

                // Los inyectamos en el Modal
                document.getElementById('modalNombre').textContent = nombre;
                document.getElementById('modalMarca').textContent = marca;
                document.getElementById('modalPrecio').textContent = precio;
                document.getElementById('modalDesc').textContent = desc;
                document.getElementById('modalStock').textContent = stock;
                document.getElementById('modalImg').src = img;
                
                // Ajustar badge de condición
                const badge = document.getElementById('modalCondicion');
                badge.textContent = condicion.toUpperCase();
                badge.className = condicion.toLowerCase() === 'nuevo' 
                    ? 'badge bg-success' 
                    : 'badge bg-warning text-dark';
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