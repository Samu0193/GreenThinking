<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsuarioModel;

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
            date_default_timezone_set("America/El_Salvador");
            $date1 = new \DateTime($this->request->getPost('f_nacimiento_mayor'));
            $date2 = new \DateTime();
            $edad = $date1->diff($date2);

            $persona = [
                'id_persona' => $this->usuarioModel->maxPersona(),
                'nombres' => $this->request->getPost('nombres'),
                'apellidos' => $this->request->getPost('apellidos'),
                'DUI' => $this->request->getPost('DUI'),
                'edad' => $edad->y,
                'telefono' => $this->request->getPost('telefono'),
                'fecha_creacion' => date('Y-m-d H:i:s')
            ];
            $dato1 = $this->usuarioModel->insertPersona($persona);

            $usuario = [
                'id_usuario' => $this->usuarioModel->maxUsuario(),
                'id_persona' => $persona['id_persona'],
                'id_rol' => 2,
                'usuario' => $this->request->getPost('nombre_usuario'),
                'email' => $this->request->getPost('email'),
                'password' => sha1($this->request->getPost('password')),
                'estado' => true,
                'fecha_creacion' => date('Y-m-d H:i:s')
            ];
            $dato2 = $this->usuarioModel->insertUsuario($usuario);

            return $this->response->setBody($dato1 && $dato2 ? 'true' : 'false');

            // return $this->response->setJSON(
            //     [
            //         'persona' => $persona,
            //         'usuario' => $usuario
            //     ], 200);

        } catch (\Exception $e) {
            // Log the error message
            log_message('error', $e->getMessage());
            return $this->response->setJSON(['error' => 'Ocurrio un error: ' . $e->getMessage()], 500);
        }
    }

    // *************************************************************************************************************************
    //    MOSTRAR TODOS LOS USUARIOS:
    public function tblUsuarios()
    {
        $resultList = $this->usuarioModel->tblUsuariosModel();
        $result = ['data' => []];
        $i = 1;

        foreach ($resultList as $value) {
            $estado = $value['estado'] > 0 ? 'Activo' : 'Inactivo';
            $btnEstado = $value['estado'] > 0 ?
                '<a class="btn-table btn-active" title="Estado" style="font-size: x-large;" onclick="cambiarEstadoUsuario(' . $value['id_usuario'] . ');"><i class="fas fa-user-check"></i></a>' :
                '<a class="btn-table btn-inactive" title="Estado" style="font-size: x-large;" onclick="cambiarEstadoUsuario(' . $value['id_usuario'] . ');"><i class="fas fa-user-times"></i></a>';

            $result['data'][] = [
                $i++,
                $value['nombres'] . ' ' . $value['apellidos'],
                $value['rol'],
                $value['usuario'],
                $value['telefono'],
                '<textarea class="txt-tbl" readonly>' . $value['email'] . '</textarea>',
                $value['fecha_creacion'],
                $estado,
                $btnEstado
            ];
        }
        return $this->response->setJSON($result);
    }

    public function tblUsuariosNuevo()
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
            $estado = $value['estado'] > 0 ? 'Activo' : 'Inactivo';
            $btnEstado = $value['estado'] > 0 ?
                '<a class="btn-table btn-active" title="Estado" style="font-size: x-large;" onclick="cambiarEstadoUsuario(' . $value['id_usuario'] . ');"><i class="fas fa-user-check"></i></a>' :
                '<a class="btn-table btn-inactive" title="Estado" style="font-size: x-large;" onclick="cambiarEstadoUsuario(' . $value['id_usuario'] . ');"><i class="fas fa-user-times"></i></a>';

            $data[] = [
                $i++, // El índice
                $value['nombres'] . ' ' . $value['apellidos'], // Nombres y Apellidos
                $value['rol'], // Rol
                $value['usuario'], // Usuario
                $value['telefono'], // Teléfono
                '<textarea class="txt-tbl" readonly>' . $value['email'] . '</textarea>', // Email
                $value['fecha_creacion'], // Fecha de creación
                $estado, // Estado (Activo/Inactivo)
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
    public function cambiarEstado($where)
    {
        $estado_p = $this->usuarioModel->getEstadoModel($where);
        $estado = ($estado_p[0]['estado'] == 0) ? 1 : 0;

        $editar = $this->usuarioModel->cambiarEstadoModel('usuario', ['estado' => $estado], ['id_usuario' => $where]);
        echo $editar ? "true" : "false";
    }
}
