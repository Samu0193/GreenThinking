<?php

namespace App\Controllers;

use App\Models\GaleriaModel;
use App\Controllers\BaseController;

class GaleriaController extends BaseController
{
    protected $galeriaModel;

    public function __construct()
    {
        $this->galeriaModel = new GaleriaModel();
        helper(['url', 'form']); // Carga helpers necesarios
    }

    public function index()
    {
        $session = session();
        if ($session->get('is_logged')) {

            $sessionData = $session->get();
            $data = [
                'title' => ' - Galeria',
                'session_data' => $sessionData
            ];
            return view('Layout/Header', $data) .
                view('Layout/Navegacion') .
                view('Galeria/Galerias') .
                view('Layout/Footer');
        }

        // Redirigir con mensaje flash directamente y guardarlo
        return redirect()->to(site_url('login'))->with('message', 'Usted no se ha identificado.');
    }

    // IMPRIMIR GALERIA
    public function printImgGalery()
    {
        $galeria = $this->galeriaModel->printImgGaleryModel();
        return $this->response->setJSON(json_encode($galeria));
    }

    public function tblGaleria()
    {
        $resultList = $this->galeriaModel->tblGaleriaModel();
        $result = ['data' => []];
        $i = 1;

        foreach ($resultList as $value) {
            $update = '<a class="btn-table btn-active" title="Cambiar imagen" style="font-size: x-large;" 
                        onclick="cargarImg(' . $value['id_galeria'] . ');">
                        <i class="fas fa-sync-alt"></i> 
                        <i class="fas fa-images"></i>
                    </a>';

            $result['data'][] = [
                $i++,
                '<img src="' . base_url($value['ruta_archivo']) . '" class="img-tbl">',
                $value['usuario'],
                $value['fecha_creacion'],
                $update
            ];
        }

        return $this->response->setJSON($result);
    }

    // OBTENER Imagen
    public function cargarImg()
    {
        $id_galeria = $this->request->getPost('id_galeria');

        if (!$id_galeria) {
            return $this->response->setJSON(['error' => 'ID de galería no proporcionado']);
        }

        $resultData = $this->galeriaModel->cargarImgModel(['id_galeria' => $id_galeria]);

        if ($resultData) {
            return $this->response->setJSON($resultData);
        } else {
            return $this->response->setJSON(['error' => 'No se encontró la imagen']);
        }
    }


    // CAMBIAR
    public function cambiarImg()
    {
        $id_galeria = $this->request->getPost('id_galeria');
        $imagen_original = $this->request->getPost('imagen_original');
        $img_name = $this->request->getPost('nombre_imagen');
        $extension = pathinfo($img_name, PATHINFO_EXTENSION);

        // Elimina la imagen original
        if (file_exists("assets/img/galery/" . $imagen_original)) {
            unlink("assets/img/galery/" . $imagen_original);
        }

        $mi_archivo = 'upload'; // input file
        $config = [
            'upload_path'   => "assets/img/galery/",
            'file_name'     => "galeria$id_galeria.$extension",
            'allowed_types' => '*',
            'max_size'      => '50000', // kb
            'max_width'     => '2000',
            'max_height'    => '2000'
        ];

        $file = $this->request->getFile($mi_archivo);
        $file->move($config['upload_path'], $config['file_name']);

        $datos = [
            'ruta_archivo'  => "assets/img/galery/galeria$id_galeria.$extension",
            'usuario_crea'  => $this->session->get('id_usuario'),
            'fecha_creacion' => date('Y-m-d H:i:s')
        ];

        $editar = $this->galeriaModel->cambiarImgModel('galeria', $datos, ['id_galeria' => $id_galeria]);

        if ($editar) {
            return redirect()->to('/Galeria');
        }
    }
}
