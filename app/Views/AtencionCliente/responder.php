<?= $this->extend('layouts/main') ?>
<?= $this->section('contenido') ?>

<div class="container-fluid mt-3">
    
    <div class="mb-3">
        <h3 class="text-primary fw-bold"><i class="fas fa-headset me-2"></i> Soporte Interno</h3>
        <p class="text-muted small">Atiende las consultas escaladas por tus clientes.</p>
    </div>

    <input type="hidden" id="id_conversacion_js" value="<?= esc($conversacion['id']) ?>"> 

    <div class="row">
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0 text-muted fw-bold"><i class="fas fa-inbox me-2"></i> Bandeja de Entrada</h6>
                </div>
                <div class="card-body p-0" style="height: 550px; overflow-y: auto;">
                    <div class="list-group list-group-flush">
                        <?php if(empty($conversaciones)): ?>
                            <div class="p-4 text-center text-muted small">No hay chats activos.</div>
                        <?php else: ?>
                            <?php foreach($conversaciones as $conv): ?>
                                <?php $esActivo = ($conv['id'] == $conversacion['id']); ?>
                                <a href="<?= base_url('admin/soporte/responder/' . $conv['id']) ?>" 
                                   class="list-group-item list-group-item-action py-3 <?= $esActivo ? 'bg-primary bg-opacity-10 border-start border-primary border-4' : '' ?>">
                                    <div class="d-flex w-100 justify-content-between align-items-center">
                                        <div class="fw-bold <?= $esActivo ? 'text-primary' : 'text-dark' ?>">
                                            <i class="fas fa-user-circle me-1"></i> <?= esc($conv['nombre']) ?>
                                        </div>
                                        <small class="text-muted"><?= date('d M', strtotime($conv['fecha_inicio'])) ?></small>
                                    </div>
                                    <small class="text-muted">Sala #<?= $conv['id'] ?></small>
                                </a>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8 mb-3">
            <div class="card shadow-sm border-0 h-100">
                
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0 text-primary fw-bold"><i class="fas fa-comments me-2"></i> Conversación con <?= esc($conversacion['nombre'] . ' ' . $conversacion['apellidos']) ?></h6>
                        <small class="text-muted">Sala de soporte #<?= esc($conversacion['id']) ?> | Estado: <?= esc($conversacion['estado']) ?></small>
                    </div>
                    
                    <?php if($conversacion['estado'] != 'cerrado'): ?>
                        <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#panelGestion">
                            <i class="fas fa-cog"></i> Gestionar Ticket
                        </button>
                    <?php endif; ?>
                </div>

                <div class="collapse bg-light border-bottom" id="panelGestion">
                    <div class="p-3">
                        <form action="<?= base_url('admin/soporte/actualizar_conversacion') ?>" method="POST" class="row g-2 align-items-end">
                            <input type="hidden" name="id_conversacion" value="<?= esc($conversacion['id']) ?>"> 
                            <div class="col-md-4">
                                <label class="small fw-bold text-muted">Cambiar Estado:</label>
                                <select class="form-select form-select-sm" name="estado">
                                    <option value="nuevo" <?= ($conversacion['estado'] == 'nuevo') ? 'selected' : '' ?>>Nuevo</option>
                                    <option value="en_proceso" <?= ($conversacion['estado'] == 'en_proceso') ? 'selected' : '' ?>>En Proceso</option>
                                    <option value="cerrado">Archivar Ticket</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="small fw-bold text-muted">Programar Seguimiento:</label>
                                <input type="datetime-local" class="form-control form-control-sm" name="fecha_seguimiento" value="<?= !empty($conversacion['fecha_seguimiento']) ? date('Y-m-d\TH:i', strtotime($conversacion['fecha_seguimiento'])) : '' ?>">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-sm btn-success w-100"><i class="fas fa-save"></i> Guardar</button>
                            </div>
                            <div class="col-md-2">
                                <a href="<?= base_url('admin/soporte/archivar_conversacion/' . $conversacion['id']) ?>" class="btn btn-sm btn-secondary w-100 shadow-sm" onclick="return confirm('¿Seguro que deseas archivar este ticket?')">
                                    <i class="fas fa-archive"></i> Archivar
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="card-body" id="caja-mensajes" style="height: 430px; overflow-y: auto; background-color: #f8f9fc;">
                    <div class="text-center mt-5 text-muted">
                        <i class="fas fa-spinner fa-spin"></i> Cargando mensajes...
                    </div>
                </div>

                <?php if($conversacion['estado'] == 'cerrado'): ?>
                    <div class="card-footer bg-light border-top p-4 text-center">
                        <p class="text-muted mb-3"><i class="fas fa-archive me-1"></i> Este ticket se encuentra cerrado y archivado.</p>
                        <a href="<?= base_url('admin/soporte/reabrir_conversacion/' . $conversacion['id']) ?>" class="btn btn-warning fw-bold px-4 shadow-sm">
                            <i class="fas fa-folder-open me-2"></i> Retomar Conversación
                        </a>
                    </div>
                <?php else: ?>
                    <div class="card-footer bg-white border-top p-3">
                        <form action="<?= base_url('admin/soporte/enviar_mensaje') ?>" method="POST">
                            <input type="hidden" name="id_conversacion" value="<?= esc($conversacion['id']) ?>"> 
                            <div class="input-group">
                                <input type="text" class="form-control rounded-pill me-2 bg-light" name="mensaje" id="inputMensaje" placeholder="Escribe tu respuesta para el cliente..." required autocomplete="off">
                                <button class="btn btn-primary rounded-pill px-4" type="submit"><i class="fas fa-paper-plane me-1"></i> Enviar</button>
                            </div>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
const idSalaChat = document.getElementById('id_conversacion_js').value;
const cajaMensajes = document.getElementById('caja-mensajes');
const myUsuarioId = "<?= session()->get('id') ?? 3 ?>"; // Para saber si el mensaje es tuyo o del cliente

function cargarMensajesEnTiempoReal() {
    fetch('<?= base_url('admin/soporte/obtener_mensajes_nuevos/') ?>' + idSalaChat)
        .then(response => response.json())
        .then(data => {
            let scrollPrevio = cajaMensajes.scrollTop;
            let alturaTotalPrevia = cajaMensajes.scrollHeight;
            
            cajaMensajes.innerHTML = ''; 
            
            if (data.mensajes && data.mensajes.length > 0) {
                data.mensajes.forEach(msg => {
                    // Si el remitente eres tú (o un admin/soporte), la burbuja va a la derecha y azul.
                    let esMio = (msg.remitente_id == myUsuarioId || msg.rol === 'admin' || msg.rol === 'atencion_cliente');
                    
                    let burbuja = `
                        <div class="d-flex justify-content-${esMio ? 'end' : 'start'} mb-3">
                            <div class="p-3 rounded shadow-sm" style="max-width: 75%; ${esMio ? 'background-color: #0d6efd; color: white;' : 'background-color: #ffffff; border: 1px solid #dee2e6;'}">
                                <small class="${esMio ? 'text-white-50' : 'text-primary'} fw-bold mb-1 d-block" style="font-size: 0.7rem;">
                                    ${msg.nombre} ${esMio ? '(Tú)' : ''}
                                </small>
                                <p class="mb-1" style="font-size: 0.95rem;">${msg.mensaje}</p>
                                <div class="text-end">
                                    <small class="${esMio ? 'text-white-50' : 'text-muted'}" style="font-size: 0.65rem;">
                                        ${msg.fecha_envio.substring(11, 16)} </small>
                                </div>
                            </div>
                        </div>
                    `;
                    cajaMensajes.innerHTML += burbuja;
                });

                // Auto-scroll si estás hasta abajo
                if (alturaTotalPrevia - scrollPrevio <= 450) {
                    cajaMensajes.scrollTop = cajaMensajes.scrollHeight;
                } else {
                    cajaMensajes.scrollTop = scrollPrevio; 
                }
            } else {
                cajaMensajes.innerHTML = '<div class="text-center mt-5 text-muted">Aún no hay mensajes. ¡Escribe el primero!</div>';
            }
        });
}

cargarMensajesEnTiempoReal();
setInterval(cargarMensajesEnTiempoReal, 3000);
</script>

<?= $this->endSection() ?>