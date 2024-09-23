<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/', 'Home::index');


/**************************************************************************************************************************************************
    RUTAS PARA METODOS NORMALES:
**************************************************************************************************************************************************/
$routes->get('/',                'InicioController::index');
$routes->get('login',            'LoginController::index');
$routes->get('dashboard',        'DashboardController::index');
$routes->post('inicio/pdfMayor', 'InicioController::pdfMayor');
$routes->post('inicio/pdfMenor', 'InicioController::pdfMenor');
$routes->get('usuario',          'UsuarioController::index');
$routes->get('galeria',          'GaleriaController::index');
$routes->get('productos',        'ProductosController::index');
$routes->get('solicitudes',      'SolicitudesController::index');


/**************************************************************************************************************************************************
    RUTAS PARA METODOS CON AJAX:
**************************************************************************************************************************************************/

// INICIO CONTROLLER
$routes->get('inicio/setDepartamentos', 'InicioController::setDepartamentos');
$routes->post('inicio/setMunicipios',   'InicioController::setMunicipios');
$routes->post('inicio/validarDUI',      'InicioController::validarDUI');
$routes->post('inicio/validarTel',      'InicioController::validarTel');
$routes->post('inicio/validarEmail',    'InicioController::validarEmail');
$routes->post('inicio/createVolMayor',  'InicioController::createVolMayor');
$routes->post('inicio/createVolMenor',  'InicioController::createVolMenor');

// LOGIN CONTROLLER
$routes->post('login/verifica',         'LoginController::verifica');
$routes->get('login/logout',            'LoginController::logout');
$routes->get('resetPassword',           'LoginController::resetPassword');
$routes->post('login/validarEmail',     'LoginController::validarEmail');
$routes->post('sendPasswordResetEmail', 'LoginController::sendPasswordResetEmail');
$routes->get('changePassword',          'LoginController::changePassword');
$routes->post('updatePassword',         'LoginController::updatePassword');

// USUARIO CONTROLLER
$routes->post('usuario/tblUsuarios',   'UsuarioController::tblUsuarios');
$routes->post('usuario/validarEmail',  'UsuarioController::validarEmail');
$routes->post('usuario/validarUser',   'UsuarioController::validarUser');
$routes->get('usuario/setRoles',       'UsuarioController::setRoles');
$routes->post('usuario/create',        'UsuarioController::create');
$routes->post('usuario/cambiarEstado', 'UsuarioController::cambiarEstado');

// GALERIA CONTROLLER
$routes->post('galeria/cargarImg',  'GaleriaController::cargarImg');
$routes->post('galeria/cambiarImg', 'GaleriaController::cambiarImg');
$routes->get('galeria/tblGaleria',  'GaleriaController::tblGaleria');

// PRODUCTOS CONTROLLER
$routes->get('productos/verProductos',   'ProductosController::verProductos');
$routes->get('productos/tblProductos',   'ProductosController::tblProductos');
$routes->post('productos/cambiarEstado', 'ProductosController::cambiarEstado');

// SOLICITUDES CONTROLLER
$routes->get('solicitudes/verSolicitudMayores', 'SolicitudesController::verSolicitudMayores');
$routes->get('solicitudes/verSolicitudMenores', 'SolicitudesController::verSolicitudMenores');
