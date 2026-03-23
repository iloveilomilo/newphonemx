<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // 1. ¿Está logueado?
        if (!$session->has('id')) {
            return redirect()->to('/login')->with('alerta_intruso', 'Debes iniciar sesión primero.');
        }

        // 2. ¿Es Administrador? Si NO lo es, lo sacamos.
        if ($session->get('rol') !== 'admin') {
            return redirect()->to('/dashboard/cliente')->with('alerta_intruso', 'Acceso denegado. Área exclusiva de administración.');
        }

        // =========================================================
        // 3. LÓGICA DE INACTIVIDAD (Seguridad)
        // =========================================================
        
        // Tiempo máximo en segundos (900 = 15 minutos). 
        // Cámbialo a 10 para tomar tu captura de pantalla, luego regrésalo a 900.
        $tiempo_maximo = 9000; 

        if ($session->has('ultima_actividad')) {
            $tiempo_inactivo = time() - $session->get('ultima_actividad');

            if ($tiempo_inactivo > $tiempo_maximo) {
                // En lugar de destroy(), solo borramos sus credenciales
                $session->remove(['id', 'rol', 'ultima_actividad']);
                
                return redirect()->to('/login')->with('alerta_intruso', 'Tu sesión ha expirado por inactividad. Por tu seguridad, vuelve a ingresar.');
            }
        }

        // 4. Si el usuario interactúa, reiniciamos el reloj al segundo actual
        $session->set('ultima_actividad', time());
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nada aquí
    }
}