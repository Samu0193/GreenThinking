<?php

namespace App\Controllers;

use Mpdf\Mpdf;
use App\Models\VoluntarioModel;
use App\Controllers\BaseController;

class InicioController extends BaseController
{
    protected $modelVol;

    public function __construct()
    {
        $this->modelVol = new VoluntarioModel();
    }

    // VISTA PRINCIPAL
    public function index()
    {
        return view('Inicio/index');
    }

    /*************************************************************************************************************************
        METODOS NORMALES:
     *************************************************************************************************************************/

    // *************************************************************************************************************************
    //    DESCARGAR PDF VOLUNTARIO MAYOR DE EDAD:
    public function pdfMayor()
    {
        try {
            // Instanciar mPDF
            $mpdf = new Mpdf();

            // Cargar el CSS
            $stylesheet = file_get_contents('assets/css/pdf.css');
            $mpdf->WriteHTML($stylesheet, 1);

            // Obtener datos
            $datos['b'] = $this->modelVol->getVoluntarioMayor($this->request->getPost('DUI'), $this->request->getPost('telefono'));

            // Preparar datos para la vista
            $solicitud = [
                'nombres'   => $this->request->getPost('nombres'),
                'apellidos' => $this->request->getPost('apellidos')
            ];

            // Generar el HTML
            $html = view('GenerarPDF/GenerarPdfAdulto', $datos);

            // Escribir el contenido HTML al PDF
            $mpdf->WriteHTML($html, 2);

            // Salida del archivo PDF directamente al navegador
            $mpdf->Output('Solicitud ' . $solicitud['nombres'] . ' ' . $solicitud['apellidos'] . '.pdf', 'D');

            // Terminar la ejecución
            exit;
        } catch (\Exception $e) {
            // Manejo de errores
            log_message('error', $e->getMessage());
            return $this->response->setStatusCode(500)->setBody('Error al generar el PDF: ' . $e->getMessage());
            // return $this->response->setJSON(['error' => 'Error al generar el PDF: ' . $e->getMessage()], 500);
        }
    }

    // *************************************************************************************************************************
    //    DESCARGAR PDF VOLUNTARIO MENOR DE EDAD:
    public function pdfMenor()
    {
        try {
            // Instanciar mPDF
            $mpdf = new Mpdf();

            // Cargar el CSS
            $stylesheet = file_get_contents('assets/css/pdf.css');
            $mpdf->WriteHTML($stylesheet, 1);

            // Obtener datos del formulario
            $dato = [
                'nombres'   => $this->request->getPost('nombres_menor'),
                'apellidos' => $this->request->getPost('apellidos_menor')
            ];

            // Obtener datos del modelo
            $datos['b'] = $this->modelVol->getVoluntarioMenor($this->request->getPost('DUI_ref'), $this->request->getPost('telefono_ref'));

            // Generar el HTML
            $html = view('GenerarPDF/GenerarPdfMenores', $datos);

            // Escribir el contenido HTML al PDF
            $mpdf->WriteHTML($html, 2);

            // Salida del archivo PDF directamente al navegador
            $mpdf->Output('Solicitud ' . $dato['nombres'] . ' ' . $dato['apellidos'] . '.pdf', 'D');

            // Terminar la ejecución
            exit;
        } catch (\Exception $e) {
            // Manejo de errores
            log_message('error', $e->getMessage());
            return $this->response->setStatusCode(500)->setBody('Error al generar el PDF: ' . $e->getMessage());
        }
    }


    /*************************************************************************************************************************
        METODOS PARA AJAX:
     *************************************************************************************************************************/


    // *************************************************************************************************************************
    //    LLENAR SELECT DEPARTAMENTO (AJAX):
    public function setDepartamentos()
    {
        if ($this->request->isAJAX()) {
            $datos = $this->modelVol->getDepartamentos();
            return $this->response->setJSON($datos);
        }

        return redirect()->back();
    }

    // *************************************************************************************************************************
    //    LLENAR SELECT MUNICIPIO (AJAX):
    public function setMunicipios()
    {
        if ($this->request->isAJAX()) {

            $id_departamento = $this->request->getPost('id_departamento');
            $municipio       = $this->modelVol->getMunicipios($id_departamento);

            if (count($municipio) > 0) {
                $options = "<option selected disabled value=''>Seleccionar...</option>";
                foreach ($municipio as $i) {
                    $options .= "<option value='" . $i['id_municipio'] . "'>" . $i['nombre_municipio'] . "</option>";
                }
                return $this->response->setBody($options);
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
    //             $options = "<option selected disabled value=''>Seleccionar...</option>";
    //             foreach ($municipio as $i) {
    //                 $options .= "<option value='".$i['id_municipio']."'>".$i['nombre_municipio']."</option>";
    //             }
    //             return $this->response->setBody($options);
    //         }
    //     }

    //     return redirect()->back();
    // }

    // *************************************************************************************************************************
    //    VALIDAR DUI (AJAX):
    public function validarDUI()
    {
        if ($this->request->isAJAX()) {
            $valor     = $this->request->getPost('DUI');
            $resultado = $this->modelVol->findDUI($valor);
            return $this->response->setJSON(['result' => $resultado ? 1 : 0]);
        }

        return redirect()->back();
    }

    // *************************************************************************************************************************
    //    VALIDAR TELEFONO (AJAX):
    public function validarTel()
    {
        if ($this->request->isAJAX()) {
            $valor     = $this->request->getPost('telefono');
            $resultado = $this->modelVol->findTel($valor);
            return $this->response->setJSON(['result' => $resultado ? 1 : 0]);
        }

        return redirect()->back();
    }

    // *************************************************************************************************************************
    //    VALIDAR CORREO (AJAX):
    public function validarEmail()
    {
        if ($this->request->isAJAX()) {
            $valor     = $this->request->getPost('email');
            $resultado = $this->modelVol->findEmail($valor);
            return $this->response->setJSON(['result' => $resultado ? 1 : 0]);
        }

        return redirect()->back();
    }

    // *************************************************************************************************************************
    //    GUARDAR VOLUNTARIO MAYOR DE EDAD (AJAX):
    public function guardar1()
    {
        if ($this->request->isAJAX()) {

            try {
                date_default_timezone_set("America/El_Salvador");
                $f_nacimiento = new \DateTime($this->request->getPost('f_nacimiento_mayor'));
                $f_hoy        = new \DateTime();
                $edad         = $f_nacimiento->diff($f_hoy)->y;

                // DATOS TABLA PERSONA
                $persona_voluntario = [
                    'id_persona'     => $this->modelVol->maxPersona(),
                    'nombres'        => $this->request->getPost('nombres'),
                    'apellidos'      => $this->request->getPost('apellidos'),
                    'edad'           => $edad,
                    'DUI'            => $this->request->getPost('DUI'),
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
                $solicitud  = $this->modelVol->insertSolicitud($datos_solicitud);

                return $this->response->setBody($persona && $voluntario && $solicitud ? 'true' : 'false');
            } catch (\Exception $e) {
                // Log the error message
                log_message('error', $e->getMessage());
                return $this->response->setJSON(['error' => $e->getMessage()], 500);
            }
        }

        return redirect()->back();
    }

    // *************************************************************************************************************************
    //    GUARDAR VOLUNTARIO MENOR DE EDAD (AJAX):
    public function guardar2()
    {
        if ($this->request->isAJAX()) {

            try {
                date_default_timezone_set("America/El_Salvador");
                $f_nacMenor      = new \DateTime($this->request->getPost('f_nacimiento_menor'));
                $f_nacRef        = new \DateTime($this->request->getPost('f_nacimiento_ref'));
                $f_hoy           = new \DateTime();
                $edad_menor      = $f_nacMenor->diff($f_hoy)->y;
                $edad_referencia = $f_nacRef->diff($f_hoy)->y;

                // DATOS TABLA PERSONA (VOLUNTARIO MENOR)
                $persona_voluntario = [
                    'id_persona'     => $this->modelVol->maxPersona(),
                    'nombres'        => $this->request->getPost('nombres_menor'),
                    'apellidos'      => $this->request->getPost('apellidos_menor'),
                    'edad'           => $edad_menor,
                    'DUI'            => null,
                    'telefono'       => $this->request->getPost('telefono_menor'),
                    'fecha_creacion' => date('Y-m-d H:i:s')
                ];
                $insert_per_vol = $this->modelVol->insertPersona($persona_voluntario);


                // DATOS TABLA PERSONA (REFERENCIA)
                $persona_referencia = [
                    'id_persona'     => $this->modelVol->maxPersona() + 1,
                    'nombres'        => $this->request->getPost('nombres_ref'),
                    'apellidos'      => $this->request->getPost('apellidos_ref'),
                    'edad'           => $edad_referencia,
                    'DUI'            => $this->request->getPost('DUI_ref'),
                    'telefono'       => $this->request->getPost('telefono_ref'),
                    'fecha_creacion' => date('Y-m-d H:i:s')
                ];
                $insert_per_ref = $this->modelVol->insertPersona($persona_referencia);


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


                // DATOS TABLA REFERENCIA
                $datos_per_referencia = [
                    'id_referencia'  => $this->modelVol->maxReferenciaPersonal(),
                    'id_persona'     => $persona_referencia['id_persona'],
                    'parentesco'     => $this->request->getPost('parentesco'),
                    'fecha_creacion' => date('Y-m-d H:i:s')
                ];
                $insert_referencia = $this->modelVol->insertReferencia($datos_per_referencia);


                // DATOS TABLA SOLICITUD
                $datos_solicitud = [
                    'id_voluntario'      => $datos_per_voluntario['id_voluntario'],
                    'id_referencia'      => $datos_per_referencia['id_referencia'],
                    'fecha_ingreso'      => date('Y-m-d'),
                    'fecha_finalizacion' => $this->request->getPost('fecha_finalizacion'),
                    'fecha_creacion'     => date('Y-m-d H:i:s')
                ];
                $insert_soli = $this->modelVol->insertSolicitud($datos_solicitud);


                // return $this->response->setBody($insert_per_vol && $insert_vol && $insert_soli ? 'true' : 'false');
                return $this->response->setBody($insert_per_vol && $insert_per_ref && $insert_vol && $insert_referencia && $insert_soli ? 'true' : 'false');

                // return $this->response->setJSON(
                //     [
                //         'persona_vol' => $persona_voluntario,
                //         'persona_ref' => $persona_referencia,
                //         'voluntario' => $datos_per_voluntario,
                //         'referencia' => $datos_per_referencia,
                //         'solicitud' => $datos_solicitud
                //     ], 200);

            } catch (\Exception $e) {
                // Log the error message
                log_message('error', $e->getMessage());
                return $this->response->setJSON(['error' => $e->getMessage()], 500);
            }
        }

        return redirect()->back();
    }
}
