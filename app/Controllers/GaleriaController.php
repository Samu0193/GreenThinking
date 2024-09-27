<?php

namespace App\Controllers;

use App\Models\GaleriaModel;
use App\Controllers\BaseController;

class GaleriaController extends BaseController
{
    protected $modelGaleria;

    public function __construct()
    {
        $this->modelGaleria = new GaleriaModel();
    }

    // ****************************************************************************************************************************
    // *!*   CARGAR LA VISTA DE GALERIA:
    // ****************************************************************************************************************************
    public function index()
    {
        $session = session();
        if ($session->get('is_logged')) {

            $sessionData = $session->get();
            $data = [
                'title'        => ' - Galeria',
                'session_data' => $sessionData
            ];
            return view('layout/header', $data) .
                   view('layout/navegacion') .
                   view('galeria/index') .
                   view('layout/footer');
        }

        // Redirigir con mensaje flash directamente y guardarlo
        return redirect()->to(site_url('login'))->with('message', 'Usted no se ha identificado');
    }

    // private function _guardarImagen($file)
    // {
    //     $nombreArchivo = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
    //     $hash = substr(md5(uniqid(rand(), true)), 0, 10);
    //     $extension = strtolower($file->getClientOriginalExtension());
    //     $nombreArchivo = str_replace(' ', '_', $nombreArchivo) . '_' . $hash . '.' . $extension;
    //     $file->storeAs('public/assets/img/galery', $nombreArchivo);

    //     return 'assets/img/galery/' . $nombreArchivo;
    // }

    // ****************************************************************************************************************************
    // *!*   GUARDAR IMAGEN EN EL PROYECTO:
    // ****************************************************************************************************************************
    private function guardarImagen($file, $id_file)
    {
        $nombreArchivo = pathinfo($file->getName(), PATHINFO_FILENAME);
        $extension     = strtolower($file->getExtension());
        $nombreArchivo = strtolower($id_file . '_' . str_replace(' ', '_', $nombreArchivo) . '.' . $extension);
        $config = [
            'upload_path'   => "assets/img/galery/",
            'file_name'     => $nombreArchivo,
            'allowed_types' => 'jpg|jpeg|png', // Solo permitir jpg, jpeg y png
            'max_size'      => '50000',        // kb
            'max_width'     => '2000',
            'max_height'    => '2000'
        ];

        try {

            // Mover el archivo a la carpeta de destino
            if ($file->isValid() && !$file->hasMoved()) {
                $file->move($config['upload_path'], $config['file_name']);
                log_message('debug', 'Archivo subido correctamente.');
                return 'assets/img/galery/' . $nombreArchivo;
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

        // // Mover el archivo a la carpeta de destino
        // if ($file->isValid() && !$file->hasMoved()) {
        //     $file->move($config['upload_path'], $config['file_name']);
        //     log_message('debug', 'Archivo subido.');
        //     return 'assets/img/galery/' . $nombreArchivo;
        // } else {
        //     // Manejar el error en caso de que el archivo no sea válido o ya se haya movido
        //     log_message('debug', 'Error al subir el archivo.');
        //     throw new \RuntimeException('Error al subir el archivo.');
        //     return '';
        // }
    }

    public function _cambiarImg()
    {
        $id_galeria = $this->request->getPost('id_galeria');
        $nom_last_img = $this->request->getPost('nom_last_img');
        $img_name = $this->request->getPost('nombre_imagen');
        $extension = pathinfo($img_name, PATHINFO_EXTENSION);

        // Elimina la imagen original
        if (file_exists("assets/img/galery/$nom_last_img")) {
            unlink("assets/img/galery/$nom_last_img");
        }

        $mi_archivo = 'fileUpload'; // input file
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

        $editar = $this->modelGaleria->cambiarImgModel('galeria', $datos, ['id_galeria' => $id_galeria]);
        if ($editar) {
            return redirect()->to('/galeria');
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
    // *!*   MOSTRAR TODAS LAS IMAGENES DE LA GALERIA EN LA VISTA DE ADMINISTRADOR (AJAX DATATABLE):
    // ****************************************************************************************************************************
    public function tblGaleria()
    {
        if ($this->request->isAJAX()) {
            
            $request       = $this->request->getPost();
            $draw          = intval($request['draw']);
            $start         = intval($request['start']);
            $length        = intval($request['length']);
            $searchValue   = $request['search']['value'] ?? '';
            $totals        = $this->modelGaleria->getTotalGaleria($searchValue);
            $totalRecords  = $totals['totalRecords'];
            $totalFiltered = $totals['totalFiltered'];
            $resultList    = $this->modelGaleria->getGaleriaPaginada($start, $length, $searchValue);

            $data = [];
            $i = $start + 1;

            foreach ($resultList as $value) {
                $update =
                    '<a class="btn-table btn-active" title="Cambiar imagen" style="font-size: x-large;" 
                        onclick="cargarImg(' . $value['id_galeria'] . ');">
                        <i class="fas fa-sync-alt"></i> 
                        <i class="fas fa-images"></i>
                    </a>';

                $data[] = [
                    $i++,
                    '<img src="' . base_url($value['ruta_archivo']) . '" class="img-tbl">',
                    $value['usuario'],
                    $value['fecha_creacion'],
                    $update
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
    // *!*   OBTENER RUTA DE IMAGEN POR ID (AJAX):
    // ****************************************************************************************************************************
    public function cargarImg()
    {
        if ($this->request->isAJAX()) {

            try {

                $id_galeria = $this->request->getPost('id_galeria');
                if (!$id_galeria) {
                    $jsonResponse = $this->responseUtil->setResponse(400, 'error', 'ID de la imagen no proporcionado', []);
                    return $this->response->setStatusCode(400)->setJSON($jsonResponse);
                }

                $resultado = $this->modelGaleria->cargarImgModel($id_galeria);
                if (!$resultado) {
                    $jsonResponse = $this->responseUtil->setResponse(500, 'server_error', 'No se encontró la imagen', []);
                    return $this->response->setStatusCode(500)->setJSON($jsonResponse);
                }
                
                $jsonResponse = $this->responseUtil->setResponse(200, 'success', 'Imagen encontrada', $resultado);
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
    // *!*   CAMBIAR IMAGEN DE LA GALERIA:
    // ****************************************************************************************************************************
    public function cambiarImg()
    {
        if ($this->request->isAJAX()) {

            try {

                $data = $this->request->getPost();
                if (!$this->validate($this->modelGaleria->validator)) {
                    $errors       = $this->validator->getErrors();
                    $firstError   = reset($errors);
                    $jsonResponse = $this->responseUtil->setResponse(400, 'error', $errors, []);
                    return $this->response->setStatusCode(400)->setJSON($jsonResponse);
                }

                $id_galeria   = $this->request->getPost('id_galeria');
                $nom_last_img = $this->request->getPost('nom_last_img');
                $ruta_new_img = $this->guardarImagen($this->request->getFile('fileUpload'), $id_galeria);
                if ($ruta_new_img == '') {
                    $jsonResponse = $this->responseUtil->setResponse(500, 'success', 'Error al subir la imagen', []);
                    return $this->response->setStatusCode(500)->setJSON($jsonResponse);
                }

                $datos = [
                    'ruta_archivo'  => $ruta_new_img,
                    'usuario_crea'  => $this->session->get('id_usuario'),
                    'fecha_creacion' => date('Y-m-d H:i:s')
                ];
                $resultado = $this->modelGaleria->cambiarImgModel($datos, $id_galeria);
                if (!$resultado) {
                    // Elimina la imagen nueva (en caso de fallo de actualizacion)
                    if (file_exists($ruta_new_img)) unlink($ruta_new_img);
                    $jsonResponse = $this->responseUtil->setResponse(500, 'server_error', 'Error al cambiar la imagen', []);
                    return $this->response->setStatusCode(500)->setJSON($jsonResponse);
                }

                // Elimina la imagen original
                if (file_exists("assets/img/galery/$nom_last_img")) unlink("assets/img/galery/$nom_last_img");
                $jsonResponse = $this->responseUtil->setResponse(200, 'success', 'Imagen cambiada exitosamente', []);
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
    
}
