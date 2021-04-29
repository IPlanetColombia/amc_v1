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
$routes->post('/amc-laboratorio/password/password_update', 'HomeController::password_update');
// $routes->get('/amc-laboratorio/password/password_update', 'HomeController::password_update');
$routes->get('/amc-laboratorio/home', 'HomeController::index');
$routes->get('/amc-laboratorio/about', 'HomeController::about');
$routes->get('/amc-laboratorio/password', 'HomeController::password');
$routes->get('/amc-laboratorio/perfile', 'UserController::perfile');
$routes->post('/amc-laboratorio/update_photo', 'UserController::updatePhoto');
$routes->post('/amc-laboratorio/update_user', 'UserController::updateUser');
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
