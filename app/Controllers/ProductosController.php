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
        helper(['url', 'form']);
    }

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
            return  view('Layout/Header', $data) .
                view('Layout/Navegacion') .
                view('Productos/Productos') .
                view('Layout/Footer');
        }

        return redirect()->to(site_url('login'))->with('message', 'Usted no se ha identificado.');
    }

    // *************************************************************************************************************************
    //    GUARDAR UN NUEVO PRODUCTO:
    public function guardar()
    {
        $img_name = $this->request->getPost('nombre_imagen');
        $extension = pathinfo($img_name, PATHINFO_EXTENSION);
        $id_producto = $this->modelProd->maxProducto();

        $file = $this->request->getFile('upload');
        $fileName = "producto{$id_producto}.{$extension}";
        $filePath = "assets/img/products/{$fileName}";

        if ($file->isValid() && !$file->hasMoved()) {
            $file->move('assets/img/products/', $fileName);
        } else {
            echo $file->getErrorString();
            return;
        }

        $data = [
            'id_producto' => $id_producto,
            'ruta_archivo' => $filePath,
            'nombre' => $this->request->getPost('nombre_producto'),
            'descripcion' => $this->request->getPost('descripcion'),
            'precio' => $this->request->getPost('precio'),
            'estado' => true,
            'usuario_crea' => session()->get('id_usuario'),
            'fecha_creacion' => date('Y-m-d H:i:s')
        ];

        if ($this->modelProd->insertProducto($data)) {
            return redirect()->to(site_url('productos'));
        }
    }

    // *************************************************************************************************************************
    //    MOSTRAR TODOS LOS PRODUCTOS EN LA VISTA DE INICIO PARA LAS TARJETAS:
    public function verProductos()
    {
        if ($this->request->isAJAX()) {
            $datos = $this->modelProd->verProductosModel();
            return $this->response->setJSON($datos);
        }

        return redirect()->back();
    }

    // *************************************************************************************************************************
    //    MOSTRAR TODOS LOS PRODUCTOS EN LA VISTA DE ADMINISTRADOR:
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

    // *************************************************************************************************************************
    //    CAMBIAR EL ESTADO DE UN PRODUCTO DESDE LA VISTA DE ADMINISTRADOR:
    public function cambiarEstado($id_producto)
    {
        $estado_p = $this->modelProd->getEstadoModel($id_producto);
        $estado = ($estado_p['estado'] == 0) ? 1 : 0;

        $editar = $this->modelProd->cambiarEstadoModel(['estado' => $estado], $id_producto);
        return $this->response->setBody($editar ? "true" : "false");
    }
}
