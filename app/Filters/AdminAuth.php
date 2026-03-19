<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // 1. ¿Está logueado?
        if (!session()->has('id')) {
            return redirect()->to('/login')->with('msg', 'Debes iniciar sesión primero.');
        }

        // 2. ¿Es Administrador? Si NO lo es, lo sacamos.
        if (session()->get('rol') !== 'admin') {
            return redirect()->to('/dashboard/cliente')->with('msg', 'Acceso denegado. Área exclusiva de administración.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nada aquí
    }
}