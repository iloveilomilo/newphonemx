<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsuarioModel;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Auth extends BaseController
{
    public function index()
    {
        // Si ya tiene ID en sesión, lo mandamos a su panel segun su rol
        if (session()->has('id')) {
            return $this->redirigirPorRol(session()->get('rol'));
        }
        return view('auth/login');
    }

    public function login()
    {
        $session = session();
        $model = new UsuarioModel();
        
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $data = $model->where('correo', $email)->first();

        if ($data) {
            
            // Bloquear acceso a usuarios dados de baja
            if ($data['activo'] == 0) {
                $session->setFlashdata('msg', 'Tu cuenta ha sido desactivada. Contacta a soporte.');
                return redirect()->to('/login');
            }

            $pass = $data['password'];
            $auth = false;

            // Verificación SEGURA (Hash)
            if (password_verify($password, $pass)) {
                $auth = true;
            } 
            elseif ($password === $pass) {
                $auth = true;
                $model->update($data['id'], ['password' => password_hash($password, PASSWORD_DEFAULT)]);
            }

            if ($auth) {
                // ==========================================
                // MAGIA JWT: Crear Token de 5 Minutos
                // ==========================================
                $llave_secreta = getenv('JWT_SECRET');
                $payload = [
                    'iat' => time(), 
                    'exp' => time() + (5 * 60), 
                    'id_usuario' => $data['id']
                ];

                $token_acceso = JWT::encode($payload, $llave_secreta, 'HS256');

                // ==========================================
                // ENVIAR EL CORREO AL USUARIO
                // ==========================================
                $email_service = \Config\Services::email();

                $config = [
                    'protocol'   => getenv('email.protocol'),
                    'SMTPHost'   => getenv('email.SMTPHost'),
                    'SMTPUser'   => getenv('email.SMTPUser'),
                    'SMTPPass'   => getenv('email.SMTPPass'),
                    'SMTPPort'   => (int) getenv('email.SMTPPort'),
                    'SMTPCrypto' => getenv('email.SMTPCrypto'),
                    'mailType'   => getenv('email.mailType'),
                    'charset'    => getenv('email.charset'),
                    'CRLF'       => "\r\n", 
                    'newline'    => "\r\n"  
                ];
                
                $email_service->initialize($config);

                // Definimos quién lo envía y a quién va
                $email_service->setFrom(getenv('email.SMTPUser'), 'NewPhoneMX Seguridad');
                $email_service->setTo($data['correo']);
                $email_service->setSubject('Acceso Seguro a tu Cuenta - NewPhoneMX');
                
                $enlace = base_url('auth/validar_token/' . $token_acceso);
                
                $html = "<h2>Hola, " . $data['nombre'] . "</h2>";
                $html .= "<p>Has solicitado iniciar sesión. Haz clic en el botón de abajo para entrar de forma segura a tu cuenta.</p>";
                $html .= "<p><b>⚠️ Este enlace caduca en exactamente 5 minutos por tu seguridad.</b></p>";
                $html .= "<a href='{$enlace}' style='display:inline-block; padding:10px 20px; background-color:#6f42c1; color:#ffffff; text-decoration:none; border-radius:5px; font-weight:bold;'>Ingresar a mi cuenta</a>";
                $html .= "<br><br><p><small>Si no solicitaste este acceso, puedes ignorar este correo.</small></p>";
                
                $email_service->setMessage($html);
                
                if ($email_service->send()) {
                    $session->setFlashdata('success', 'Te hemos enviado un enlace de seguridad a tu correo. Tienes 5 minutos para confirmar tu acceso.');
                } else {
                    $session->setFlashdata('msg', 'Hubo un problema al enviar el correo. Por favor, intenta de nuevo o contacta a soporte.');
                }
                
                return redirect()->to('/login');
            } else {
                $session->setFlashdata('msg', 'Contraseña incorrecta');
                return redirect()->to('/login');
            }
        } else {
            $session->setFlashdata('msg', 'Correo no encontrado');
            return redirect()->to('/login');
        }
    }

    // =========================================================
    // VALIDAR EL CLIC DESDE EL CORREO
    // =========================================================
    public function validar_token($token)
    {
        $session = session();
        $llave_secreta = getenv('JWT_SECRET');

        try {
            $decodificado = JWT::decode($token, new Key($llave_secreta, 'HS256'));
            
            // Si llegamos aquí, el token es completamente válido y está en tiempo.
            $id_usuario = $decodificado->id_usuario;

            $model = new UsuarioModel();
            $data = $model->find($id_usuario);

            if (!$data || $data['activo'] == 0) {
                $session->setFlashdata('msg', 'Usuario no encontrado o inactivo.');
                return redirect()->to('/login');
            }

            // Crear el Token de Sesión de 1 DÍA (Mantiene la sesión viva por 24 horas)
            $payload_sesion = [
                'iat' => time(),
                'exp' => time() + (24 * 60 * 60), // 24 horas * 60 minutos * 60 segundos
                'id_usuario' => $data['id']
            ];
            $token_sesion = JWT::encode($payload_sesion, $llave_secreta, 'HS256');

            // Iniciar sesión finalmente
            $ses_data = [
                'id'           => $data['id'],
                'nombre'       => $data['nombre'],
                'email'        => $data['correo'],
                'rol'          => $data['rol'],
                'foto_perfil'  => $data['foto_perfil'] ?? null,
                'token_sesion' => $token_sesion, 
                'is_logged_in' => true
            ];
            $session->set($ses_data);

            return $this->redirigirPorRol($data['rol']);

        } catch (\Exception $e) {
            // El token expiró o es inválido
            $session->setFlashdata('msg', 'El enlace de seguridad ha caducado (pasaron más de 5 minutos) o es inválido. Vuelve a iniciar sesión.');
            return redirect()->to('/login');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }

    // Función auxiliar para redirigir según rol
    private function redirigirPorRol($rol) {
        switch($rol) {
            case 'admin': return redirect()->to('/admin/panel');
            case 'atencion_cliente': return redirect()->to('soporte/soporte');
            default: return redirect()->to('dashboard/cliente');
        }
    }

    // =========================================================
    // FUNCIONES DE REGISTRO DE NUEVOS CLIENTES
    // =========================================================

    public function registro()
    {
        // Muestra la vista del formulario
        return view('auth/registro');
    }

    public function guardar_registro()
    {
        $usuarioModel = new \App\Models\UsuarioModel();

        // 1. Recibimos el correo
        $correo = $this->request->getPost('correo');

        // 2. Verificamos que el correo no exista ya en la base de datos
        $existe = $usuarioModel->where('correo', $correo)->first();
        if ($existe) {
            return redirect()->back()->with('error', 'Este correo ya está registrado. Intenta iniciar sesión.');
        }

        // 3. Preparamos el arreglo con los datos exactos de tu BD
        $data = [
            'nombre'    => $this->request->getPost('nombre'),
            'apellidos' => $this->request->getPost('apellidos'),
            'correo'    => $correo,
            'telefono'  => $this->request->getPost('telefono'),
            'password'  => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT), // Encriptación obligatoria
            'rol'       => 'cliente', // Forzamos el rol para que no entren al panel admin
            'activo'    => 1
        ];

        // 4. Insertamos en la base de datos
        $usuarioModel->insert($data);

        // 5. Redirigimos al login con mensaje de éxito
        session()->setFlashdata('msg', '¡Cuenta creada exitosamente! Ya puedes iniciar sesión.');
        return redirect()->to(base_url('login'));
    }
}