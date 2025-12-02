<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Auth Routes
$routes->get('/', 'Auth::login');
$routes->get('/auth/login', 'Auth::login');
$routes->post('/auth/processLogin', 'Auth::processLogin');
$routes->get('/auth/logout', 'Auth::logout');

// Dashboard
$routes->get('/dashboard', 'Dashboard::index', ['filter' => 'auth']);

// Pasien Routes
$routes->group('pasien', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Pasien::index');
    $routes->get('create', 'Pasien::create');
    $routes->post('store', 'Pasien::store');
    $routes->get('edit/(:num)', 'Pasien::edit/$1');
    $routes->post('update/(:num)', 'Pasien::update/$1');
    $routes->get('delete/(:num)', 'Pasien::delete/$1');
});

// Prediksi Routes
$routes->group('prediksi', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Prediksi::index');
    $routes->get('create', 'Prediksi::create');
    $routes->post('store', 'Prediksi::store');
    $routes->get('detail/(:num)', 'Prediksi::detail/$1');
});

// Petugas Routes (Admin only)
$routes->group('petugas', ['filter' => 'auth:admin'], function($routes) {
    $routes->get('/', 'Petugas::index');
    $routes->get('create', 'Petugas::create');
    $routes->post('store', 'Petugas::store');
    $routes->get('edit/(:num)', 'Petugas::edit/$1');
    $routes->post('update/(:num)', 'Petugas::update/$1');
    $routes->get('delete/(:num)', 'Petugas::delete/$1');
    $routes->get('toggleStatus/(:num)', 'Petugas::toggleStatus/$1');
});

// Laporan Routes
$routes->group('laporan', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Laporan::index');
    $routes->get('hasil', 'Laporan::hasil');
    $routes->get('bulanan', 'Laporan::bulanan');
    $routes->get('export/pdf', 'Laporan::exportPdf');
    $routes->get('export/excel', 'Laporan::exportExcel');
    // Aliases for backward compatibility
    $routes->get('exportPdf', 'Laporan::exportPdf');
    $routes->get('exportExcel', 'Laporan::exportExcel');
});
