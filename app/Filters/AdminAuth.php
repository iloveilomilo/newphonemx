<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminAuth implements FilterInterface  // <--- CAMBIADO A AdminAuth
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // 1. Verificar si hay sesión iniciada (Tu lógica de ID)
        if (!session()->has('id')) {
            return redirect()->to('/login')->with('msg', 'Debes iniciar sesión primero.');
        }

        // 2. Protección de rutas por ROL
        $rol = session()->get('rol');
        $uri = service('uri');
        
        // Si intenta entrar a /dashboard/admin pero NO es admin
        if ($uri->getSegment(2) == 'admin' && $rol != 'admin') {
            return redirect()->to('/dashboard/cliente');
        }
        
        // Si intenta entrar a /dashboard/soporte pero es cliente
        if ($uri->getSegment(2) == 'soporte' && $rol == 'cliente') {
            return redirect()->to('/dashboard/cliente');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nada aquí
    }
}