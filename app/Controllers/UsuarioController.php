<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsuarioModel;
use App\Utils\ResponseUtil;

class UsuarioController extends BaseController
{
    protected $usuarioModel;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
    }

    public function index()
    {
        $session = session();
        if ($session->get('is_logged') && $session->get('id_rol') == 1) {

            $sessionData = $session->get();
            $data = [
                'title' => ' - Usuarios',
                'session_data' => $sessionData
            ];

            return view('Layout/Header', $data) .
                view('Layout/Navegacion') .
                view('Usuario/Usuarios') .
                view('Layout/Footer');
        }

        // Redirigir con mensaje flash directamente y guardarlo
        return redirect()->to(site_url('login'))->with('message', 'Usted no se ha identificado.');
    }

    /**************************************************************************************************************************************************
        METODOS PARA AJAX:
     **************************************************************************************************************************************************/

    public function validarEmail()
    {
        if ($this->request->isAJAX()) {
            $valor = $this->request->getPost('email');
            $resultado = $this->usuarioModel->findEmail($valor);
            return $this->response->setJSON(['result' => $resultado ? 1 : 0]);
        }

        return redirect()->back();
    }

    public function validarUser()
    {
        if ($this->request->isAJAX()) {
            $valor = $this->request->getPost('nombre_usuario');
            $resultado = $this->usuarioModel->findUser($valor);
            return $this->response->setJSON(['result' => $resultado ? 1 : 0]);
        }

        return redirect()->back();
    }

    public function setRoles()
    {
        if ($this->request->isAJAX()) {
            $datos = $this->usuarioModel->getRolesModel();
            return $this->response->setJSON($datos);
        }

        return redirect()->back();
    }

    public function ingresarUsuario()
    {
        return view('Usuario/IngresarUsuario');
    }

    // *************************************************************************************************************************
    //    GUARDAR UN NUEVO USUARIO:
    public function guardar()
    {
        try {
            // Obtener datos del POST
            $data = $this->request->getPost();
            log_message('debug', json_encode($data));

            // Validar usando las reglas definidas en el modelo
            if (!$this->validate($this->usuarioModel->validator)) {
                // Obtiene todos los errores
                $errors = $this->validator->getErrors();
                
                // Obtener el primer mensaje de error
                $firstError = reset($errors); // reset() devuelve el primer valor del array
                $jsonResponse = ResponseUtil::setResponse(400, "error", $errors, []);
                return $this->response->setStatusCode(400)->setJSON($jsonResponse);
            }

            date_default_timezone_set("America/El_Salvador");
            $date1 = new \DateTime($this->request->getPost('f_nacimiento_mayor'));
            $date2 = new \DateTime();
            $edad = $date1->diff($date2);

            $datosPersona = [
                'id_persona' => $this->usuarioModel->maxPersona(),
                'nombres' => $this->request->getPost('nombres'),
                'apellidos' => $this->request->getPost('apellidos'),
                'DUI' => $this->request->getPost('DUI'),
                'edad' => $edad->y,
                'telefono' => $this->request->getPost('telefono'),
                'fecha_creacion' => date('Y-m-d H:i:s')
            ];

            $datosUsuario = [
                'id_usuario' => $this->usuarioModel->maxUsuario(),
                'id_persona' => $datosPersona['id_persona'],
                'id_rol' => 2,
                'usuario' => $this->request->getPost('nombre_usuario'),
                'email' => $this->request->getPost('email'),
                'password' => sha1($this->request->getPost('password')),
                'estado' => true,
                'fecha_creacion' => date('Y-m-d H:i:s')
            ];

            $persona = $this->usuarioModel->insertPersona($datosPersona);
            $usuario = $this->usuarioModel->insertUsuario($datosUsuario);
            return $this->response->setBody($persona && $usuario ? 'true' : 'false');

        } catch (\Exception $e) {
            $jsonResponse = ResponseUtil::setResponse(500, "server_error", 'Error inesperado.', []);
            ResponseUtil::logWithContext(ResponseUtil::setResponse(500, "server_error", 'Exception: ' . $e->getMessage(), []));
            return $this->response->setStatusCode(500)->setJSON($jsonResponse);
        }
    }

    // *************************************************************************************************************************
    //    MOSTRAR TODOS LOS USUARIOS CON NIVEL BAJO:
    public function tblUsuarios()
    {

        // Simular un retraso de 5 segundos
        // sleep(5);
        // Retrasar 3 segundos (3000000 microsegundos)
        // usleep(3000000);

        $request = $this->request->getPost();
        $draw = intval($request['draw']);
        $start = intval($request['start']);
        $length = intval($request['length']);
        $searchValue = $request['search']['value'] ?? '';

        // Obtener totales (con y sin filtro) en una sola llamada
        $totals = $this->usuarioModel->getTotalUsuarios($searchValue);
        $totalRecords = $totals['totalRecords'];  // Número total de usuarios sin filtro
        $totalFiltered = $totals['totalFiltered']; // Número de usuarios filtrados

        // Obtener usuarios paginados con búsqueda (si hay)
        $resultList = $this->usuarioModel->getUsuariosPaginados($start, $length, $searchValue);

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
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalFiltered,
            'data' => $data
        ]);
    }

    // *************************************************************************************************************************
    //    CAMBIAR EL ESTADO DE UN USUARIO:
    public function cambiarEstado()
    {
        try {
            $id_usuario = $this->request->getPost('id_usuario');
            if (!$id_usuario) {
                $jsonResponse = ResponseUtil::setResponse(400, "error", 'ID de usuario no proporcionado.', $id_usuario);
                return $this->response->setStatusCode(400)->setJSON($jsonResponse);
            }

            $estado = $this->usuarioModel->getEstadoModel($id_usuario);
            if (!$estado) {
                $jsonResponse = ResponseUtil::setResponse(404, "not_found", 'Usuario no encontrado.', $id_usuario);
                return $this->response->setStatusCode(404)->setJSON($jsonResponse);
            }

            // Cambia el estado
            $nuevo_estado = !$estado['estado'];
            $editar = $this->usuarioModel->cambiarEstadoModel($id_usuario, $nuevo_estado);

            // Devuelve la respuesta JSON
            if ($editar) {
                $message = $estado['estado'] == true ? 'Deshabilitado exitosamente!' : 'Habilitado exitosamente!';
                $jsonResponse = ResponseUtil::setResponse(201, "success", $message, $editar);
                return $this->response->setStatusCode(201)->setJSON($jsonResponse);
            }

            $jsonResponse = ResponseUtil::setResponse(500, "server_error", 'Error al cambiar el estado.', $editar);
            return $this->response->setStatusCode(500)->setJSON($jsonResponse);

        } catch (\Exception $e) {
            $jsonResponse = ResponseUtil::setResponse(500, "server_error", 'Error inesperado.', []);
            ResponseUtil::logWithContext(ResponseUtil::setResponse(500, "server_error", 'Exception: ' . $e->getMessage(), []));
            return $this->response->setStatusCode(500)->setJSON($jsonResponse);
        }
        
    }

}
