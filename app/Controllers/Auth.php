<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsuarioModel;

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
            // Si la contraseña en BD es texto plano la encriptamos (esto solo pasará la primera vez, luego se encripta)
            elseif ($password === $pass) {
                $auth = true;
                // Actualiza la BD con el hash seguro
                $model->update($data['id'], ['password' => password_hash($password, PASSWORD_DEFAULT)]);
            }

            if ($auth) {
                $ses_data = [
                    'id'       => $data['id'],
                    'nombre'   => $data['nombre'],
                    'email'    => $data['correo'],
                    'rol'      => $data['rol'],
                    'foto_perfil' => $data['foto_perfil'] ?? null,
                    'is_logged_in' => true
                ];
                $session->set($ses_data);
                return $this->redirigirPorRol($data['rol']);
            } else {
                $session->setFlashdata('msg', 'Contraseña incorrecta');
                return redirect()->to('/login');
            }
        } else {
            $session->setFlashdata('msg', 'Correo no encontrado');
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