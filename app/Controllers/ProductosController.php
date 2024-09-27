<?php

namespace App\Controllers;

use App\Models\ProductosModel;
use App\Controllers\BaseController;

class ProductosController extends BaseController
{
    protected $modelProd;

    public function __construct()
    {
        $this->modelProd = new ProductosModel();
    }

    // ****************************************************************************************************************************
    // *!*   CARGAR LA VISTA DE PRODUCTOS:
    // ****************************************************************************************************************************
    public function index()
    {
        $session = session();
        if ($session->get('is_logged')) {

            $sessionData = $session->get();
            $data = [
                'title'        => ' - Productos',
                'session_data' => $sessionData
            ];

            // Cargar vistas
            return  view('layout/header', $data) .
                    view('layout/navegacion') .
                    view('productos/index') .
                    view('layout/footer');
        }

        return redirect()->to(site_url('login'))->with('message', 'Usted no se ha identificado');
    }

    // ****************************************************************************************************************************
    // *!*   GUARDAR IMAGEN EN EL PROYECTO:
    // ****************************************************************************************************************************
    private function guardarImagen($file, $nombre, $id_producto)
    {
        $extension     = strtolower($file->getExtension());
        $nombreArchivo = strtolower($id_producto . '_' . str_replace(' ', '_', $nombre) . '.' . $extension);
        $config = [
            'upload_path'   => "assets/img/products/",
            'file_name'     => $nombreArchivo,
            'allowed_types' => 'jpg|jpeg|png|svg|tiff', // Solo permitir jpg, jpeg y png
            'max_size'      => '50000',        // kb
            'max_width'     => '2000',
            'max_height'    => '2000'
        ];

        try {

            // Mover el archivo a la carpeta de destino
            if ($file->isValid() && !$file->hasMoved()) {
                $file->move($config['upload_path'], $config['file_name']);
                log_message('debug', 'Archivo subido correctamente.');
                return 'assets/img/products/' . $nombreArchivo;
            } else {
                $mensaje = 'Error al subir la imagen' . $file->getErrorString();
                $this->responseUtil->logWithContext($this->responseUtil->setResponse(500, 'server_error', $mensaje, []));
                return '';
            }

        } catch (\Exception $e) {
            $mensaje = 'Error al subir la imagen: ' . $e->getMessage();
            $this->responseUtil->logWithContext($this->responseUtil->setResponse(500, 'server_error', $mensaje, []));
            return '';
        }

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
    // *!*   MOSTRAR TODOS LOS PRODUCTOS EN LA VISTA DE INICIO PARA LAS TARJETAS (AJAX):
    // ****************************************************************************************************************************
    public function verProductos()
    {
        if ($this->request->isAJAX()) {
            $datos = $this->modelProd->verProductosModel();
            return $this->response->setJSON($datos);
        }

        return redirect()->back();
    }

    // ****************************************************************************************************************************
    // *!*   MOSTRAR TODOS LOS PRODUCTOS EN LA VISTA DE ADMINISTRADOR (AJAX DATATABLE):
    // ****************************************************************************************************************************
    public function tblProductos()
    {
        if ($this->request->isAJAX()) {
            
            $request       = $this->request->getPost();
            $draw          = intval($request['draw']);
            $start         = intval($request['start']);
            $length        = intval($request['length']);
            $searchValue   = $request['search']['value'] ?? '';
            $totals        = $this->modelProd->getTotalProductos($searchValue);
            $totalRecords  = $totals['totalRecords'];
            $totalFiltered = $totals['totalFiltered'];
            $resultList    = $this->modelProd->getProductosPaginados($start, $length, $searchValue);

            $data = [];
            $i = $start + 1;

            foreach ($resultList as $value) {

                $height    = strlen($value['descripcion']);
                $btnEstado = ($value['estado'] == 'Activo') ?
                    '<a class="btn-table btn-active" title="Estado" style="font-size: x-large;" 
                        onclick="cambiarEstadoProducto(' . $value['id_producto'] . ');">
                        <i class="far fa-check-circle"></i>
                        <i class="fas fa-shopping-cart"></i>
                    </a>' :
                    '<a class="btn-table btn-inactive" title="Estado" style="font-size: x-large;" 
                        onclick="cambiarEstadoProducto(' . $value['id_producto'] . ');">
                        <i class="far fa-times-circle"></i>
                        <i class="fas fa-shopping-cart"></i>
                    </a>';

                $data[] = [
                    $i++,
                    '<img src="' . base_url($value['ruta_archivo']) . '" class="img-tbl">',
                    $value['nombre'],
                    '<textarea class="txt-tbl" style="height: ' . $height . 'px;" readonly>' . $value['descripcion'] . '</textarea>',
                    '$' . $value['precio'],
                    $value['usuario'],
                    $value['fecha_creacion'],
                    $value['estado'],
                    $btnEstado
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
    // *!*   GUARDAR UN NUEVO PRODUCTO (AJAX):
    // ****************************************************************************************************************************
    public function guardar()
    {
        if ($this->request->isAJAX()) {

            try {

                $data = $this->request->getPost();
                if (!$this->validate($this->modelProd->validator)) {
                    $errors       = $this->validator->getErrors();
                    $firstError   = reset($errors);
                    $jsonResponse = $this->responseUtil->setResponse(400, 'error', $errors, []);
                    return $this->response->setStatusCode(400)->setJSON($jsonResponse);
                }

                $id_producto  = $this->modelProd->maxProducto();
                $ruta_new_img = $this->guardarImagen($this->request->getFile('fileUpload'), $this->request->getPost('nombre_producto'), $id_producto);
                if ($ruta_new_img == '') {
                    $jsonResponse = $this->responseUtil->setResponse(500, 'success', 'Error al subir la imagen, no se guardó la información', []);
                    return $this->response->setStatusCode(500)->setJSON($jsonResponse);
                }

                $producto = [
                    'id_producto'    => $id_producto,
                    'ruta_archivo'   => $ruta_new_img,
                    'nombre'         => $this->request->getPost('nombre_producto'),
                    'descripcion'    => $this->request->getPost('descripcion'),
                    'precio'         => (double)$this->request->getPost('precio'),
                    'estado'         => true,
                    'usuario_crea'   => session()->get('id_usuario'),
                    'fecha_creacion' => date('Y-m-d H:i:s')
                ];

                $resultado = $this->modelProd->insertProducto($producto);
                if (!$resultado) {
                    // Elimina la imagen (en caso de fallo de registro)
                    if (file_exists($ruta_new_img)) unlink($ruta_new_img);
                    $jsonResponse = $this->responseUtil->setResponse(500, 'success', 'Error al guardar producto', $resultado);
                    return $this->response->setStatusCode(500)->setJSON($jsonResponse);
                }
                
                $jsonResponse = $this->responseUtil->setResponse(200, 'success', 'Producto guardado exitosamente', []);
                return $this->response->setStatusCode(200)->setJSON($jsonResponse);

            } catch (\CodeIgniter\Database\Exceptions\DatabaseException $dbException) {
                $mensaje      = 'Database error: ' . $dbException->getMessage();
                $jsonResponse = $this->responseUtil->setResponse(500, 'server_error', 'Error en la base de datos', []);
                $this->responseUtil->logWithContext($this->responseUtil->setResponse(500, 'server_error', $mensaje, []));
                return $this->response->setStatusCode(500)->setJSON($jsonResponse);
    
            } catch (\Exception $e) {
                $mensaje      = 'Exception: ' . $e->getMessage();
                $jsonResponse = $this->responseUtil->setResponse(500, 'server_error', 'Error al generar el PDF', []);
                $this->responseUtil->logWithContext($this->responseUtil->setResponse(500, 'server_error', $mensaje, []));
                return $this->response->setStatusCode(500)->setJSON($jsonResponse);
            }

        }

        return redirect()->back();
    }

    // ****************************************************************************************************************************
    // *!*   CAMBIAR EL ESTADO DE UN PRODUCTO DESDE LA VISTA DE ADMINISTRADOR (AJAX):
    // ****************************************************************************************************************************
    public function cambiarEstado()
    {
        if ($this->request->isAJAX()) {

            try {

                $id_producto = $this->request->getPost('id_producto');
                if (!$id_producto) {
                    $jsonResponse = $this->responseUtil->setResponse(400, 'error', 'ID de producto no proporcionado', $id_producto);
                    return $this->response->setStatusCode(400)->setJSON($jsonResponse);
                }

                $estado = $this->modelProd->getEstadoModel($id_producto);
                if (!$estado) {
                    $jsonResponse = $this->responseUtil->setResponse(404, 'not_found', 'Producto no encontrado', $id_producto);
                    return $this->response->setStatusCode(404)->setJSON($jsonResponse);
                }

                $nuevo_estado = !$estado['estado'];
                $editar       = $this->modelProd->cambiarEstadoModel($id_producto, $nuevo_estado);
                if (!$editar) {
                    $jsonResponse = $this->responseUtil->setResponse(500, 'server_error', 'Error al cambiar el estado', false);
                    return $this->response->setStatusCode(500)->setJSON($jsonResponse);
                }
                
                $message      = $estado['estado'] == true ? 'Deshabilitado exitosamente!' : 'Habilitado exitosamente!';
                $jsonResponse = $this->responseUtil->setResponse(201, 'success', $message, true);
                return $this->response->setStatusCode(201)->setJSON($jsonResponse);

            } catch (\CodeIgniter\Database\Exceptions\DatabaseException $dbException) {
                $mensaje      = 'Database error: ' . $dbException->getMessage();
                $jsonResponse = $this->responseUtil->setResponse(500, 'server_error', 'Error en la base de datos', []);
                $this->responseUtil->logWithContext($this->responseUtil->setResponse(500, 'server_error', $mensaje, []));
                return $this->response->setStatusCode(500)->setJSON($jsonResponse);
    
            } catch (\Exception $e) {
                $mensaje      = 'Exception: ' . $e->getMessage();
                $jsonResponse = $this->responseUtil->setResponse(500, 'server_error', 'Error al generar el PDF', []);
                $this->responseUtil->logWithContext($this->responseUtil->setResponse(500, 'server_error', $mensaje, []));
                return $this->response->setStatusCode(500)->setJSON($jsonResponse);
            }

        }

        return redirect()->back();
    }

}
