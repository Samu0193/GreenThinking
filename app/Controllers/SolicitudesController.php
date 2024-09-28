<?php

namespace App\Controllers;

use App\Models\VoluntarioModel;
use App\Controllers\BaseController;

class SolicitudesController extends BaseController
{
    protected $modelVol;

    public function __construct()
    {
        $this->modelVol = new VoluntarioModel();
    }

    // ****************************************************************************************************************************
    // *!*   CARGAR LA VISTA DE SOLICITUDES:
    // ****************************************************************************************************************************
    public function index()
    {
        $session = session();
        if ($session->get('is_logged')) {

            $sessionData = $session->get();
            $data = [
                'title'        => ' - Solicitudes',
                'session_data' => $sessionData
            ];
            return view('layout/header', $data) .
                   view('layout/navegacion') .
                   view('solicitud/index') .
                   view('layout/footer');
        }

        return redirect()->to(site_url('login'))->with('message', 'Usted no se ha identificado');
    }

    // ****************************************************************************************************************************
    // *!*   DESCARGAR PDF VOLUNTARIO MAYOR DE EDAD:
    // ****************************************************************************************************************************
    public function downloadSoliMayores($id_voluntario, $dui, $telefono)
    {
        try {

            if (!$id_voluntario) {
                $jsonResponse = $this->responseUtil->setResponse(400, 'error', 'ID del voluntario no proporcionado', []);
                return $this->response->setStatusCode(400)->setJSON($jsonResponse);
            }

            if (!$dui) {
                $jsonResponse = $this->responseUtil->setResponse(400, 'error', 'DUI del voluntario no proporcionado', []);
                return $this->response->setStatusCode(400)->setJSON($jsonResponse);
            }

            if (!$telefono) {
                $jsonResponse = $this->responseUtil->setResponse(400, 'error', 'Teléfono del voluntario no proporcionado', []);
                return $this->response->setStatusCode(400)->setJSON($jsonResponse);
            }

            $resultado = $this->modelVol->getVoluntarioMayor($id_voluntario, $dui, $telefono);
            if (!$resultado) {
                $jsonResponse = $this->responseUtil->setResponse(500, 'server_error', 'No se encontró la solicitud', []);
                return $this->response->setStatusCode(500)->setJSON($jsonResponse);
            }

            // Obtener datos
            $datos['volMayor'] = $resultado;

            // Generar el HTML
            $html = view('pdf/pdf_mayores', $datos);

            // Escribir el contenido HTML al PDF
            $this->mpdf->WriteHTML($html, 2);

            // Salida del archivo PDF directamente al navegador
            $this->mpdf->Output('Solicitud ' . $datos['volMayor']['nombre_completo'] . '.pdf', 'D');

            // Terminar la ejecución
            exit;

        } catch (\CodeIgniter\Database\Exceptions\DatabaseException $dbException) {
            $mensaje      = 'Database error: ' . $dbException->getMessage();
            $jsonResponse = $this->responseUtil->setResponse(500, 'server_error', 'Error en la base de datos', []);
            $this->responseUtil->logWithContext($this->responseUtil->setResponse(500, 'server_error', $mensaje, []));
            return $this->response->setStatusCode(500)->setJSON($jsonResponse);

        } catch (\Mpdf\MpdfException $e) {
            $mensaje      = 'Error en mPDF: ' . $e->getMessage();
            $jsonResponse = $this->responseUtil->setResponse(500, 'server_error', 'Error al generar el PDF', []);
            $this->responseUtil->logWithContext($this->responseUtil->setResponse(500, 'server_error', $mensaje, []));
            return $this->response->setStatusCode(500)->setJSON($jsonResponse);

        } catch (\Exception $e) {
            $mensaje      = 'Exception: ' . $e->getMessage();
            $jsonResponse = $this->responseUtil->setResponse(500, 'server_error', 'Error al generar el PDF', []);
            $this->responseUtil->logWithContext($this->responseUtil->setResponse(500, 'server_error', $mensaje, []));
            return $this->response->setStatusCode(500)->setJSON($jsonResponse);
        }
        
    }

    // ****************************************************************************************************************************
    // *!*   DESCARGAR PDF VOLUNTARIO MENOR DE EDAD:
    // ****************************************************************************************************************************
    public function downloadSoliMenores($id_voluntario, $dui, $telefono)
    {
        try {

            if (!$id_voluntario) {
                $jsonResponse = $this->responseUtil->setResponse(400, 'error', 'ID del voluntario no proporcionado', []);
                return $this->response->setStatusCode(400)->setJSON($jsonResponse);
            }

            if (!$dui) {
                $jsonResponse = $this->responseUtil->setResponse(400, 'error', 'DUI del responsable no proporcionado', []);
                return $this->response->setStatusCode(400)->setJSON($jsonResponse);
            }

            if (!$telefono) {
                $jsonResponse = $this->responseUtil->setResponse(400, 'error', 'Teléfono del responsable no proporcionado', []);
                return $this->response->setStatusCode(400)->setJSON($jsonResponse);
            }

            $resultado = $this->modelVol->getVoluntarioMenor($id_voluntario, $dui, $telefono);
            if (!$resultado) {
                $jsonResponse = $this->responseUtil->setResponse(500, 'server_error', 'No se encontró la solicitud', []);
                return $this->response->setStatusCode(500)->setJSON($jsonResponse);
            }

            // Obtener datos
            $datos['volMenor'] = $resultado;

            // Generar el HTML
            $html = view('pdf/pdf_menores', $datos);

            // Escribir el contenido HTML al PDF
            $this->mpdf->WriteHTML($html, 2);

            // Salida del archivo PDF directamente al navegador
            $this->mpdf->Output('Solicitud ' . $datos['volMenor']['nombre_completo'] . '.pdf', 'D');

            // Terminar la ejecución
            exit;

        } catch (\CodeIgniter\Database\Exceptions\DatabaseException $dbException) {
            $mensaje      = 'Database error: ' . $dbException->getMessage();
            $jsonResponse = $this->responseUtil->setResponse(500, 'server_error', 'Error en la base de datos', []);
            $this->responseUtil->logWithContext($this->responseUtil->setResponse(500, 'server_error', $mensaje, []));
            return $this->response->setStatusCode(500)->setJSON($jsonResponse);

        } catch (\Mpdf\MpdfException $e) {
            $mensaje      = 'Error en mPDF: ' . $e->getMessage();
            $jsonResponse = $this->responseUtil->setResponse(500, 'server_error', 'Error al generar el PDF', []);
            $this->responseUtil->logWithContext($this->responseUtil->setResponse(500, 'server_error', $mensaje, []));
            return $this->response->setStatusCode(500)->setJSON($jsonResponse);

        } catch (\Exception $e) {
            $mensaje      = 'Exception: ' . $e->getMessage();
            $jsonResponse = $this->responseUtil->setResponse(500, 'server_error', 'Error al generar el PDF', []);
            $this->responseUtil->logWithContext($this->responseUtil->setResponse(500, 'server_error', $mensaje, []));
            return $this->response->setStatusCode(500)->setJSON($jsonResponse);
        }

    }

	/// ****************************************************************************************************************************
    // *!*   MOSTRAR TODAS LAS SOLICITUDES DE MAYORES EN LA VISTA DE ADMINISTRADOR (AJAX DATATABLE):
    // ****************************************************************************************************************************
	public function tblSoliMayores()
	{
        if ($this->request->isAJAX()) {

            $request       = $this->request->getPost();
            $draw          = intval($request['draw']);
            $start         = intval($request['start']);
            $length        = intval($request['length']);
            $searchValue   = $request['search']['value'] ?? '';
            $totals        = $this->modelVol->getTotalVoluntarioMayor($searchValue);
            $totalRecords  = $totals['totalRecords'];
            $totalFiltered = $totals['totalFiltered'];
            $resultList    = $this->modelVol->getVoluntarioMayorPaginado($start, $length, $searchValue);

            $data = [];
            $i = $start + 1;

            foreach ($resultList as $value) {

                $verPDF =
                    '<a class="btn-table btn-inactive" 
                        onclick="showSoliMayores(' . $value['id_solicitud'] . ', ' . $value['id_voluntario'] . 
                            ', \'' . $value['dui'] . '\', \'' . $value['telefono'] . '\')">
                        <i class="far fa-file-pdf fa-lg"></i>
                    </a>';

                $data[] = [
                    $i++,
                    $value['nombre_completo'],
                    $value['departamento'],
                    $value['fecha_ingreso'],
                    //$value['fecha_finalizacion'],
                    $verPDF
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
    // *!*   MOSTRAR TODAS LAS SOLICITUDES DE MENORES EN LA VISTA DE ADMINISTRADOR (AJAX DATATABLE):
    // ****************************************************************************************************************************
	public function tblSoliMenores()
	{
        if ($this->request->isAJAX()) {

            $request       = $this->request->getPost();
            $draw          = intval($request['draw']);
            $start         = intval($request['start']);
            $length        = intval($request['length']);
            $searchValue   = $request['search']['value'] ?? '';
            $totals        = $this->modelVol->getTotalVoluntarioMenor($searchValue);
            $totalRecords  = $totals['totalRecords'];
            $totalFiltered = $totals['totalFiltered'];
            $resultList    = $this->modelVol->getVoluntarioMenorPaginado($start, $length, $searchValue);

            $data = [];
            $i = $start + 1;

            foreach ($resultList as $value) {

                $verPDF =
                    '<a class="btn-table btn-inactive" 
                        onclick="showSoliMenores(' . $value['id_solicitud'] . ', ' . $value['id_voluntario'] . 
                            ', \'' . $value['dui_refe'] . '\', \'' . $value['telefono_refe'] . '\')">
                        <i class="far fa-file-pdf fa-lg"></i>
                    </a>';
                // $verPDF = 
                //     '<a class="btn-table btn-inactive" 
                //         href= "'.base_url().'GenerarPDF/verPdfIdMenores?id_voluntario='.$value['id_voluntario'].'" target="_blank">
                //         <i class="far fa-file-pdf fa-lg"></i>
                //     </a> ';

                $data[] = [
                    $i++,
                    $value['nombre_completo'],
                    $value['departamento'],
                    $value['nombre_completo_refe'],
                    $value['parentesco'],
                    $value['dui_refe'],
                    $value['fecha_ingreso'],
                    //$value['fecha_finalizacion'],
                    $verPDF
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
    // *!*   MOSTRAR PDF VOLUNTARIO MAYOR DE EDAD:
    // ****************************************************************************************************************************
    public function showSoliMayores($id_solicitud, $id_voluntario)
    {
        try {

            if (!$id_solicitud) {
                $jsonResponse = $this->responseUtil->setResponse(400, 'error', 'ID de la solicitud no proporcionado', []);
                return $this->response->setStatusCode(400)->setJSON($jsonResponse);
            }

            if (!$id_voluntario) {
                $jsonResponse = $this->responseUtil->setResponse(400, 'error', 'ID del voluntario no proporcionado', []);
                return $this->response->setStatusCode(400)->setJSON($jsonResponse);
            }

            $resultado = $this->modelVol->getSolicitudMayor($id_solicitud, $id_voluntario);
            if (!$resultado) {
                $jsonResponse = $this->responseUtil->setResponse(500, 'server_error', 'No se encontró la solicitud', []);
                return $this->response->setStatusCode(500)->setJSON($jsonResponse);
            }

            $datos['volMayor'] = $resultado;
            $nombre_archivo = 'Solicitud - ' . $datos['volMayor']['nombre_completo'] . '.pdf';
            $html = view('pdf/pdf_mayores', $datos);
            $this->mpdf->WriteHTML($html, 2);
            $this->mpdf->SetTitle($nombre_archivo);
            $this->mpdf->Output($nombre_archivo, 'I'); // La opción 'I' es para abrir el PDF en el navegador

            exit;

        } catch (\CodeIgniter\Database\Exceptions\DatabaseException $dbException) {
            $mensaje      = 'Database error: ' . $dbException->getMessage();
            $jsonResponse = $this->responseUtil->setResponse(500, 'server_error', 'Error en la base de datos', []);
            $this->responseUtil->logWithContext($this->responseUtil->setResponse(500, 'server_error', $mensaje, []));
            return $this->response->setStatusCode(500)->setJSON($jsonResponse);

        } catch (\Mpdf\MpdfException $e) {
            $mensaje      = 'Error en mPDF: ' . $e->getMessage();
            $jsonResponse = $this->responseUtil->setResponse(500, 'server_error', 'Error al generar el PDF', []);
            $this->responseUtil->logWithContext($this->responseUtil->setResponse(500, 'server_error', $mensaje, []));
            return $this->response->setStatusCode(500)->setJSON($jsonResponse);

        } catch (\Exception $e) {
            $mensaje      = 'Exception: ' . $e->getMessage();
            $jsonResponse = $this->responseUtil->setResponse(500, 'server_error', 'Error al generar el PDF', []);
            $this->responseUtil->logWithContext($this->responseUtil->setResponse(500, 'server_error', $mensaje, []));
            return $this->response->setStatusCode(500)->setJSON($jsonResponse);
        }
    }

    // ****************************************************************************************************************************
    // *!*   MOSTRAR PDF VOLUNTARIO MENOR DE EDAD:
    // ****************************************************************************************************************************
    public function showSoliMenores($id_solicitud, $id_voluntario)
    {
        try {

            if (!$id_solicitud) {
                $jsonResponse = $this->responseUtil->setResponse(400, 'error', 'ID de la solicitud no proporcionado', []);
                return $this->response->setStatusCode(400)->setJSON($jsonResponse);
            }

            if (!$id_voluntario) {
                $jsonResponse = $this->responseUtil->setResponse(400, 'error', 'ID del voluntario no proporcionado', []);
                return $this->response->setStatusCode(400)->setJSON($jsonResponse);
            }

            // if (!$dui) {
            //     $jsonResponse = $this->responseUtil->setResponse(400, 'error', 'DUI del voluntario no proporcionado', []);
            //     return $this->response->setStatusCode(400)->setJSON($jsonResponse);
            // }

            // if (!$telefono) {
            //     $jsonResponse = $this->responseUtil->setResponse(400, 'error', 'Teléfono del voluntario no proporcionado', []);
            //     return $this->response->setStatusCode(400)->setJSON($jsonResponse);
            // }

            $resultado = $this->modelVol->getSolicitudMenor($id_solicitud, $id_voluntario/*, $dui, $telefono*/);
            if (!$resultado) {
                $jsonResponse = $this->responseUtil->setResponse(500, 'server_error', 'No se encontró la solicitud', []);
                return $this->response->setStatusCode(500)->setJSON($jsonResponse);
            }

            $datos['volMenor'] = $resultado;
            $nombre_archivo = 'Solicitud - ' . $datos['volMenor']['nombre_completo'] . '.pdf';
            $html = view('pdf/pdf_menores', $datos);
            $this->mpdf->WriteHTML($html, 2);
            $this->mpdf->SetTitle($nombre_archivo);
            $this->mpdf->Output($nombre_archivo, 'I'); // La opción 'I' es para abrir el PDF en el navegador

            exit;

        } catch (\CodeIgniter\Database\Exceptions\DatabaseException $dbException) {
            $mensaje      = 'Database error: ' . $dbException->getMessage();
            $jsonResponse = $this->responseUtil->setResponse(500, 'server_error', 'Error en la base de datos', []);
            $this->responseUtil->logWithContext($this->responseUtil->setResponse(500, 'server_error', $mensaje, []));
            return $this->response->setStatusCode(500)->setJSON($jsonResponse);

        } catch (\Mpdf\MpdfException $e) {
            $mensaje      = 'Error en mPDF: ' . $e->getMessage();
            $jsonResponse = $this->responseUtil->setResponse(500, 'server_error', 'Error al generar el PDF', []);
            $this->responseUtil->logWithContext($this->responseUtil->setResponse(500, 'server_error', $mensaje, []));
            return $this->response->setStatusCode(500)->setJSON($jsonResponse);

        } catch (\Exception $e) {
            $mensaje      = 'Exception: ' . $e->getMessage();
            $jsonResponse = $this->responseUtil->setResponse(500, 'server_error', 'Error al generar el PDF', []);
            $this->responseUtil->logWithContext($this->responseUtil->setResponse(500, 'server_error', $mensaje, []));
            return $this->response->setStatusCode(500)->setJSON($jsonResponse);
        }
    }

}
