<?= $this->extend('layouts/main') ?>
<?= $this->section('contenido') ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Conversación - <?= esc($conversacion['nombre'] . ' ' . $conversacion['apellidos']) ?></h2>
        
        <?php if (!empty($conversacion['fecha_seguimiento'])): ?>
            <span class="badge bg-warning text-dark p-2">
                <i class="fas fa-bell"></i> Recordatorio: <?= date('d/m/Y H:i', strtotime($conversacion['fecha_seguimiento'])) ?>
            </span>
        <?php else: ?>
            <span class="badge bg-secondary text-white p-2">
                <i class="fas fa-bell-slash"></i> Sin seguimiento
            </span>
        <?php endif; ?>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <strong>Chat Activo (Sala #<?= esc($conversacion['id']) ?>)</strong>
                </div>
                
                <div class="card-body" id="caja-mensajes" style="height: 400px; overflow-y: auto; background-color: #f8f9fa;">
                    <div class="text-center mt-5 text-muted">
                        <i class="fas fa-spinner fa-spin"></i> Cargando mensajes...
                    </div>
                </div>

                <div class="card-footer">
                    <form action="<?= base_url('admin/soporte/enviar_mensaje') ?>" method="POST">
                        <input type="hidden" name="id_conversacion" value="<?= esc($conversacion['id']) ?>"> 
                        <div class="input-group">
                            <input type="text" class="form-control" name="mensaje" id="inputMensaje" placeholder="Escribe tu respuesta aquí..." required autocomplete="off">
                            <button class="btn btn-primary" type="submit">Enviar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-dark text-white">
                    <strong>Gestión de la Conversación</strong>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('admin/soporte/actualizar_conversacion') ?>" method="POST">
                        <input type="hidden" name="id_conversacion" id="id_conversacion_js" value="<?= esc($conversacion['id']) ?>"> 
                        
                        <div class="mb-3">
                            <label for="estado" class="form-label fw-bold">Estado actual:</label>
                            <select class="form-select border-primary" id="estado" name="estado">
                                <option value="nuevo" <?= ($conversacion['estado'] == 'nuevo') ? 'selected' : '' ?>>Nuevo</option>
                                <option value="en_proceso" <?= ($conversacion['estado'] == 'en_proceso') ? 'selected' : '' ?>>En Proceso</option>
                                <option value="espera_cliente" <?= ($conversacion['estado'] == 'espera_cliente') ? 'selected' : '' ?>>En Espera del Cliente</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="fecha_seguimiento" class="form-label fw-bold">Programar Seguimiento:</label>
                            <input type="datetime-local" class="form-control" id="fecha_seguimiento" name="fecha_seguimiento" value="<?= !empty($conversacion['fecha_seguimiento']) ? date('Y-m-d\TH:i', strtotime($conversacion['fecha_seguimiento'])) : '' ?>">
                        </div>

                        <button type="submit" class="btn btn-success w-100">Guardar Cambios</button>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm border-danger">
                <div class="card-body text-center">
                    <p class="text-muted small mb-2">Al finalizar, el chat se archivará.</p>
                    <button type="button" class="btn btn-outline-danger w-100 fw-bold" onclick="finalizarConversacion()">Finalizar Conversación</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function finalizarConversacion() {
    let idSala = document.getElementById('id_conversacion_js').value;
    if(confirm("¿Estás segura de finalizar y archivar esta conversación?")) {
        window.location.href = "<?= base_url('admin/soporte/cerrar_conversacion/') ?>" + idSala;
    }
}

const idSalaChat = document.getElementById('id_conversacion_js').value;
const cajaMensajes = document.getElementById('caja-mensajes');

function cargarMensajesEnTiempoReal() {
    fetch('<?= base_url('admin/soporte/obtener_mensajes_nuevos/') ?>' + idSalaChat)
        .then(response => response.json())
        .then(data => {
            let scrollPrevio = cajaMensajes.scrollTop;
            let alturaTotalPrevia = cajaMensajes.scrollHeight;
            
            cajaMensajes.innerHTML = ''; 
            
            if (data.mensajes && data.mensajes.length > 0) {
                data.mensajes.forEach(msg => {
                    let esAsesor = (msg.rol === 'atencion_cliente' || msg.rol === 'admin');
                    let burbuja = `
                        <div class="d-flex justify-content-${esAsesor ? 'end' : 'start'} mb-3">
                            <div class="p-3 border rounded shadow-sm" style="max-width: 80%; background-color: ${esAsesor ? '#e3f2fd' : '#ffffff'};">
                                <strong>${msg.nombre}</strong>
                                <p class="mb-1 mt-1">${msg.mensaje}</p>
                                <small class="text-muted" style="font-size: 0.75rem;">${msg.fecha_envio}</small>
                            </div>
                        </div>
                    `;
                    cajaMensajes.innerHTML += burbuja;
                });

                if (alturaTotalPrevia - scrollPrevio <= 450) {
                    cajaMensajes.scrollTop = cajaMensajes.scrollHeight;
                } else {
                    cajaMensajes.scrollTop = scrollPrevio; 
                }
            } else {
                cajaMensajes.innerHTML = '<div class="text-center mt-5 text-muted">Aún no hay mensajes.</div>';
            }
        });
}

cargarMensajesEnTiempoReal();
setInterval(cargarMensajesEnTiempoReal, 3000);
</script>

<?= $this->endSection() ?>