<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Login::index');
$routes->get('register', 'Register::index');
$routes->post('register/save', 'Register::save');


$routes->group('admin', function($routes) {
    $routes->get('dashboard', 'Admin::index');      // URL: public/admin/dashboard
    $routes->get('menu', 'Admin::kelola_menu');    // URL: public/admin/menu
});

// --- ROUTES UNTUK KASIR ---
$routes->group('kasir', function($routes) {
    $routes->get('dashboard', 'Kasir::index');      // URL: public/kasir/dashboard
    $routes->get('menu', 'Kasir::kelola_menu');    // URL: public/kasir/menu
});