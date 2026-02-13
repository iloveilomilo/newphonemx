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
            $pass = $data['password'];
            $auth = false;

            // 1. Verificación SEGURA (Hash)
            if (password_verify($password, $pass)) {
                $auth = true;
            } 
            // 2. Si la contraseña en BD es texto plano la encriptamos (esto solo pasará la primera vez, luego se encripta)
            elseif ($password === $pass) {
                $auth = true;
                // Actualizamos la BD con el hash seguro
                $model->update($data['id'], ['password' => password_hash($password, PASSWORD_DEFAULT)]);
            }

            if ($auth) {
                // Guardamos ID y Rol para permisos
                $ses_data = [
                    'id'       => $data['id'],
                    'nombre'   => $data['nombre'],
                    'email'    => $data['correo'],
                    'rol'      => $data['rol'],
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
        return redirect()->to('/login');
    }

    // Función auxiliar para redirigir según rol
    private function redirigirPorRol($rol) {
        switch($rol) {
            case 'admin': return redirect()->to('/admin/panel');
            case 'atencion_cliente': return redirect()->to('/dashboard/soporte');
            default: return redirect()->to('dashboard/cliente');
        }
    }
}