<?php

namespace App\Controllers;

use App\Models\UsuarioModel;
use App\Controllers\BaseController;

class UsuarioController extends BaseController
{
    protected $modelUsuario;

    public function __construct()
    {
        $this->modelUsuario = new UsuarioModel();
    }

    // ****************************************************************************************************************************
    // *!*   CARGAR LA VISTA DE USUARIOS:
    // ****************************************************************************************************************************
    public function index()
    {
        $session = session();
        if ($session->get('is_logged') && $session->get('id_rol') == 1) {

            $sessionData = $session->get();
            $data = [
                'title'        => ' - Usuarios',
                'session_data' => $sessionData
            ];

            return view('layout/header', $data) .
                   view('layout/navegacion') .
                   view('usuario/index') .
                   view('layout/footer');
        }

        // Redirigir con mensaje flash directamente y guardarlo
        return redirect()->to(site_url('login'))->with('message', 'Usted no se ha identificado');
    }

    
    // ****************************************************************************************************************************
    // ****************************************************************************************************************************
    //                        ******                      ****         ******          ****             ****
    //                       ********	                  ****        ********           ****         ****
    //                      ****  ****	                  ****       ****  ****            ****     ****
    //                     ****    ****	                  ****      ****    ****             *********
    //                    **************   	 ****         ****     **************            *********
    //                   ****************	 ****         ****    ****************         ****     ****
    //                  ****          ****    ***************    ****          ****      ****         ****
    //                 ****            ****     ***********     ****            ****   ****             ****
    // ****************************************************************************************************************************
    // ****************************************************************************************************************************


    // ****************************************************************************************************************************
    // *!*   LLENAR SELECT ROLES (AJAX):
    // ****************************************************************************************************************************
    public function setRoles()
    {
        if ($this->request->isAJAX()) {

            try {

                $roles = $this->modelUsuario->getRolesModel();
                if (!$roles) {
                    $jsonResponse = $this->responseUtil->setResponse(404, 'not_found', 'No hay registros', []);
                    return $this->response->setStatusCode(404)->setJSON($jsonResponse);
                }

                $message = count($roles) . ' registros encontrados';
                $jsonResponse = $this->responseUtil->setResponse(200, 'success', $message, $roles);
                return $this->response->setStatusCode(200)->setJSON($jsonResponse);

            } catch (\CodeIgniter\Database\Exceptions\DatabaseException $dbException) {
                $jsonResponse = $this->responseUtil->setResponse(500, 'server_error', 'Error en la base de datos', []);
                $this->responseUtil->logWithContext($this->responseUtil->setResponse(500, 'server_error', 'Database error: ' . $dbException->getMessage(), []));
                return $this->response->setStatusCode(500)->setJSON($jsonResponse);

            } catch (\Exception $e) {
                $jsonResponse = $this->responseUtil->setResponse(500, 'server_error', 'Error inesperado', []);
                $this->responseUtil->logWithContext($this->responseUtil->setResponse(500, 'server_error', 'Exception: ' . $e->getMessage(), []));
                return $this->response->setStatusCode(500)->setJSON($jsonResponse);
            }

        }

        return redirect()->back();
    }

    // ****************************************************************************************************************************
    // *!*   VALIDAR CORREO (AJAX):
    // ****************************************************************************************************************************
    public function validarEmail()
    {
        if ($this->request->isAJAX()) {

            try {

                $email = $this->request->getPost('email');
                if (!$email) {
                    $jsonResponse = $this->responseUtil->setResponse(400, 'error', 'Email no proporcionado', []);
                    return $this->response->setStatusCode(400)->setJSON($jsonResponse);
                }

                $resultado = $this->modelUsuario->findEmail($email);
                if (!$resultado) {
                    $jsonResponse = $this->responseUtil->setResponse(200, 'success', 'Email disponible', true);
                    return $this->response->setStatusCode(200)->setJSON($jsonResponse);
                }

                $jsonResponse = $this->responseUtil->setResponse(200, 'success', 'Este email ya está registrado', false);
                return $this->response->setStatusCode(200)->setJSON($jsonResponse);

            } catch (\CodeIgniter\Database\Exceptions\DatabaseException $dbException) {
                $jsonResponse = $this->responseUtil->setResponse(500, 'server_error', 'Error en la base de datos', []);
                $this->responseUtil->logWithContext($this->responseUtil->setResponse(500, 'server_error', 'Database error: ' . $dbException->getMessage(), []));
                return $this->response->setStatusCode(500)->setJSON($jsonResponse);

            } catch (\Exception $e) {
                $jsonResponse = $this->responseUtil->setResponse(500, 'server_error', 'Error inesperado', []);
                $this->responseUtil->logWithContext($this->responseUtil->setResponse(500, 'server_error', 'Exception: ' . $e->getMessage(), []));
                return $this->response->setStatusCode(500)->setJSON($jsonResponse);
            }

        }

        return redirect()->back();
    }

    // ****************************************************************************************************************************
    // *!*   VALIDAR USUARIO (AJAX):
    // ****************************************************************************************************************************
    public function validarUser()
    {
        if ($this->request->isAJAX()) {

            try {

                $usuario = $this->request->getPost('nombre_usuario');
                if (!$usuario) {
                    $jsonResponse = $this->responseUtil->setResponse(400, 'error', 'Usuario no proporcionado', []);
                    return $this->response->setStatusCode(400)->setJSON($jsonResponse);
                }

                $resultado = $this->modelUsuario->findUser($usuario);
                if (!$resultado) {
                    $jsonResponse = $this->responseUtil->setResponse(200, 'success', 'Usuario disponible', true);
                    return $this->response->setStatusCode(200)->setJSON($jsonResponse);
                }

                $jsonResponse = $this->responseUtil->setResponse(200, 'success', 'Este usuario ya está registrado', false);
                return $this->response->setStatusCode(200)->setJSON($jsonResponse);

            } catch (\CodeIgniter\Database\Exceptions\DatabaseException $dbException) {
                $jsonResponse = $this->responseUtil->setResponse(500, 'server_error', 'Error en la base de datos', []);
                $this->responseUtil->logWithContext($this->responseUtil->setResponse(500, 'server_error', 'Database error: ' . $dbException->getMessage(), []));
                return $this->response->setStatusCode(500)->setJSON($jsonResponse);

            } catch (\Exception $e) {
                $jsonResponse = $this->responseUtil->setResponse(500, 'server_error', 'Error inesperado', []);
                $this->responseUtil->logWithContext($this->responseUtil->setResponse(500, 'server_error', 'Exception: ' . $e->getMessage(), []));
                return $this->response->setStatusCode(500)->setJSON($jsonResponse);
            }

        }

        return redirect()->back();
    }

    // ****************************************************************************************************************************
    // *!*   GUARDAR UN NUEVO USUARIO (AJAX):
    // ****************************************************************************************************************************
    public function create()
    {
        if ($this->request->isAJAX()) {

            try {

                $data = $this->request->getPost();
                if (!$this->validate($this->modelUsuario->validatorUser)) {
                    $errors       = $this->validator->getErrors();
                    $firstError   = reset($errors);
                    $jsonResponse = $this->responseUtil->setResponse(400, 'error', $errors, []);
                    return $this->response->setStatusCode(400)->setJSON($jsonResponse);
                }

                // date_default_timezone_set('America/El_Salvador');
                $date1 = new \DateTime($this->request->getPost('f_nacimiento_mayor'));
                $date2 = new \DateTime();
                $edad  = $date1->diff($date2);

                $datosPersona = [
                    'id_persona'     => $this->modelUsuario->maxPersona(),
                    'nombres'        => $this->request->getPost('nombres'),
                    'apellidos'      => $this->request->getPost('apellidos'),
                    'dui'            => $this->request->getPost('dui'),
                    'edad'           => $edad->y,
                    'telefono'       => $this->request->getPost('telefono'),
                    'fecha_creacion' => date('Y-m-d H:i:s')
                ];
                $persona = $this->modelUsuario->insertPersona($datosPersona);

                $datosUsuario = [
                    'id_usuario'     => $this->modelUsuario->maxUsuario(),
                    'id_persona'     => $datosPersona['id_persona'],
                    'id_rol'         => 2,
                    'usuario'        => $this->request->getPost('nombre_usuario'),
                    'email'          => $this->request->getPost('email'),
                    'password'       => sha1($this->request->getPost('password')),
                    'estado'         => true,
                    'fecha_creacion' => date('Y-m-d H:i:s')
                ];
                $usuario = $this->modelUsuario->insertUsuario($datosUsuario);

                if ($persona && $usuario) {
                    $jsonResponse = $this->responseUtil->setResponse(201, 'success', 'Usuario guardado exitosamente!', true);
                    return $this->response->setStatusCode(201)->setJSON($jsonResponse);
                }

                $jsonResponse = $this->responseUtil->setResponse(500, 'server_error', 'Error al guardar usuario', false);
                return $this->response->setStatusCode(500)->setJSON($jsonResponse);

            } catch (\CodeIgniter\Database\Exceptions\DatabaseException $dbException) {
                $jsonResponse = $this->responseUtil->setResponse(500, 'server_error', 'Error en la base de datos', []);
                $this->responseUtil->logWithContext($this->responseUtil->setResponse(500, 'server_error', 'Database error: ' . $dbException->getMessage(), []));
                return $this->response->setStatusCode(500)->setJSON($jsonResponse);

            } catch (\Exception $e) {
                $jsonResponse = $this->responseUtil->setResponse(500, 'server_error', 'Error inesperado', []);
                $this->responseUtil->logWithContext($this->responseUtil->setResponse(500, 'server_error', 'Exception: ' . $e->getMessage(), []));
                return $this->response->setStatusCode(500)->setJSON($jsonResponse);
            }

        }

        return redirect()->back();
    }

    // ****************************************************************************************************************************
    // *!*   MOSTRAR TODOS LOS USUARIOS CON NIVEL BAJO (AJAX DATATABLE):
    // ****************************************************************************************************************************
    public function tblUsuarios()
    {
        if ($this->request->isAJAX()) {

            // Simular un retraso de 5 segundos
            // sleep(5);
            // Retrasar 3 segundos (3000000 microsegundos)
            // usleep(3000000);

            $request     = $this->request->getPost();
            $draw        = intval($request['draw']);
            $start       = intval($request['start']);
            $length      = intval($request['length']);
            $searchValue = $request['search']['value'] ?? '';

            // Obtener totales (con y sin filtro) en una sola llamada
            $totals        = $this->modelUsuario->getTotalUsuarios($searchValue);
            $totalRecords  = $totals['totalRecords'];  // Número total de usuarios sin filtro
            $totalFiltered = $totals['totalFiltered']; // Número de usuarios filtrados

            // Obtener usuarios paginados con búsqueda (si hay)
            $resultList = $this->modelUsuario->getUsuariosPaginados($start, $length, $searchValue);

            $data = [];
            $i = $start + 1;

            foreach ($resultList as $value) {

                // log_message('debug', 'Estado Usuario: ' . $value['estado']);
                $btnEstado = $value['estado'] == 'Activo' ?
                    '<a class="btn-table btn-active" title="Estado" style="font-size: x-large;" onclick="cambiarEstadoUsuario(' . $value['id_usuario'] . ');"><i class="fas fa-user-check"></i></a>' :
                    '<a class="btn-table btn-inactive" title="Estado" style="font-size: x-large;" onclick="cambiarEstadoUsuario(' . $value['id_usuario'] . ');"><i class="fas fa-user-times"></i></a>';

                $data[] = [
                    $i++, // El índice
                    $value['nombre_apellido'], // Nombres y Apellidos
                    $value['nombre_rol'], // Rol
                    $value['usuario'], // Usuario
                    $value['telefono'], // Teléfono
                    '<textarea class="txt-tbl" readonly>' . $value['email'] . '</textarea>', // Email
                    $value['fecha_creacion'], // Fecha de creación
                    $value['estado'], // Estado (Activo/Inactivo)
                    $btnEstado, // Botón de estado
                ];
            }

            // Devolver los datos con la estructura necesaria para DataTables
            return $this->response->setJSON([
                'draw'            => $draw,
                'recordsTotal'    => $totalRecords,
                'recordsFiltered' => $totalFiltered,
                'data'            => $data
            ]);
        }

        return redirect()->back();
    }

    // ****************************************************************************************************************************
    // *!*   CAMBIAR EL ESTADO DE UN USUARIO (AJAX DATATABLE):
    // ****************************************************************************************************************************
    public function cambiarEstado()
    {
        if ($this->request->isAJAX()) {

            try {
                $id_usuario = $this->request->getPost('id_usuario');
                if (!$id_usuario) {
                    $jsonResponse = $this->responseUtil->setResponse(400, 'error', 'ID de usuario no proporcionado', $id_usuario);
                    return $this->response->setStatusCode(400)->setJSON($jsonResponse);
                }

                $estado = $this->modelUsuario->getEstadoModel($id_usuario);
                if (!$estado) {
                    $jsonResponse = $this->responseUtil->setResponse(404, 'not_found', 'Usuario no encontrado', $id_usuario);
                    return $this->response->setStatusCode(404)->setJSON($jsonResponse);
                }

                // Cambiar el estado
                $nuevo_estado = !$estado['estado'];
                $editar       = $this->modelUsuario->cambiarEstadoModel($id_usuario, $nuevo_estado);

                // Devuelve la respuesta JSON
                if ($editar) {
                    $message      = $estado['estado'] == true ? 'Deshabilitado exitosamente!' : 'Habilitado exitosamente!';
                    $jsonResponse = $this->responseUtil->setResponse(201, 'success', $message, true);
                    return $this->response->setStatusCode(201)->setJSON($jsonResponse);
                }

                $jsonResponse = $this->responseUtil->setResponse(500, 'server_error', 'Error al cambiar el estado', false);
                return $this->response->setStatusCode(500)->setJSON($jsonResponse);

            } catch (\CodeIgniter\Database\Exceptions\DatabaseException $dbException) {
                $jsonResponse = $this->responseUtil->setResponse(500, 'server_error', 'Error en la base de datos', []);
                $this->responseUtil->logWithContext($this->responseUtil->setResponse(500, 'server_error', 'Database error: ' . $dbException->getMessage(), []));
                return $this->response->setStatusCode(500)->setJSON($jsonResponse);

            } catch (\Exception $e) {
                $jsonResponse = $this->responseUtil->setResponse(500, 'server_error', 'Error inesperado', []);
                $this->responseUtil->logWithContext($this->responseUtil->setResponse(500, 'server_error', 'Exception: ' . $e->getMessage(), []));
                return $this->response->setStatusCode(500)->setJSON($jsonResponse);
            }
        }

        return redirect()->back();
    }

}
