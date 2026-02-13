<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4 mt-2">
        <div>
            <h2 class="fw-bold text-dark mb-0">Tienda / Catálogo</h2>
            <p class="text-muted small">Celulares disponibles para entrega inmediata.</p>
        </div>
        
        <form class="d-flex" role="search" action="<?= base_url('dashboard/cliente') ?>" method="get">
            <input class="form-control me-2" 
                   type="search" 
                   name="q" 
                   value="<?= esc($busqueda ?? '') ?>" 
                   placeholder="Buscar marca o modelo..." 
                   aria-label="Search">
            <button class="btn btn-outline-primary" type="submit"><i class="fas fa-search"></i></button>
        </form>
    </div>

    <?php if(empty($productos) && !empty($busqueda)): ?>
        <div class="alert alert-warning mb-4">
            No encontramos resultados para "<strong><?= esc($busqueda) ?></strong>". 
            <a href="<?= base_url('dashboard/cliente') ?>" class="alert-link">Ver todo el catálogo</a>.
        </div>
    <?php endif; ?>

    <div class="row">
        <?php if (!empty($productos)): ?>
            <?php foreach ($productos as $prod): ?>
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card h-100 shadow-sm border-0 hover-effect">
                        
                        <div class="position-absolute top-0 end-0 m-2">
                            <?php if(strtolower($prod['condicion']) == 'nuevo'): ?>
                                <span class="badge bg-success">NUEVO</span>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark">SEMI-NUEVO</span>
                            <?php endif; ?>
                        </div>

                        <div class="text-center p-3 bg-light rounded-top">
                            <img src="<?= base_url('uploads/productos/' . $prod['imagen_principal']) ?>" 
                                 class="img-fluid" 
                                 alt="<?= $prod['nombre'] ?>" 
                                 style="height: 200px; object-fit: contain;">
                        </div>
                        
                        <div class="card-body d-flex flex-column">
                            <small class="text-muted text-uppercase fw-bold"><?= $prod['marca'] ?></small>
                            <h5 class="card-title fw-bold text-dark"><?= $prod['nombre'] ?></h5>
                            
                            <div class="mt-auto">
                                <h4 class="text-primary fw-bold mb-3">
                                    $<?= number_format($prod['precio'], 2) ?>
                                </h4>
                                
                                <div class="d-grid gap-2">
                                    <a href="<?= base_url('tienda/producto/'.$prod['id']) ?>" class="btn btn-outline-secondary btn-sm">
                                        Ver Detalles
                                    </a>
                                    <button class="btn btn-primary btn-sm">
                                        <i class="fas fa-cart-plus me-2"></i>Agregar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php elseif(empty($busqueda)): ?>
            <div class="col-12 text-center py-5">
                <div class="alert alert-light border">
                    <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                    <h4>No hay productos disponibles por el momento.</h4>
                    <p>Estamos actualizando nuestro inventario.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
    .hover-effect { transition: transform 0.2s; }
    .hover-effect:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; }
</style>

<?= $this->endSection() ?>