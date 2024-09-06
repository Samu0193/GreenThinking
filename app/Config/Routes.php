<?php

use CodeIgniter\Router\RouteCollection;
use App\Controllers\InicioController;
use App\Controllers\LoginController;
use App\Controllers\DashboardController;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/', 'Home::index');


/**************************************************************************************************************************************************
    RUTAS PARA METODOS NORMALES:
**************************************************************************************************************************************************/
$routes->get('/', 'InicioController::index');
$routes->get('login', 'LoginController::index');
$routes->get('dashboard', 'DashboardController::index');
$routes->post('inicio/pdfMayor', 'InicioController::pdfMayor');
$routes->post('inicio/pdfMenor', 'InicioController::pdfMenor');

/**************************************************************************************************************************************************
    RUTAS PARA METODOS CON AJAX:
**************************************************************************************************************************************************/

// INICIO CONTROLLER
$routes->get('inicio/setDepartamentos', 'InicioController::setDepartamentos');
$routes->post('inicio/setMunicipios', 'InicioController::setMunicipios');
$routes->post('inicio/validarDUI', 'InicioController::validarDUI');
$routes->post('inicio/validarTel', 'InicioController::validarTel');
$routes->post('inicio/validarEmail', 'InicioController::validarEmail');
$routes->post('inicio/guardar1', 'InicioController::guardar1');
$routes->post('inicio/guardar2', 'InicioController::guardar2');

// LOGIN CONTROLLER
$routes->post('login/verifica', 'LoginController::verifica');
$routes->get('login/logout', 'LoginController::logout');
$routes->post('login/test', 'LoginController::test');
$routes->get('login/forgotPassword', 'LoginController::forgotPassword');
$routes->post('login/forgotPassword', 'LoginController::forgotPassword');
$routes->get('login/password', 'LoginController::password');
$routes->post('login/password', 'LoginController::password');

// USUARIO CONTROLLER
$routes->post('usuario/tblUsuarios', 'UsuarioController::tblUsuarios');
$routes->get('usuario', 'UsuarioController::index');
$routes->post('usuario/validarEmail', 'UsuarioController::validarEmail');
$routes->post('usuario/validarUser', 'UsuarioController::validarUser');
$routes->get('usuario/setRoles', 'UsuarioController::setRoles');
$routes->post('usuario/guardar', 'UsuarioController::guardar');
$routes->post('usuario/cambiarEstado', 'UsuarioController::cambiarEstado');

// GALERIA CONTROLLER
$routes->get('galeria', 'GaleriaController::index');
$routes->get('galeria/printImgGalery', 'GaleriaController::printImgGalery');
$routes->post('galeria/cargarImg', 'GaleriaController::cargarImg');
$routes->post('galeria/cambiarImg', 'GaleriaController::cambiarImg');
$routes->get('galeria/tblGaleria', 'GaleriaController::tblGaleria');

// PRODUCTOS CONTROLLER
$routes->get('productos', 'ProductosController::index');
$routes->get('productos/verProductos', 'ProductosController::verProductos');
$routes->get('productos/tblProductos', 'ProductosController::tblProductos');
$routes->post('productos/cambiarEstado', 'ProductosController::cambiarEstado');

// SOLICITUDES CONTROLLER
$routes->get('solicitudes', 'SolicitudesController::index');
$routes->get('solicitudes/verSolicitudMayores', 'SolicitudesController::verSolicitudMayores');
$routes->get('solicitudes/verSolicitudMenores', 'SolicitudesController::verSolicitudMenores');
