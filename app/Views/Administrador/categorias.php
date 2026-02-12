<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Gestión de Categorías</h2>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCategoria">
        <i class="fas fa-plus me-2"></i>Nueva Categoría
    </button>
</div>

<?php if(session()->getFlashdata('msg')):?>
    <div class="alert alert-info"><?= session()->getFlashdata('msg') ?></div>
<?php endif;?>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($categorias)): ?>
                    <?php foreach ($categorias as $cat): ?>
                        <tr>
                            <td><?= $cat['id'] ?></td>
                            <td><?= $cat['nombre'] ?></td>
                            <td class="text-end">
                                <a href="<?= base_url('dashboard/categorias/eliminar/'.$cat['id']) ?>" 
                                   class="btn btn-sm btn-outline-danger"
                                   onclick="return confirm('¿Seguro que deseas eliminar esta categoría?');">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-center text-muted">No hay categorías registradas</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalCategoria" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Nueva Categoría</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="<?= base_url('dashboard/categorias/guardar') ?>" method="post">
          <div class="modal-body">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre de la Categoría</label>
                <input type="text" class="form-control" name="nombre" required placeholder="Ej: Celulares, Tablets...">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary">Guardar</button>
          </div>
      </form>
    </div>
  </div>
</div>

<?= $this->endSection() ?>