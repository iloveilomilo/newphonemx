<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Rutas Públicas y de Autenticación

// página de inicio para TODOS
$routes->get('/', 'Administrador\Dashboard::cliente'); 
$routes->get('dashboard/cliente', 'Administrador\Dashboard::cliente'); 
$routes->get('tienda/producto/(:num)', 'Administrador\Dashboard::detalle/$1');

// Rutas de Login y Registro
$routes->get('/login', 'Auth::index');
$routes->post('/auth/login', 'Auth::login');
$routes->get('/logout', 'Auth::logout');
$routes->get('/registro', 'Auth::registro'); 
$routes->post('/auth/guardar_registro', 'Auth::guardar_registro'); 

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

    // Usuarios
    $routes->get('usuarios', 'Usuarios::index');
    $routes->post('usuarios/guardar', 'Usuarios::store');
    $routes->get('usuarios/eliminar/(:num)', 'Usuarios::delete/$1');
    $routes->get('usuarios/reactivar/(:num)', 'Usuarios::reactivar/$1');
    
    // Productos
    $routes->get('productos', 'Productos::index');
    $routes->get('productos/crear', 'Productos::create');
    $routes->post('productos/guardar', 'Productos::store');
    
    // Edición de Productos
    $routes->get('productos/editar/(:num)', 'Productos::edit/$1');
    $routes->post('productos/actualizar/(:num)', 'Productos::actualizar/$1');

    // Eliminación/Baja de Productos
    $routes->get('productos/eliminar/(:num)', 'Productos::delete/$1');

    // Soporte Interno (Chat entre Administrador y Atencion al Cliente)
    $routes->get('soporte', 'SoporteAdmin::index');
    $routes->get('soporte/chat/(:num)', 'SoporteAdmin::ver_chat/$1');
    $routes->post('soporte/responder', 'SoporteAdmin::responder');

    // Perfil del Administrador
    $routes->get('perfil', 'Perfil::index');
    $routes->post('perfil/actualizar', 'Perfil::actualizar_datos');
});

// =================================================================
// Rutas para Soporte (Atención al Cliente)
// =================================================================
$routes->group('admin', function($routes) {
    // Tus rutas actuales
    $routes->get('soporte', 'Administrador\Soporte::index');
    $routes->get('soporte/mensajes', 'Administrador\Soporte::mensajes');
    $routes->get('soporte/historial', 'Administrador\Soporte::historial');
    $routes->get('soporte/responder', 'Administrador\Soporte::responder');

    // ¡Nuevas rutas para que los botones y formularios funcionen!
    $routes->post('soporte/enviar_mensaje', 'Administrador\Soporte::enviar_mensaje');
    $routes->post('soporte/actualizar_conversacion', 'Administrador\Soporte::actualizar_conversacion');
    $routes->get('soporte/cerrar_conversacion/(:num)', 'Administrador\Soporte::cerrar_conversacion/$1');
});
$routes->group('soporte', ['namespace' => 'App\Controllers\Soporte', 'filter' => 'soporteAuth'], function($routes) {
    $routes->get('soporte', 'Soporte::index');
    $routes->get('mensajes', 'Soporte::mensajes');
    $routes->get('historial', 'Soporte::historial');
    $routes->get('responder', 'Soporte::responder');
});


// =================================================================
// Rutas para Clientes
// =================================================================


// Rutas para el Carrito de Compras
$routes->get('carrito', 'cliente\Carrito::index', ['filter' => 'clienteAuth']);
$routes->post('carrito/agregar', 'cliente\Carrito::agregar', ['filter' => 'clienteAuth']);
$routes->get('carrito/eliminar/(:segment)', 'cliente\Carrito::eliminar/$1', ['filter' => 'clienteAuth']);
$routes->post('carrito/actualizar', 'cliente\Carrito::actualizar', ['filter' => 'clienteAuth']); 
$routes->post('soporte/enviar_duda', 'cliente\SoporteCliente::enviar_duda', ['filter' => 'clienteAuth']);

// Historial de preguntas del cliente
$routes->get('mis-preguntas', 'cliente\SoporteCliente::mis_preguntas', ['filter' => 'clienteAuth']);
// Rutas para el chat individual
$routes->get('mis-preguntas/chat/(:num)', 'cliente\SoporteCliente::ver_chat/$1', ['filter' => 'clienteAuth']);
$routes->post('mis-preguntas/responder', 'cliente\SoporteCliente::responder_chat', ['filter' => 'clienteAuth']);

// Rutas para Mi Perfil y Direcciones
$routes->get('perfil', 'cliente\Perfil::index', ['filter' => 'clienteAuth']);
$routes->post('perfil/actualizar_datos', 'cliente\Perfil::actualizar_datos', ['filter' => 'clienteAuth']);
$routes->post('perfil/guardar_direccion', 'cliente\Perfil::guardar_direccion', ['filter' => 'clienteAuth']);
$routes->get('perfil/eliminar_direccion/(:num)', 'cliente\Perfil::eliminar_direccion/$1', ['filter' => 'clienteAuth']);

//Envio de correo login
$routes->get('auth/validar_token/(:any)', 'Auth::validar_token/$1');

// Rutas para Pagos y Checkout
$routes->get('checkout', 'cliente\Checkout::index', ['filter' => 'clienteAuth']);
$routes->post('checkout/procesar', 'cliente\Checkout::procesar', ['filter' => 'clienteAuth']);
$routes->get('checkout/exito', 'cliente\Checkout::exito', ['filter' => 'clienteAuth']);

//mis compras
$routes->get('mis-compras', 'cliente\Compras::index', ['filter' => 'clienteAuth']);

// Grupo para futuras rutas exclusivas
$routes->group('cliente', ['namespace' => 'App\Controllers\Cliente', 'filter' => 'clienteAuth'], function($routes) {
});