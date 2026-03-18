<?= $this->extend('layouts/main') ?>
<?= $this->section('contenido') ?>

<div class="container-fluid mt-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-dark fw-bold"><i class="fas fa-history text-secondary me-2"></i> Historial de Atenciones</h2>
        <span class="badge bg-secondary fs-6"><i class="fas fa-archive me-1"></i> <?= count($historial) ?> Tickets Archivados</span>
    </div>

    <p class="text-muted mb-4">Aquí puedes consultar todos los tickets que ya fueron resueltos y cerrados.</p>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-muted">
                        <tr>
                            <th class="ps-4">Ticket</th>
                            <th>Cliente</th>
                            <th>Asunto</th>
                            <th>Fecha de Cierre</th>
                            <th>Estado</th>
                            <th class="text-center pe-4">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($historial)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fas fa-box-open fs-1 text-secondary mb-3 d-block"></i>
                                    <h5>El historial está vacío</h5>
                                    <p>Aún no has cerrado ninguna conversación.</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($historial as $ticket): ?>
                                <tr>
                                    <td class="ps-4 fw-bold text-secondary">#<?= str_pad($ticket['id'], 4, '0', STR_PAD_LEFT) ?></td>
                                    
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-secondary bg-opacity-25 text-dark rounded-circle d-flex justify-content-center align-items-center me-2" style="width: 35px; height: 35px; font-weight: bold;">
                                                <?= strtoupper(substr($ticket['nombre'] ?? 'U', 0, 1)) ?>
                                            </div>
                                            <div>
                                                <span class="fw-bold d-block text-dark"><?= esc($ticket['nombre'] . ' ' . $ticket['apellidos']) ?></span>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="text-muted">
                                        <i class="fas fa-comment-dots text-secondary me-1"></i> <?= esc($ticket['asunto'] ?? 'Sin asunto') ?>
                                    </td>

                                    <td class="text-muted small">
                                        <i class="fas fa-calendar-check me-1"></i> 
                                        <?= !empty($ticket['fecha_cierre']) ? date('d/m/Y h:i A', strtotime($ticket['fecha_cierre'])) : 'N/A' ?>
                                    </td>

                                    <td>
                                        <span class="badge bg-success p-2"><i class="fas fa-check-circle me-1"></i> Resuelto</span>
                                    </td>

                                    <td class="text-center pe-4">
                                        <a href="<?= base_url('admin/soporte/responder/' . $ticket['id']) ?>" class="btn btn-sm btn-outline-secondary fw-bold shadow-sm">
                                            <i class="fas fa-eye me-1"></i> Ver Detalles
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