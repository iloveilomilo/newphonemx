<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Auth::index');

// Rutas de Login/Logout
$routes->get('/login', 'Auth::index');
$routes->post('/auth/login', 'Auth::login');
$routes->get('/logout', 'Auth::logout');

// =================================================================
// Rutas para Administración
// =================================================================
$routes->group('admin', ['namespace' => 'App\Controllers\Administrador', 'filter' => 'adminAuth'], function($routes) {
    
    $routes->get('panel', 'Dashboard::admin'); 
    
    // Categorías
    $routes->get('categorias', 'Categorias::index');
    $routes->post('categorias/guardar', 'Categorias::store');
    $routes->get('categorias/eliminar/(:num)', 'Categorias::delete/$1');
    
    // Filtros
    $routes->get('filtros', 'Filtros::index');
    $routes->post('filtros/guardar', 'Filtros::store');
    $routes->get('filtros/eliminar/(:num)', 'Filtros::delete/$1');
    
    // Productos
    $routes->get('productos', 'Productos::index');
    $routes->get('productos/crear', 'Productos::create');
    $routes->post('productos/guardar', 'Productos::store');
});

// =================================================================
// Rutas para Soporte (Atención al Cliente)
// =================================================================
$routes->group('soporte', ['namespace' => 'App\Controllers\Soporte', 'filter' => 'soporteAuth'], function($routes) {
    // Aquí tu compañero pondrá sus rutas, ej:
    // $routes->get('chats', 'Dashboard::index');
});

// =================================================================
// Rutas para Clientes
// =================================================================
$routes->get('dashboard/cliente', 'Administrador\Dashboard::cliente');
$routes->get('tienda/producto/(:num)', 'Administrador\Dashboard::detalle/$1');

$routes->group('cliente', ['namespace' => 'App\Controllers\Cliente', 'filter' => 'clienteAuth'], function($routes) {
    // Aquí tu otro compañero pondrá sus rutas, ej:
    // $routes->get('mi-cuenta', 'Perfil::index');
});