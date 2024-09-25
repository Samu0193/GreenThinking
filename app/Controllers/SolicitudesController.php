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
                'title'        => ' - Galeria',
                'session_data' => $sessionData
            ];
            return view('layout/header', $data) .
                   view('layout/navegacion') .
                   view('solicitud/index') .
                   view('layout/footer');
        }

        return redirect()->to(site_url('login'))->with('message', 'Usted no se ha identificado');
    }

	/// ****************************************************************************************************************************
    // *!*   MOSTRAR TODAS LAS SOLICITUDES DE MAYORES EN LA VISTA DE ADMINISTRADOR (AJAX DATATABLE):
    // ****************************************************************************************************************************
	public function verSolicitudMayores()
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
                        href= "'.base_url().'GenerarPDF/verPdfIdMayores?id_voluntario='.$value['id_voluntario'].'" target="_blank">
                        <i class="far fa-file-pdf fa-lg"></i>
                    </a> ';

                $data[] = [
                    $i++,
                    $value['nombre_completo'],
                    $value['nombre_departamento'],
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
	public function verSolicitudMenores()
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
                        href= "'.base_url().'GenerarPDF/verPdfIdMenores?id_voluntario='.$value['id_voluntario'].'" target="_blank">
                        <i class="far fa-file-pdf fa-lg"></i>
                    </a> ';

                $data[] = [
                    $i++,
                    $value['nombre_completo'],
                    $value['nombre_completo_refe'],
                    $value['dui_refe'],
                    $value['nombre_departamento'],
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

    

}
