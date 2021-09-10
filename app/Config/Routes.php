<?php namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('AuthController');
$routes->setDefaultMethod('login');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/**
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'AuthController::login');
$routes->get('/reset_password', 'AuthController::resetPassword');
$routes->get('/logout', 'AuthController::logout');
$routes->post('/validation', 'AuthController::validation');
$routes->post('/forgot_password', 'AuthController::forgotPassword');
// ----------------------- Fin de login --------------
$routes->group('cliente', function ($routes){
    $routes->get('password', 'ClienteController::password');
    $routes->post('password/password_update', 'ClienteController::password_update');
    $routes->get('certificado', 'ClienteController::certificado',['as' => 'certificado']);
    $routes->get('certificado/(:segment)','ClienteController::certificado_download/$1');
    $routes->post('certificado/download','ClienteController::certificado_down',['as'=>'certificado_download']);
    $routes->post('certificado/filtrar','ClienteController::certificado_filtrar',['as'=>'filtrar_certificado']);
    $routes->post('certificado/paginar','ClienteController::certificado_paginar',['as'=>'filtrar_paginar']);
    $routes->get('reporte', 'ClienteController::reporte');
    $routes->post('reporte', 'ClienteController::reporte_post');
    $routes->get('user', 'ClienteController::user');
});
    // --------------------- Fin Controller Cliente ---------

$routes->group('funcionario', function ($routes){
    $routes->get('remisiones', 'FuncionarioController::remision');
    $routes->post('remisiones/empresa', 'FuncionarioController::remision_empresa');
    $routes->post('remisiones/muestra', 'FuncionarioController::remision_muestra');
    $routes->get('remisiones/edit', 'FuncionarioController::remision_edit');
    $routes->post('remisiones/edit/muestra', 'FuncionarioController::remision_edit_muestra');
    $routes->get('remisiones/ticket/(:segment)', 'FuncionarioController::remision_ticket/$1');
});
    // -------------------- Fin Controller Funcionario ------

$routes->group('amc-laboratorio', function ($routes){
    $routes->get('home', 'HomeController::index');
    $routes->get('about', 'HomeController::about');
    $routes->get('perfile', 'UserController::perfile');
    $routes->post('update_photo', 'UserController::updatePhoto');
    $routes->post('update_user', 'UserController::updateUser');

});

$routes->post('/config/(:segment)', 'ConfigController::index/$1');
$routes->get('/config/(:segment)', 'ConfigController::index/$1');
$routes->post('/table/(:segment)', 'TableController::index/$1');
$routes->get('/table/(:segment)', 'TableController::index/$1');


/**
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need to it be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
