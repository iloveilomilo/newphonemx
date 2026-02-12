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

// Rutas Protegidas (Grupo Dashboard)
$routes->group('dashboard', ['namespace' => 'App\Controllers\Administrador', 'filter' => 'adminAuth'], function($routes) {
    
    // Panel principal
    $routes->get('admin', 'Dashboard::admin');
    
    // Rutas de Categorías 
    $routes->get('categorias', 'Categorias::index');
    $routes->post('categorias/guardar', 'Categorias::store');
    $routes->get('categorias/eliminar/(:num)', 'Categorias::delete/$1');

    // Rutas de Filtros Globales
    $routes->get('filtros', 'Filtros::index');
    $routes->post('filtros/guardar', 'Filtros::store');
    $routes->get('filtros/eliminar/(:num)', 'Filtros::delete/$1');

    // Rutas de Productos
    $routes->get('productos', 'Productos::index');
    $routes->get('productos/crear', 'Productos::create');
    $routes->post('productos/guardar', 'Productos::store');

    // Rutas de Soporte y Cliente
    $routes->get('soporte', 'Dashboard::soporte');
    $routes->get('cliente', 'Dashboard::cliente');
});