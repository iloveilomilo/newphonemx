<?= $this->extend('layouts/main') ?>
<?= $this->section('contenido') ?>

<div class="container-fluid mt-3">
    
    <div class="mb-4">
        <h2 class="text-dark fw-bold">Hola, <?= session()->get('nombre') ?? 'Paola' ?> 👋</h2>
        <p class="text-muted">Resumen de actividad de soporte técnico de NewPhoneMX.</p>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm border-start border-danger border-4 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 text-uppercase fw-bold" style="font-size: 0.75rem;">Nuevos</p>
                            <h3 class="mb-0 text-dark fw-bold"><?= $totales['nuevos'] ?></h3>
                        </div>
                        <div class="bg-danger bg-opacity-10 p-2 rounded-circle">
                            <i class="fas fa-envelope text-danger fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm border-start border-warning border-4 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 text-uppercase fw-bold" style="font-size: 0.75rem;">En Proceso</p>
                            <h3 class="mb-0 text-dark fw-bold"><?= $totales['en_proceso'] ?></h3>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-2 rounded-circle">
                            <i class="fas fa-tools text-warning fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm border-start border-secondary border-4 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 text-uppercase fw-bold" style="font-size: 0.75rem;">Archivados</p>
                            <h3 class="mb-0 text-dark fw-bold"><?= $totales['resueltos'] ?></h3>
                        </div>
                        <div class="bg-secondary bg-opacity-10 p-2 rounded-circle">
                            <i class="fas fa-archive text-secondary fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold text-dark"><i class="fas fa-clock text-primary me-2"></i> Actividad Reciente (Últimos 5)</h6>
                    <a href="<?= base_url('admin/soporte/mensajes') ?>" class="btn btn-sm btn-outline-primary">Ver todos</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-muted">
                                <tr>
                                    <th class="ps-4">Ticket</th>
                                    <th>Cliente</th>
                                    <th>Asunto</th>
                                    <th>Estado</th>
                                    <th class="text-center pe-4">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($ultimos_tickets)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">No hay tickets activos.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach($ultimos_tickets as $ticket): ?>
                                        <tr>
                                            <td class="ps-4 fw-bold text-secondary">#<?= str_pad($ticket['id'], 4, '0', STR_PAD_LEFT) ?></td>
                                            <td class="fw-bold text-dark">
                                                <?= esc($ticket['nombre'] . ' ' . $ticket['apellidos']) ?>
                                            </td>
                                            <td class="text-muted small">
                                                <?= esc($ticket['asunto'] ?? 'Sin asunto') ?>
                                            </td>
                                            <td>
                                                <?php if($ticket['estado'] == 'nuevo'): ?>
                                                    <span class="badge bg-danger">Nuevo</span>
                                                <?php elseif($ticket['estado'] == 'en_proceso'): ?>
                                                    <span class="badge bg-warning text-dark">En Proceso</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center pe-4">
                                                <a href="<?= base_url('admin/soporte/responder/' . $ticket['id']) ?>" class="btn btn-sm btn-primary shadow-sm">Atender</a>
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
    </div>

    <div class="col-md-4">
        <div class="card text-center shadow-sm">
            <div class="card-body">
                <h5>Resueltos</h5>
                <h3 class="text-success">12</h3>
            </div>
        </div>
    </div>
    <div style="margin-top:30px;">
    <a href="<?= base_url('soporte/mensajes') ?>" class="btn btn-primary">
        Ver Mensajes
    </a>

    <a href="<?= base_url('soporte/historial') ?>" class="btn btn-secondary">
        Ver Historial
    </a>

    <a href="<?= base_url('soporte/responder') ?>" class="btn btn-success">
        Responder Mensaje
    </a>
</div>

</div>

<?= $this->endSection() ?>