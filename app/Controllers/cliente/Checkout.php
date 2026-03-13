<?php

namespace App\Controllers\cliente;
use App\Controllers\BaseController;
use App\Models\CarritoModel;

class Checkout extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $usuario_id = session('id');

        // Traemos el carrito
        $carritoModel = new CarritoModel();
        $items = $carritoModel->obtenerCarrito($usuario_id);
        
        if(empty($items)) {
            return redirect()->to(base_url('carrito'))->with('mensaje', 'Tu carrito está vacío.');
        }

        // Calculamos el total
        $total = 0;
        foreach ($items as &$item) {
            $precioFinal = $item['precio'];
            if ($item['descuento'] > 0) {
                $precioFinal = $item['precio'] - ($item['precio'] * ($item['descuento'] / 100));
            }
            $item['precio_final'] = $precioFinal;
            $total += ($precioFinal * $item['cantidad']);
        }

        // Traemos las direcciones del cliente
        $direcciones = $db->table('direcciones_usuarios')->where('usuario_id', $usuario_id)->get()->getResultArray();

        return view('cliente/checkout', [
            'carrito' => $items,
            'total' => $total,
            'direcciones' => $direcciones
        ]);
    }
    public function procesar()
    {
        $db = \Config\Database::connect();
        $usuario_id = session('id');
        $direccion_id = $this->request->getPost('direccion_id');

        // Verificamos que haya seleccionado una dirección
        if (!$direccion_id) {
            return redirect()->back()->with('mensaje', 'Debes seleccionar una dirección de envío.');
        }

        // Guardamos la dirección temporalmente en la sesión para usarla cuando regrese del pago
        session()->set('direccion_envio_id', $direccion_id);

        // Traemos el carrito
        $carritoModel = new CarritoModel();
        $itemsCarrito = $carritoModel->obtenerCarrito($usuario_id);

        // Armamos los "Items" en el formato exacto que pide Mercado Pago
        $itemsMercadoPago = [];
        foreach ($itemsCarrito as $item) {
            $precioFinal = $item['precio'];
            if ($item['descuento'] > 0) {
                $precioFinal = $item['precio'] - ($item['precio'] * ($item['descuento'] / 100));
            }

            $itemsMercadoPago[] = [
                "title"       => $item['nombre'],
                "description" => "Celular de Tienda NEWPHONEMX",
                "quantity"    => (int) $item['cantidad'],
                "currency_id" => "MXN",
                "unit_price"  => (float) $precioFinal
            ];
        }

        // 4. Configuramos la petición a la API de Mercado Pago
        $token = env('MP_ACCESS_TOKEN');
        $client = \Config\Services::curlrequest();

        // Le mandamos las rutas fijas y exactas de tu servidor local
        $body = [
            "items" => $itemsMercadoPago,
            "back_urls" => [
                "success" => "http://localhost:8080/index.php/checkout/exito",
                "failure" => "http://localhost:8080/index.php/carrito",
                "pending" => "http://localhost:8080/index.php/carrito"
            ],
            //"auto_return" => "approved",
            "external_reference" => uniqid('ORDEN_')
        ];

        try {
            // 5. Enviamos todo a Mercado Pago
            $response = $client->post('https://api.mercadopago.com/checkout/preferences', [
                'headers' => [
                    'Authorization' => 'Bearer ' . trim($token), // Quitamos espacios fantasma del token
                    'Content-Type'  => 'application/json'
                ],
                'json' => $body,
                'verify' => false,
                'http_errors' => false // <--- ¡ESTO EVITA QUE C.I. ESCONDA EL ERROR REAL!
            ]);

            $bodyResponse = json_decode($response->getBody());

            // 6. Validamos la respuesta
            if (isset($bodyResponse->init_point)) {
                return redirect()->to($bodyResponse->init_point);
            } else {
                // AHORA SÍ VEREMOS EXACTAMENTE QUÉ LE MOLESTA A MERCADO PAGO
                $errorDetalle = isset($bodyResponse->message) ? $bodyResponse->message : json_encode($bodyResponse);
                return redirect()->back()->with('mensaje', 'Detalle de Mercado Pago: ' . $errorDetalle);
            }

        } catch (\Exception $e) {
            return redirect()->back()->with('mensaje', 'Error de conexión: ' . $e->getMessage());
        }
    }
public function exito()
{
    $db = \Config\Database::connect();
    $usuario_id = session('id');
    $direccion_id = session('direccion_envio_id');
    
    // Capturamos el ID real que manda Mercado Pago por la URL
    $payment_id = $this->request->getGet('payment_id') ?? $this->request->getGet('preference_id') ?? 'Confirmando...'; 

    if (!$direccion_id) {
        return redirect()->to(base_url('carrito'));
    }

    $carritoModel = new \App\Models\CarritoModel();
    $items = $carritoModel->obtenerCarrito($usuario_id);
    
    if (empty($items)) {
        return redirect()->to(base_url('dashboard/cliente'));
    }

    $usuario = $db->table('usuarios')->where('id', $usuario_id)->get()->getRowArray();

    $total = 0;
    $filasTabla = ''; 
    
    foreach ($items as $item) {
        $precioFinal = $item['precio'];
        if ($item['descuento'] > 0) {
            $precioFinal = $item['precio'] - ($item['precio'] * ($item['descuento'] / 100));
        }
        $total += ($precioFinal * $item['cantidad']);
        
        $filasTabla .= "
            <tr>
                <td style='padding: 10px; border-bottom: 1px solid #eee;'>{$item['nombre']} x {$item['cantidad']}</td>
                <td style='padding: 10px; border-bottom: 1px solid #eee; text-align: right;'>$" . number_format($precioFinal * $item['cantidad'], 2) . "</td>
            </tr>";
    }

    // Registro en Base de Datos
    $dataPedido = [
        'cliente_id'         => $usuario_id,
        'direccion_envio_id' => $direccion_id,
        'total'              => $total,
        'estado'             => 'pagado',
        'fecha'              => date('Y-m-d H:i:s')
    ];
    $db->table('pedidos')->insert($dataPedido);
    $pedido_id = $db->insertID(); 

    $detalles = [];
    foreach ($items as $item) {
        $precioFinal = $item['precio'] - ($item['precio'] * ($item['descuento'] / 100));
        $detalles[] = [
            'pedido_id'       => $pedido_id,
            'inventario_id'   => $item['inventario_id'], 
            'cantidad'        => $item['cantidad'],
            'precio_unitario' => $precioFinal
        ];
    }
    $db->table('detalles_pedido')->insertBatch($detalles);

    $db->table('carrito')->where('usuario_id', $usuario_id)->delete();
    session()->remove('direccion_envio_id');

    //DISEÑO DE CORREO 
    $email = \Config\Services::email();
    $email->setFrom('cortes17042003@gmail.com', 'NewPhoneMX Store');
    $email->setTo($usuario['correo']);
    $email->setSubject('✅ Confirmación de Compra #' . $pedido_id . ' - NewPhoneMX');

    $mensajeCorreo = "
    <div style='background-color: #f4f7f6; padding: 30px; font-family: Segoe UI, sans-serif;'>
        <div style='max-width: 600px; margin: auto; background-color: #ffffff; border-radius: 15px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.1);'>
            <div style='background-color: #007bff; padding: 40px; text-align: center; color: white;'>
                <h1 style='margin: 0; font-size: 28px;'>¡Pago Confirmado!</h1>
                <p style='opacity: 0.9;'>Gracias por confiar en NewPhoneMX</p>
            </div>
            <div style='padding: 30px;'>
                <p>Hola <strong>{$usuario['nombre']}</strong>,</p>
                <p>Tu orden ha sido recibida con éxito. Aquí tienes los detalles de tu compra:</p>
                
                <div style='background-color: #f8f9fa; padding: 20px; border-radius: 10px; margin-bottom: 25px;'>
                    <p style='margin: 5px 0;'><strong>ID Pedido:</strong> #{$pedido_id}</p>
                    <p style='margin: 5px 0;'><strong>Transacción MP:</strong> <span style='color: #007bff;'>{$payment_id}</span></p>
                    <p style='margin: 5px 0;'><strong>Fecha:</strong> " . date('d/m/Y H:i') . "</p>
                </div>

                <table style='width: 100%; border-collapse: collapse;'>
                    <thead>
                        <tr>
                            <th style='text-align: left; padding: 10px; border-bottom: 2px solid #eee;'>Producto</th>
                            <th style='text-align: right; padding: 10px; border-bottom: 2px solid #eee;'>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        {$filasTabla}
                    </tbody>
                    <tfoot>
                        <tr>
                            <td style='padding: 20px 10px; font-size: 18px;'><strong>Total Pagado</strong></td>
                            <td style='padding: 20px 10px; font-size: 22px; color: #28a745; text-align: right;'><strong>$" . number_format($total, 2) . "</strong></td>
                        </tr>
                    </tfoot>
                </table>

                <div style='text-align: center; margin-top: 30px;'>
                    <p style='color: #666; font-size: 14px;'>En breve recibirás otro correo con tu guía de rastreo.</p>
                    <p style='margin-top: 20px;'>
                        <a href='" . base_url() . "' style='background-color: #007bff; color: white; padding: 12px 25px; text-decoration: none; border-radius: 25px; font-weight: bold;'>Volver a la Tienda</a>
                    </p>
                </div>
            </div>
            <div style='background-color: #333; color: white; padding: 20px; text-align: center; font-size: 12px;'>
                © 2026 NewPhoneMX Store. Todos los derechos reservados.
            </div>
        </div>
    </div>";

    $email->setMessage($mensajeCorreo);
    $email->send(); 

    return view('cliente/exito', ['pedido_id' => $pedido_id, 'transaccion' => $payment_id]);
}
}