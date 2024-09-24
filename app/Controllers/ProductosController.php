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
        // helper(['url', 'form']);
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
                'title' => ' - Productos',
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
    // *!*   MOSTRAR TODOS LOS PRODUCTOS EN LA VISTA DE INICIO PARA LAS TARJETAS:
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
        $resultList = $this->modelProd->tblProductosModel();
        $result = [];
        $i = 1;

        foreach ($resultList as $value) {
            $estado = ($value['estado'] > 0) ? 'Activo' : 'Inactivo';
            $btnEstado = ($value['estado'] > 0) ?
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
            $height = strlen($value['descripcion']);
            $result['data'][] = [
                $i++,
                '<img src="' . base_url($value['ruta_archivo']) . '" class="img-tbl">',
                $value['nombre'],
                '<textarea class="txt-tbl" style="height: ' . $height . 'px;" readonly>' . $value['descripcion'] . '</textarea>',
                '$' . $value['precio'],
                $value['fecha_creacion'],
                $estado,
                $btnEstado
            ];
        }

        return $this->response->setJSON($result);
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
    // *!*   GUARDAR UN NUEVO PRODUCTO:
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
                if ($resultado) {
                    $jsonResponse = $this->responseUtil->setResponse(200, 'success', 'Producto guardado exitosamente', []);
                    return $this->response->setStatusCode(200)->setJSON($jsonResponse);
                }

                // Elimina la imagen (en caso de fallo de registro)
                if (file_exists($ruta_new_img)) unlink($ruta_new_img);
                $jsonResponse = $this->responseUtil->setResponse(500, 'success', 'Error al guardar producto', $resultado);
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

        // $img_name = $this->request->getPost('nombre_imagen');
        // $extension = pathinfo($img_name, PATHINFO_EXTENSION);
        // $id_producto = $this->modelProd->maxProducto();

        // $file = $this->request->getFile('upload');
        // $fileName = "producto{$id_producto}.{$extension}";
        // $filePath = "assets/img/products/{$fileName}";

        // if ($file->isValid() && !$file->hasMoved()) {
        //     $file->move('assets/img/products/', $fileName);
        // } else {
        //     echo $file->getErrorString();
        //     return;
        // }

        // $producto = [
        //     'id_producto'    => $id_producto,
        //     'ruta_archivo'   => $filePath,
        //     'nombre'         => $this->request->getPost('nombre_producto'),
        //     'descripcion'    => $this->request->getPost('descripcion'),
        //     'precio'         => $this->request->getPost('precio'),
        //     'estado'         => true,
        //     'usuario_crea'   => session()->get('id_usuario'),
        //     'fecha_creacion' => date('Y-m-d H:i:s')
        // ];

        // if ($this->modelProd->insertProducto($producto)) {
        //     return redirect()->to(site_url('productos'));
        // }
    }

    // ****************************************************************************************************************************
    // *!*   CAMBIAR EL ESTADO DE UN PRODUCTO DESDE LA VISTA DE ADMINISTRADOR:
    // ****************************************************************************************************************************
    public function cambiarEstado($id_producto)
    {
        $estado_p = $this->modelProd->getEstadoModel($id_producto);
        $estado = ($estado_p['estado'] == 0) ? 1 : 0;
        $editar = $this->modelProd->cambiarEstadoModel(['estado' => $estado], $id_producto);
        return $this->response->setBody($editar ? "true" : "false");
    }

}
