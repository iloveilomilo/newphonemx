<?= $this->extend('layouts/main') ?>
<?= $this->section('contenido') ?>

<div class="container-fluid mt-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-dark fw-bold"><i class="fas fa-inbox text-primary me-2"></i> Bandeja de Entrada</h2>
        <span class="badge bg-primary fs-6"><?= count($conversaciones) ?> Tickets Abiertos</span>
    </div>

    <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
            <a class="nav-link active fw-bold text-primary" href="#"><i class="fas fa-filter"></i> Todos los Abiertos</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-muted" href="#">Nuevos</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-muted" href="#">En Proceso</a>
        </li>
    </ul>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-muted">
                        <tr>
                            <th class="ps-4">Ticket</th>
                            <th>Cliente</th>
                            <th>Asunto</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th class="text-center pe-4">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($conversaciones)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fas fa-check-circle fs-1 text-success mb-3 d-block"></i>
                                    <h5>¡Todo limpio!</h5>
                                    <p>No tienes mensajes pendientes por responder.</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($conversaciones as $conv): ?>
                                <tr>
                                    <td class="ps-4 fw-bold text-secondary">#<?= str_pad($conv['id'], 4, '0', STR_PAD_LEFT) ?></td>
                                    
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary text-white rounded-circle d-flex justify-content-center align-items-center me-2" style="width: 35px; height: 35px; font-weight: bold;">
                                                <?= strtoupper(substr($conv['nombre'] ?? 'U', 0, 1)) ?>
                                            </div>
                                            <div>
                                                <span class="fw-bold d-block text-dark"><?= esc($conv['nombre'] . ' ' . $conv['apellidos']) ?></span>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="text-muted">
                                        <i class="fas fa-comment-dots text-secondary me-1"></i> <?= esc($conv['asunto'] ?? 'Sin asunto') ?>
                                    </td>

                                    <td>
                                        <?php if($conv['estado'] == 'nuevo'): ?>
                                            <span class="badge bg-danger p-2"><i class="fas fa-star me-1"></i> Nuevo</span>
                                        <?php elseif($conv['estado'] == 'en_proceso'): ?>
                                            <span class="badge bg-warning text-dark p-2"><i class="fas fa-tools me-1"></i> En Proceso</span>
                                        <?php elseif($conv['estado'] == 'espera_cliente'): ?>
                                            <span class="badge bg-info text-dark p-2"><i class="fas fa-user-clock me-1"></i> En Espera</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary p-2"><?= esc($conv['estado']) ?></span>
                                        <?php endif; ?>
                                    </td>

                                    <td class="text-muted small">
                                        <?= date('d/m/Y h:i A', strtotime($conv['fecha_inicio'])) ?>
                                    </td>

                                    <td class="text-center pe-4">
                                        <a href="<?= base_url('admin/soporte/responder/' . $conv['id']) ?>" class="btn btn-sm btn-primary fw-bold shadow-sm">
                                            <i class="fas fa-reply me-1"></i> Atender
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>