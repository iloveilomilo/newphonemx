<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>Gestión de Usuarios</h2>
        <p class="text-muted">Administra el acceso de tu personal al sistema.</p>
    </div>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalUsuario">
        <i class="fas fa-user-plus me-2"></i>Nuevo Usuario
    </button>
</div>

<?php if(session()->getFlashdata('msg')):?>
    <div class="alert alert-info border-0 shadow-sm">
        <i class="fas fa-info-circle me-2"></i><?= session()->getFlashdata('msg') ?>
    </div>
<?php endif;?>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Nombre Completo</th>
                    <th>Contacto</th>
                    <th>Rol en el Sistema</th>
                    <th>Estado</th> 
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($usuarios)): ?>
                    <?php foreach ($usuarios as $u): ?>
                        <tr>
                            <td>
                                <strong><?= $u['nombre'] . ' ' . $u['apellidos'] ?></strong>
                            </td>
                            <td>
                                <i class="fas fa-envelope text-muted me-1"></i> <?= $u['correo'] ?><br>
                                <i class="fas fa-phone text-muted me-1"></i> <small><?= $u['telefono'] ?></small>
                            </td>
                            <td>
                                <?php if($u['rol'] == 'admin'): ?>
                                    <span class="badge bg-primary">Administrador</span>
                                <?php elseif($u['rol'] == 'atencion_cliente'): ?>
                                    <span class="badge bg-warning text-dark">Soporte / Atención</span>
                                <?php else: ?>
                                    <span class="badge bg-success">Cliente</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($u['activo'] == 1): ?>
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success"><i class="fas fa-check-circle me-1"></i>Activo</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary"><i class="fas fa-ban me-1"></i>Dado de baja</span>
                                <?php endif; ?>
                            </td>
                            
                            <td class="text-end">
                                <?php if($u['id'] == session('id')): ?>
                                    <span class="badge bg-light text-muted border">Tú (Sesión actual)</span>
                                <?php else: ?>
                                    
                                    <?php if($u['activo'] == 1): ?>
                                        <a href="<?= base_url('admin/usuarios/eliminar/'.$u['id']) ?>" 
                                           class="btn btn-sm btn-outline-danger"
                                           onclick="return confirm('¿Estás seguro de desactivar el acceso de este usuario?');">
                                            <i class="fas fa-user-slash"></i> Revocar Acceso
                                        </a>
                                    <?php else: ?>
                                        <a href="<?= base_url('admin/usuarios/reactivar/'.$u['id']) ?>" 
                                           class="btn btn-sm btn-outline-success"
                                           onclick="return confirm('¿Deseas devolverle el acceso a este usuario?');">
                                            <i class="fas fa-user-check"></i> Reactivar
                                        </a>
                                    <?php endif; ?>

                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">
                            No hay usuarios registrados.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalUsuario" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white border-0">
        <h5 class="modal-title"><i class="fas fa-user-plus me-2"></i>Registrar Nuevo Usuario</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="<?= base_url('admin/usuarios/guardar') ?>" method="post">
          <div class="modal-body bg-light">
            
            <div class="row bg-white p-3 rounded shadow-sm mx-1 mb-3">
                <h6 class="text-primary mb-3 pb-2 border-bottom">Datos Personales</h6>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Nombre(s)</label>
                    <input type="text" class="form-control" name="nombre" required minlength="3">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Apellidos</label>
                    <input type="text" class="form-control" name="apellidos" required minlength="3">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Teléfono (10 dígitos)</label>
                    <input type="text" class="form-control" name="telefono" required pattern="[0-9]{10}" maxlength="10" title="Debe contener exactamente 10 números">
                </div>
            </div>

            <div class="row bg-white p-3 rounded shadow-sm mx-1">
                <h6 class="text-primary mb-3 pb-2 border-bottom">Credenciales de Acceso</h6>
                <div class="col-md-12 mb-3">
                    <label class="form-label fw-bold">Rol en el Sistema</label>
                    <select class="form-select border-primary" name="rol" required>
                        <option value="">Selecciona un nivel de acceso...</option>
                        <option value="admin">Administrador (Control Total)</option>
                        <option value="atencion_cliente">Atención al Cliente (Solo Soporte)</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Correo Electrónico</label>
                    <input type="email" class="form-control" name="correo" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Contraseña Temporal</label>
                    <input type="password" class="form-control" name="password" required minlength="6">
                </div>
            </div>

          </div>
          <div class="modal-footer border-0 bg-light">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-2"></i>Guardar Usuario</button>
          </div>
      </form>
    </div>
  </div>
</div>

<?= $this->endSection() ?>