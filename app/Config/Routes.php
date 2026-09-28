<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'PersonaController::index');

// Módulo Sexo
$routes->group('sexo', static function ($routes) {
    $routes->get('', 'SexoController::index');
    $routes->get('create', 'SexoController::create');
    $routes->post('store', 'SexoController::store');
    $routes->get('edit/(:num)', 'SexoController::edit/$1');
    $routes->post('update/(:num)', 'SexoController::update/$1');
    $routes->get('delete/(:num)', 'SexoController::delete/$1');
});

// Módulo Persona
$routes->group('persona', static function ($routes) {
    $routes->get('', 'PersonaController::index');
    $routes->get('ver/(:num)', 'PersonaController::index/$1');
    $routes->get('listar', 'PersonaController::listar');
    $routes->get('create', 'PersonaController::create');
    $routes->post('store', 'PersonaController::store');
    $routes->get('show/(:num)', 'PersonaController::show/$1');
    $routes->get('edit/(:num)', 'PersonaController::edit/$1');
    $routes->post('update/(:num)', 'PersonaController::update/$1');
    $routes->get('delete/(:num)', 'PersonaController::delete/$1');
    $routes->post('subirFoto/(:num)', 'PersonaController::subirFoto/$1');
    $routes->match(['get', 'head'], 'foto/(:num)', 'PersonaController::foto/$1');
    $routes->get('eliminarFoto/(:num)', 'PersonaController::eliminarFoto/$1');
});

// Módulo Provincia
$routes->group('provincia', static function ($routes) {
    $routes->get('', 'ProvinciaController::index');
    $routes->get('create', 'ProvinciaController::create');
    $routes->post('store', 'ProvinciaController::store');
    $routes->get('edit/(:num)', 'ProvinciaController::edit/$1');
    $routes->post('update/(:num)', 'ProvinciaController::update/$1');
    $routes->get('delete/(:num)', 'ProvinciaController::delete/$1');
});

// Módulo Cantón
$routes->group('canton', static function ($routes) {
    $routes->get('', 'CantonController::index');
    $routes->get('create', 'CantonController::create');
    $routes->post('store', 'CantonController::store');
    $routes->get('edit/(:num)', 'CantonController::edit/$1');
    $routes->post('update/(:num)', 'CantonController::update/$1');
    $routes->get('delete/(:num)', 'CantonController::delete/$1');
});

// Módulo Parroquia
$routes->group('parroquia', static function ($routes) {
    $routes->get('', 'ParroquiaController::index');
    $routes->get('create', 'ParroquiaController::create');
    $routes->post('store', 'ParroquiaController::store');
    $routes->get('edit/(:num)', 'ParroquiaController::edit/$1');
    $routes->post('update/(:num)', 'ParroquiaController::update/$1');
    $routes->get('delete/(:num)', 'ParroquiaController::delete/$1');
});

// Módulo Zona
$routes->group('zona', static function ($routes) {
    $routes->get('', 'ZonaController::index');
    $routes->get('create', 'ZonaController::create');
    $routes->post('store', 'ZonaController::store');
    $routes->get('edit/(:num)', 'ZonaController::edit/$1');
    $routes->post('update/(:num)', 'ZonaController::update/$1');
    $routes->get('delete/(:num)', 'ZonaController::delete/$1');
});

// Módulo Recinto Electoral
$routes->group('recintoelectoral', static function ($routes) {
    $routes->get('', 'RecintoelectoralController::index');
    $routes->get('create', 'RecintoelectoralController::create');
    $routes->post('store', 'RecintoelectoralController::store');
    $routes->get('edit/(:num)', 'RecintoelectoralController::edit/$1');
    $routes->post('update/(:num)', 'RecintoelectoralController::update/$1');
    $routes->get('delete/(:num)', 'RecintoelectoralController::delete/$1');
});

// Módulo Mesa Electoral
$routes->group('meza', static function ($routes) {
    $routes->get('', 'MezaController::index');
    $routes->get('ver/(:num)', 'MezaController::index/$1');
    $routes->get('listar', 'MezaController::listar');
    $routes->get('create', 'MezaController::create');
    $routes->post('store', 'MezaController::store');
    $routes->get('edit/(:num)', 'MezaController::edit/$1');
    $routes->post('update/(:num)', 'MezaController::update/$1');
    $routes->get('delete/(:num)', 'MezaController::delete/$1');
    $routes->post('subirActa/(:num)', 'MezaController::subirActa/$1');
    $routes->match(['get', 'head'], 'acta/(:num)', 'MezaController::acta/$1');
    $routes->get('eliminarActa/(:num)', 'MezaController::eliminarActa/$1');
});

// Módulo Tipo de Dignidad
$routes->group('tipodignidad', static function ($routes) {
    $routes->get('', 'TipodignidadController::index');
    $routes->get('create', 'TipodignidadController::create');
    $routes->post('store', 'TipodignidadController::store');
    $routes->get('edit/(:num)', 'TipodignidadController::edit/$1');
    $routes->post('update/(:num)', 'TipodignidadController::update/$1');
    $routes->get('delete/(:num)', 'TipodignidadController::delete/$1');
});

// Módulo Dignidad / Candidatura
$routes->group('dignidad', static function ($routes) {
    $routes->get('', 'DignidadController::index');
    $routes->get('ver/(:num)', 'DignidadController::index/$1');
    $routes->get('listar', 'DignidadController::listar');
    $routes->get('create', 'DignidadController::create');
    $routes->post('store', 'DignidadController::store');
    $routes->get('edit/(:num)', 'DignidadController::edit/$1');
    $routes->post('update/(:num)', 'DignidadController::update/$1');
    $routes->get('delete/(:num)', 'DignidadController::delete/$1');
});

// Módulo Asignación Mesa - Dignidad (Papeletas)
$routes->group('mezadignidad', static function ($routes) {
    $routes->get('', 'MezadignidadController::index');
    $routes->get('create', 'MezadignidadController::create');
    $routes->match(['get', 'post'], 'store', 'MezadignidadController::store');
    $routes->get('edit/(:num)', 'MezadignidadController::edit/$1');
    $routes->match(['get', 'post'], 'update/(:num)', 'MezadignidadController::update/$1');
    $routes->get('delete/(:num)', 'MezadignidadController::delete/$1');
});






// Módulo Rol de Usuario
$routes->group('rolusuario', static function ($routes) {
    $routes->get('', 'RolusuarioController::index');
    $routes->get('create', 'RolusuarioController::create');
    $routes->post('store', 'RolusuarioController::store');
    $routes->get('edit/(:num)', 'RolusuarioController::edit/$1');
    $routes->post('update/(:num)', 'RolusuarioController::update/$1');
    $routes->get('delete/(:num)', 'RolusuarioController::delete/$1');
});

// Módulo Usuario
$routes->group('usuario', static function ($routes) {
    $routes->get('', 'UsuarioController::index');
    $routes->get('create', 'UsuarioController::create');
    $routes->post('store', 'UsuarioController::store');
    $routes->get('show/(:num)', 'UsuarioController::show/$1');
    $routes->get('edit/(:num)', 'UsuarioController::edit/$1');
    $routes->post('update/(:num)', 'UsuarioController::update/$1');
    $routes->get('delete/(:num)', 'UsuarioController::delete/$1');
});
