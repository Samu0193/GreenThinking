<?php

namespace App\Controllers;

use App\Models\VoluntarioModel;
use App\Controllers\BaseController;

class InicioController extends BaseController
{
    protected $modelVol;

    public function __construct()
    {
        $this->modelVol = new VoluntarioModel();
    }

    // ****************************************************************************************************************************
    // *!*   CARGAR LA VISTA PRINCIPAL:
    // ****************************************************************************************************************************
    public function index()
    {
        // Usa FCPATH para obtener la ruta física completa al directorio de imágenes
        $galeriaDirectorio = FCPATH . 'assets/img/galery';
        $galeriaFiles = array_diff(scandir($galeriaDirectorio), array('.', '..'));

        natsort($galeriaFiles);
        $galeriaFiles = array_values($galeriaFiles); // Reindexar el array
        $galeriaFiles = array_combine(range(1, count($galeriaFiles)), $galeriaFiles); // Reindexar comenzando desde 1

        $galeriaFiles = array_map(function($file) {
            return base_url('assets/img/galery/' . $file);
        }, $galeriaFiles); // Añadir la ruta completa para cada archivo de galería

        $dataFiles = [
            'files' => [
                'galeria'  => $galeriaFiles
            ]
        ];

        // dd(date('Y-m-d H:i:s'));
        // dd($dataFiles); // Mostrar los archivos

        // Envía los archivos como un array asociativo a la vista
        return view('inicio/index', $dataFiles);
    }
    
    
    // public function pdfMayor()
    // {
    //     try {

    //         // Obtener datos
    //         $datos['volMayor'] = $this->modelVol->getVoluntarioMayor($this->request->getPost('dui'), $this->request->getPost('telefono'));

    //         // Generar el HTML
    //         $html = view('pdf/pdf_mayores', $datos);

    //         // Escribir el contenido HTML al PDF
    //         $this->mpdf->WriteHTML($html, 2);

    //         // Salida del archivo PDF directamente al navegador
    //         $this->mpdf->Output('Solicitud ' . $datos['volMayor']['nombre_completo'] . '.pdf', 'D');

    //         // Terminar la ejecución
    //         exit;

    //     } catch (\CodeIgniter\Database\Exceptions\DatabaseException $dbException) {
    //         $mensaje      = 'Database error: ' . $dbException->getMessage();
    //         $jsonResponse = $this->responseUtil->setResponse(500, 'server_error', 'Error en la base de datos', []);
    //         $this->responseUtil->logWithContext($this->responseUtil->setResponse(500, 'server_error', $mensaje, []));
    //         return $this->response->setStatusCode(500)->setJSON($jsonResponse);

    //     } catch (\Exception $e) {
    //         $mensaje      = 'Exception: ' . $e->getMessage();
    //         $jsonResponse = $this->responseUtil->setResponse(500, 'server_error', 'Error al generar el PDF', []);
    //         $this->responseUtil->logWithContext($this->responseUtil->setResponse(500, 'server_error', $mensaje, []));
    //         return $this->response->setStatusCode(500)->setJSON($jsonResponse);
    //     }
    // }

    
    // public function pdfMenor()
    // {
    //     try {

    //         // Obtener datos del modelo
    //         $datos['volMenor'] = $this->modelVol->getVoluntarioMenor($this->request->getPost('dui_ref'), $this->request->getPost('telefono_ref'));

    //         // Generar el HTML
    //         $html = view('pdf/pdf_menores', $datos);

    //         // Escribir el contenido HTML al PDF
    //         $this->mpdf->WriteHTML($html, 2);

    //         // Salida del archivo PDF directamente al navegador
    //         $this->mpdf->Output('Solicitud ' . $datos['volMenor']['nombre_completo'] . '.pdf', 'D');

    //         // Terminar la ejecución
    //         exit;

    //     } catch (\CodeIgniter\Database\Exceptions\DatabaseException $dbException) {
    //         $mensaje      = 'Database error: ' . $dbException->getMessage();
    //         $jsonResponse = $this->responseUtil->setResponse(500, 'server_error', 'Error en la base de datos', []);
    //         $this->responseUtil->logWithContext($this->responseUtil->setResponse(500, 'server_error', $mensaje, []));
    //         return $this->response->setStatusCode(500)->setJSON($jsonResponse);

    //     } catch (\Exception $e) {
    //         $mensaje      = 'Exception: ' . $e->getMessage();
    //         $jsonResponse = $this->responseUtil->setResponse(500, 'server_error', 'Error al generar el PDF', []);
    //         $this->responseUtil->logWithContext($this->responseUtil->setResponse(500, 'server_error', $mensaje, []));
    //         return $this->response->setStatusCode(500)->setJSON($jsonResponse);
    //     }
    // }


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
    // *!*   LLENAR SELECT DEPARTAMENTO (AJAX):
    // ****************************************************************************************************************************
    public function setDepartamentos()
    {
        if ($this->request->isAJAX()) {

            try {
                // // Si $departamentos puede ser null o false:
                // if (!$departamentos) {
                //     // Esto se ejecutará si $departamentos es null, false, 0, una cadena vacía o un array vacío.
                // }
                // // Si $departamentos siempre es un array o una colección y necesitas verificar que tenga elementos:
                // if (is_array($departamentos) && count($departamentos) > 0) {
                //     // Solo se ejecuta si $departamentos es un array y contiene elementos
                // }
                // // O, si estás usando un objeto tipo Collection
                // if ($departamentos && count($departamentos) > 0) {
                //     // Si $departamentos no es nulo y tiene elementos
                // }
                // // En resumen, puedes combinar las dos verificaciones si $departamentos puede no existir, ser null, o un array vacío:
                // if (!$departamentos || count($departamentos) === 0) {
                //     // Maneja el caso donde $departamentos es null, false, o está vacío
                // }

                $departamentos = $this->modelVol->getDepartamentos();
                if (!$departamentos) {
                    $jsonResponse = $this->responseUtil->setResponse(404, 'not_found', 'No hay registros en departamentos', []);
                    return $this->response->setStatusCode(404)->setJSON($jsonResponse);
                }

                $message = count($departamentos) . ' registros encontrados';
                $jsonResponse = $this->responseUtil->setResponse(200, 'success', $message, $departamentos);
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
    // *!*   LLENAR SELECT MUNICIPIO (AJAX):
    // ****************************************************************************************************************************
    public function setMunicipios()
    {
        if ($this->request->isAJAX()) {

            try {

                $id_departamento = $this->request->getPost('id_departamento');
                if (!$id_departamento) {
                    $jsonResponse = $this->responseUtil->setResponse(400, 'error', 'ID de departamento no proporcionado', []);
                    return $this->response->setStatusCode(400)->setJSON($jsonResponse);
                }

                $municipio = $this->modelVol->getMunicipios($id_departamento);
                if (!$municipio) {
                    $jsonResponse = $this->responseUtil->setResponse(404, 'not_found', "No hay registros de municipios en departamento $id_departamento.", []);
                    return $this->response->setStatusCode(404)->setJSON($jsonResponse);
                }

                $message      = count($municipio) . ' registros encontrados';
                $jsonResponse = $this->responseUtil->setResponse(200, 'success', $message, $municipio);
                return $this->response->setStatusCode(200)->setJSON($jsonResponse);

                // $options = '<option selected disabled value="">Seleccionar...</option>';
                // foreach ($municipio as $i) {
                //     $options .= '<option value="' . $i['id_municipio'] . '">"' . $i['nombre_municipio'] . '"</option>';
                // }
                // return $this->response->setBody($options);

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

    // // LLENAR SELECT DEPARTAMENTO PARA MENOR DE EDAD (AJAX)
    // public function obtDepartamentoMenor()
    // {
    //     if ($this->request->isAJAX()) {
    //         $datos = $this->modelVol->obtDepar();
    //         return $this->response->setJSON($datos);
    //     }

    //     return redirect()->back();
    // }

    // // LLENAR SELECT MUNICIPIO PARA MENOR DE EDAD (AJAX)
    // public function obtMunicipioMenor()
    // {
    //     if ($this->request->isAJAX()) {
    //         $id_departamento = $this->request->getPost('id_departamento');
    //         $municipio = $this->modelVol->obtMuni($id_departamento);
    //         if (count($municipio) > 0) {
    //             $options = '<option selected disabled value="">Seleccionar...</option>';
    //             foreach ($municipio as $i) {
    //                 $options .= '<option value="' . $i['id_municipio'] . '">"' . $i['nombre_municipio'] . '"</option>';
    //             }
    //             return $this->response->setBody($options);
    //         }
    //     }

    //     return redirect()->back();
    // }

    // ****************************************************************************************************************************
    // *!*   VALIDAR DUI (AJAX):
    // ****************************************************************************************************************************
    public function validarDUI()
    {
        if ($this->request->isAJAX()) {

            try {

                $dui = $this->request->getPost('dui');
                if (!$dui) {
                    $jsonResponse = $this->responseUtil->setResponse(400, 'error', 'DUI no proporcionado', []);
                    return $this->response->setStatusCode(400)->setJSON($jsonResponse);
                }

                $resultado = $this->modelVol->findDUI($dui);
                if ($resultado) {
                    $jsonResponse = $this->responseUtil->setResponse(200, 'error', 'Este DUI ya está registrado', false);
                    return $this->response->setStatusCode(200)->setJSON($jsonResponse);
                    // return $this->response->setJSON(['result' => $resultado ? 1 : 0]);
                }

                $jsonResponse = $this->responseUtil->setResponse(200, 'success', 'DUI disponible', true);
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
    // *!*   VALIDAR TELEFONO (AJAX):
    // ****************************************************************************************************************************
    public function validarTel()
    {
        if ($this->request->isAJAX()) {

            try {

                $telefono = $this->request->getPost('telefono');
                if (!$telefono) {
                    $jsonResponse = $this->responseUtil->setResponse(400, 'error', 'Teléfono no proporcionado', []);
                    return $this->response->setStatusCode(400)->setJSON($jsonResponse);
                }

                $resultado = $this->modelVol->findTel($telefono);
                if ($resultado) {
                    $jsonResponse = $this->responseUtil->setResponse(200, 'error', 'Este teléfono ya está registrado', false);
                    return $this->response->setStatusCode(200)->setJSON($jsonResponse);
                }
                
                $jsonResponse = $this->responseUtil->setResponse(200, 'success', 'Teléfono disponible', true);
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

                $resultado = $this->modelVol->findEmail($email);
                if ($resultado) {
                    $jsonResponse = $this->responseUtil->setResponse(200, 'error', 'Este email ya está registrado', false);
                    return $this->response->setStatusCode(200)->setJSON($jsonResponse);
                }
                
                $jsonResponse = $this->responseUtil->setResponse(200, 'success', 'Email disponible', true);
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
    // *!*   GUARDAR VOLUNTARIO MAYOR DE EDAD (AJAX):
    // ****************************************************************************************************************************
    public function createVolMayor()
    {
        if ($this->request->isAJAX()) {

            try {

                // Obtener datos del POST (PARA VALIDATOR)
                $data = $this->request->getPost();
                // log_message('debug', json_encode($data));
                // Validar usando las reglas definidas en el modelo
                if (!$this->validate($this->modelVol->validatorMayor)) {

                    // Obtiene todos los errores
                    $errors = $this->validator->getErrors();

                    // Obtener el primer mensaje de error
                    $firstError   = reset($errors); // reset() devuelve el primer valor del array
                    $jsonResponse = $this->responseUtil->setResponse(400, 'error', $errors, []);
                    return $this->response->setStatusCode(400)->setJSON($jsonResponse);
                }

                // date_default_timezone_set('America/El_Salvador');
                $f_nacimiento = new \DateTime($this->request->getPost('f_nacimiento_mayor'));
                $f_hoy        = new \DateTime();
                $edad         = $f_nacimiento->diff($f_hoy)->y;

                // DATOS TABLA PERSONA
                $persona_voluntario = [
                    'id_persona'     => $this->modelVol->maxPersona(),
                    'nombres'        => $this->request->getPost('nombres'),
                    'apellidos'      => $this->request->getPost('apellidos'),
                    'edad'           => $edad,
                    'dui'            => $this->request->getPost('dui'),
                    'telefono'       => $this->request->getPost('telefono'),
                    'fecha_creacion' => date('Y-m-d H:i:s')
                ];
                $persona = $this->modelVol->insertPersona($persona_voluntario);

                // DATOS TABLA VOLUNTARIO
                $datos_per_voluntario = [
                    'id_voluntario'           => $this->modelVol->maxVoluntario(),
                    'id_persona'              => $persona_voluntario['id_persona'],
                    'email'                   => $this->request->getPost('email'),
                    'departamento_residencia' => $this->request->getPost('departamento_residencia'),
                    'municipio_residencia'    => $this->request->getPost('municipio_residencia'),
                    'direccion'               => $this->request->getPost('direccion'),
                    'fecha_creacion'          => date('Y-m-d H:i:s')
                ];
                $voluntario = $this->modelVol->insertVoluntario($datos_per_voluntario);

                // DATOS TABLA SOLICITUD
                $datos_solicitud = [
                    'id_voluntario'      => $datos_per_voluntario['id_voluntario'],
                    'fecha_ingreso'      => date('Y-m-d'),
                    'fecha_finalizacion' => $this->request->getPost('fecha_finalizacion'),
                    'fecha_creacion'     => date('Y-m-d H:i:s')
                ];
                $solicitud = $this->modelVol->insertSolicitud($datos_solicitud);

                // Devuelve la respuesta JSON
                if (!$persona && !$voluntario && !$solicitud) {
                    $jsonResponse = $this->responseUtil->setResponse(500, 'server_error', 'Error al guardar la información', []);
                    return $this->response->setStatusCode(500)->setJSON($jsonResponse);
                    // return $this->response->setBody($persona && $voluntario && $solicitud ? 'true' : 'false');
                }

                $dataResponse = [
                    'id_voluntario' => $datos_per_voluntario['id_voluntario'],
                    'dui'           => $persona_voluntario['dui'],
                    'telefono'      => $persona_voluntario['telefono']
                ];
                $jsonResponse = $this->responseUtil->setResponse(201, 'success', 'Información guardada exitosamente!', $dataResponse);
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

    // ****************************************************************************************************************************
    // *!*   GUARDAR VOLUNTARIO MENOR DE EDAD (AJAX):
    // ****************************************************************************************************************************
    public function createVolMenor()
    {
        if ($this->request->isAJAX()) {

            try {

                $data = $this->request->getPost();
                // log_message('debug', json_encode($data));
                if (!$this->validate($this->modelVol->validatorMenor)) {
                    $errors       = $this->validator->getErrors();
                    $firstError   = reset($errors);
                    log_message('debug', json_encode($errors));
                    $jsonResponse = $this->responseUtil->setResponse(400, 'error', $errors, []);
                    return $this->response->setStatusCode(400)->setJSON($jsonResponse);
                }

                // date_default_timezone_set('America/El_Salvador');
                $f_nacMenor      = new \DateTime($this->request->getPost('f_nacimiento_menor'));
                $f_nacRef        = new \DateTime($this->request->getPost('f_nacimiento_ref'));
                $f_hoy           = new \DateTime();
                $edad_menor      = $f_nacMenor->diff($f_hoy)->y;
                $edad_referencia = $f_nacRef->diff($f_hoy)->y;

                // DATOS TABLA PERSONA (REFERENCIA)
                $persona_referencia = [
                    'id_persona'     => $this->modelVol->maxPersona(),
                    'nombres'        => $this->request->getPost('nombres_ref'),
                    'apellidos'      => $this->request->getPost('apellidos_ref'),
                    'edad'           => $edad_referencia,
                    'dui'            => $this->request->getPost('dui_ref'),
                    'telefono'       => $this->request->getPost('telefono_ref'),
                    'fecha_creacion' => date('Y-m-d H:i:s')
                ];
                $insert_per_ref = $this->modelVol->insertPersona($persona_referencia);

                // DATOS TABLA REFERENCIA
                $datos_per_referencia = [
                    'id_referencia'  => $this->modelVol->maxReferenciaPersonal(),
                    'id_persona'     => $persona_referencia['id_persona'],
                    'parentesco'     => $this->request->getPost('parentesco'),
                    'fecha_creacion' => date('Y-m-d H:i:s')
                ];
                $insert_referencia = $this->modelVol->insertReferencia($datos_per_referencia);

                // DATOS TABLA PERSONA (VOLUNTARIO MENOR)
                $persona_voluntario = [
                    'id_persona'     => $this->modelVol->maxPersona() + 1,
                    'nombres'        => $this->request->getPost('nombres_menor'),
                    'apellidos'      => $this->request->getPost('apellidos_menor'),
                    'edad'           => $edad_menor,
                    'dui'            => null,
                    'telefono'       => $this->request->getPost('telefono_menor'),
                    'fecha_creacion' => date('Y-m-d H:i:s')
                ];
                $insert_per_vol = $this->modelVol->insertPersona($persona_voluntario);

                // DATOS TABLA VOLUNTARIO
                $datos_per_voluntario = [
                    'id_voluntario'           => $this->modelVol->maxVoluntario(),
                    'id_persona'              => $persona_voluntario['id_persona'],
                    'email'                   => $this->request->getPost('email'),
                    'departamento_residencia' => $this->request->getPost('departamento_residencia'),
                    'municipio_residencia'    => $this->request->getPost('municipio_residencia'),
                    'direccion'               => $this->request->getPost('direccion'),
                    'fecha_creacion'          => date('Y-m-d H:i:s')
                ];
                $insert_vol = $this->modelVol->insertVoluntario($datos_per_voluntario);

                // DATOS TABLA SOLICITUD
                $datos_solicitud = [
                    'id_voluntario'      => $datos_per_voluntario['id_voluntario'],
                    'id_referencia'      => $datos_per_referencia['id_referencia'],
                    'fecha_ingreso'      => date('Y-m-d'),
                    'fecha_finalizacion' => $this->request->getPost('fecha_finalizacion'),
                    'fecha_creacion'     => date('Y-m-d H:i:s')
                ];
                $insert_soli = $this->modelVol->insertSolicitud($datos_solicitud);

                if (!$insert_per_ref && !$insert_referencia && !$insert_per_vol && !$insert_vol && !$insert_soli) {
                    $jsonResponse = $this->responseUtil->setResponse(500, 'server_error', 'Error al guardar la información', false);
                    return $this->response->setStatusCode(500)->setJSON($jsonResponse);
                }

                $dataResponse = [
                    'id_voluntario' => $datos_per_voluntario['id_voluntario'],
                    'dui'           => $persona_referencia['dui'],
                    'telefono'      => $persona_referencia['telefono']
                ];
                $jsonResponse = $this->responseUtil->setResponse(201, 'success', 'Información guardada exitosamente!', $dataResponse);
                return $this->response->setStatusCode(201)->setJSON($jsonResponse);

                // return $this->response->setBody($insert_per_vol && $insert_vol && $insert_soli ? 'true' : 'false');
                // return $this->response->setBody($insert_per_vol && $insert_per_ref && $insert_vol && $insert_referencia && $insert_soli ? 'true' : 'false');

                // return $this->response->setJSON(
                //     [
                //         'persona_vol' => $persona_voluntario,
                //         'persona_ref' => $persona_referencia,
                //         'voluntario' => $datos_per_voluntario,
                //         'referencia' => $datos_per_referencia,
                //         'solicitud' => $datos_solicitud
                //     ], 200);

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
