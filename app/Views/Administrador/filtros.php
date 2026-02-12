<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>Gestión de Características (Filtros)</h2>
        <p class="text-muted">Define qué características tendrán tus productos (Ej: Color, GB, Batería).</p>
    </div>
    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalFiltro">
        <i class="fas fa-plus me-2"></i>Nuevo Filtro
    </button>
</div>

<?php if(session()->getFlashdata('msg')):?>
    <div class="alert alert-info"><?= session()->getFlashdata('msg') ?></div>
<?php endif;?>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nombre de la Característica</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($filtros)): ?>
                    <?php foreach ($filtros as $f): ?>
                        <tr>
                            <td><?= $f['id'] ?></td>
                            <td>
                                <span class="badge bg-secondary me-2">Atributo</span>
                                <strong><?= $f['nombre'] ?></strong>
                            </td>
                            <td class="text-end">
                                <a href="<?= base_url('dashboard/filtros/eliminar/'.$f['id']) ?>" 
                                   class="btn btn-sm btn-outline-danger"
                                   onclick="return confirm('¿Eliminar este filtro?');">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-center text-muted py-4">
                            No has definido características aún. <br>
                            Empieza creando "Color" o "Almacenamiento".
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalFiltro" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title">Nueva Característica</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="<?= base_url('dashboard/filtros/guardar') ?>" method="post">
          <div class="modal-body">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre del Filtro</label>
                <input type="text" class="form-control" name="nombre" required placeholder="Ej: Color, Capacidad, RAM...">
                <div class="form-text">Esto aparecerá cuando crees un nuevo producto.</div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-success">Guardar Filtro</button>
          </div>
      </form>
    </div>
  </div>
</div>

<?= $this->endSection() ?>