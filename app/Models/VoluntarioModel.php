<?php

namespace App\Models;

use CodeIgniter\Model;

class VoluntarioModel extends Model
{
    protected $table         = 'voluntario';
    protected $primaryKey    = 'id_voluntario';
    protected $allowedFields = [
        'id_persona',
        'email',
        'departamento_residencia',
        'municipio_residencia',
        'direccion',
        'fecha_creacion'
    ];

    // ****************************************************************************************************************************
    // *!*   VALIDATOR DE VOLUNTARIO MAYOR:
    // ****************************************************************************************************************************
    public $validatorMayor = [
        'nombres' => [
            'rules'  => 'required|alfaOespacio',
            'errors' => [
                'required'     => 'Nombres requeridos.',
                'alfaOespacio' => 'Nombres sólo pueden contener letras y espacios.'
            ]
        ],
        'apellidos' => [
            'rules'  => 'required|alfaOespacio',
            'errors' => [
                'required'     => 'Apellidos requeridos.',
                'alfaOespacio' => 'Apellidos sólo pueden contener letras y espacios.'
            ]
        ],
        'f_nacimiento_mayor' => [
            'rules'  => 'required|valid_date|minEdadMayor|maxEdadMayor',
            'errors' => [
                'required'     => 'Fecha de nacimiento requerida.',
                'valid_date'   => 'Fecha de nacimiento debe ser una fecha válida.',
                'minEdadMayor' => 'Edad máxima 40 años.',
                'maxEdadMayor' => 'Edad mínima 18 años.',
            ]
        ],
        'DUI' => [
            'rules'  => 'required|isDUI|is_unique[persona.DUI]',
            'errors' => [
                'required'  => 'DUI requerido.',
                'isDUI'     => 'DUI inválido.',
                'is_unique' => 'Este DUI ya está registrado.'
            ]
        ],
        'telefono' => [
            'rules'  => 'required|telefono|is_unique[persona.telefono]',
            'errors' => [
                'required'  => 'Teléfono requerido.',
                'telefono'  => 'Teléfono inválido.',
                'is_unique' => 'Este teléfono ya está registrado.'
            ]
        ],
        'email' => [
            'rules'  => 'required|valid_email|is_unique[voluntario.email]',
            'errors' => [
                'required'    => 'Email requerido.',
                'valid_email' => 'Ingrese un email válido.',
                'is_unique'   => 'Este email ya está registrado.'
            ]
        ],
        'departamento_residencia' => [
            'rules'  => 'required|integer',
            'errors' => [
                'required' => 'Departamento requerido.',
                'integer'  => 'Departamento debe ser un número entero.'
            ]
        ],
        'municipio_residencia' => [
            'rules'  => 'required|integer',
            'errors' => [
                'required' => 'Municipio requerido.',
                'integer'  => 'Municipio debe ser un número entero.'
            ]
        ],
        'direccion' => [
            'rules'  => 'required',
            'errors' => [
                'required' => 'Dirección requerida.'
            ]
        ],
        'fecha_finalizacion' => [
            'rules'  => 'required|valid_date|minFin|maxFin',
            'errors' => [
                'required'   => 'Fecha de finalización requerida.',
                'valid_date' => 'Fecha de finalización debe ser una fecha válida.'
            ]
        ]
    ];

    // ****************************************************************************************************************************
    // *!*   VALIDATOR DE VOLUNTARIO MENOR:
    // ****************************************************************************************************************************
    public $validatorMenor = [
        'parentesco' => [
            'rules'  => 'required|alfaOespacio',
            'errors' => [
                'required'     => 'Parentezco requerido.',
                'alfaOespacio' => 'Parentezco sólo puede contener letras y espacios.'
            ]
        ],
        'nombres_ref' => [
            'rules'  => 'required|alfaOespacio',
            'errors' => [
                'required'     => 'Nombres del referente requeridos.',
                'alfaOespacio' => 'Nombres del referente sólo pueden contener letras y espacios.'
            ]
        ],
        'apellidos_ref' => [
            'rules'  => 'required|alfaOespacio',
            'errors' => [
                'required'     => 'Apellidos del referente requeridos.',
                'alfaOespacio' => 'Apellidos del referente sólo pueden contener letras y espacios.'
            ]
        ],
        'f_nacimiento_ref' => [
            'rules'  => 'required|valid_date|minEdadRef|maxEdadRef',
            'errors' => [
                'required'   => 'Fecha de nacimiento del referente requerida.',
                'valid_date' => 'Fecha de nacimiento del referente debe ser una fecha válida.',
                'minEdadRef' => 'Edad máxima del referente 70 años.',
                'maxEdadRef' => 'Edad mínima del referente 20 años.',
            ]
        ],
        'DUI_ref' => [
            'rules'  => 'required|isDUI|is_unique[persona.DUI]',
            'errors' => [
                'required'  => 'DUI requerido.',
                'isDUI'     => 'DUI inválido.',
                'is_unique' => 'Este DUI ya está registrado.'
            ]
        ],
        'telefono_ref' => [
            'rules'  => 'required|telefono|noRepeatTelefono[telefono_menor]|uniqueTelefono[telefono_ref]',
            'errors' => [
                'required'  => 'Teléfono del referente requerido.',
                'telefono'  => 'Teléfono del referente inválido.',
                'uniqueTelefono' => 'Este teléfono ya está registrado.'
            ]
        ],
        'nombres_menor' => [
            'rules'  => 'required|alfaOespacio',
            'errors' => [
                'required'     => 'Nombres del voluntario requeridos.',
                'alfaOespacio' => 'Nombres del voluntario sólo pueden contener letras y espacios.'
            ]
        ],
        'apellidos_menor' => [
            'rules'  => 'required|alfaOespacio',
            'errors' => [
                'required'     => 'Apellidos del voluntario requeridos.',
                'alfaOespacio' => 'Apellidos del voluntario sólo pueden contener letras y espacios.'
            ]
        ],
        'f_nacimiento_menor' => [
            'rules'  => 'required|valid_date|minEdadMenor|maxEdadMenor',
            'errors' => [
                'required'     => 'Fecha de nacimiento del voluntario requerida.',
                'valid_date'   => 'Fecha de nacimiento del voluntario debe ser una fecha válida.',
                'minEdadMenor' => 'Edad máxima 17 años.',
                'maxEdadMenor' => 'Edad mínima 12 años.',
            ]
        ],
        'telefono_menor' => [
            'rules'  => 'required|telefono|noRepeatTelefono[telefono_ref]|uniqueTelefono[telefono_menor]',
            'errors' => [
                'required'  => 'Teléfono del voluntario requerido.',
                'telefono'  => 'Teléfono del voluntario inválido.',
                'noRepeatTelefono'  => 'Los telefonos no pueden ser iguales.',
                'uniqueTelefono' => 'Este teléfono ya está registrado.'
            ]
        ],
        'email' => [
            'rules'  => 'required|valid_email|is_unique[usuario.email]',
            'errors' => [
                'required'    => 'Email del voluntario requerido.',
                'valid_email' => 'Email del voluntario inválido.',
                'is_unique'   => 'Este email ya está registrado.'
            ]
        ],
        'departamento_residencia' => [
            'rules'  => 'required|integer',
            'errors' => [
                'required' => 'Departamento del voluntario requerido.',
                'integer'  => 'Departamento del voluntario debe ser un número entero.'
            ]
        ],
        'municipio_residencia' => [
            'rules'  => 'required|integer',
            'errors' => [
                'required' => 'Municipio del voluntario requerido.',
                'integer'  => 'Municipio del voluntario debe ser un número entero.'
            ]
        ],
        'direccion' => [
            'rules'  => 'required',
            'errors' => [
                'required' => 'Dirección del voluntario requerida.'
            ]
        ],
        'fecha_finalizacion' => [
            'rules'  => 'required|valid_date|minFin|maxFin',
            'errors' => [
                'required'   => 'Fecha de finalización requerida.',
                'valid_date' => 'Fecha de finalización debe ser una fecha válida.'
            ]
        ]
    ];

    // ****************************************************************************************************************************
    // *!*   OBTIENE Y GENERA EL ID MAXIMO DE LA TABLA "PERSONA":
    // ****************************************************************************************************************************
    public function maxPersona()
    {
        $maxID = $this->db->table('persona')
                          ->selectMax('id_persona')
                          ->get()
                          ->getRowArray();
        return $maxID['id_persona'] ? $maxID['id_persona'] + 1 : 1;
    }

    // ****************************************************************************************************************************
    // *!*   OBTIENE Y GENERA EL ID MAXIMO DE LA TABLA "VOLUNTARIO":
    // ****************************************************************************************************************************
    public function maxVoluntario()
    {
        $maxID = $this->db->table('voluntario')
                          ->selectMax('id_voluntario')
                          ->get()
                          ->getRowArray();
        return $maxID['id_voluntario'] ? $maxID['id_voluntario'] + 1 : 1;
    }

    // ****************************************************************************************************************************
    // *!*   OBTIENE Y GENERA EL ID MAXIMO DE LA TABLA "REFERENCIA PERSONAL":
    // ****************************************************************************************************************************
    public function maxReferenciaPersonal()
    {
        $maxID = $this->db->table('referencia_personal')
                          ->selectMax('id_referencia')
                          ->get()
                          ->getRowArray();
        return $maxID['id_referencia'] ? $maxID['id_referencia'] + 1 : 1;
    }

    // ****************************************************************************************************************************
    // *!*   OBTIENE TODOS LOS DEPARTAMENTOS:
    // ****************************************************************************************************************************
    public function getDepartamentos()
    {
        return $this->db->table('departamento')
                        ->get()
                        ->getResultArray();
    }

    // ****************************************************************************************************************************
    // *!*   OBTIENE TODOS LOS MUNICIPIOS DE CADA DEPARTAMENTO (DINAMICO):
    // ****************************************************************************************************************************
    public function getMunicipios($id_departamento)
    {
        return $this->db->table('municipio')
                        ->where('id_departamento', $id_departamento)
                        ->get()
                        ->getResultArray();
    }

    // ****************************************************************************************************************************
    // *!*   BUCAR REGISTRO DE UN DUI PARA VALIDAR EN LA TABLA "PERSONA":
    // ****************************************************************************************************************************
    public function findDUI($valor)
    {
        return $this->db->table('persona')
                        ->where('DUI', $valor)
                        ->get()
                        ->getResult();
    }

    // ****************************************************************************************************************************
    // *!*   BUCAR REGISTRO DE UN TELEFONO PARA VALIDAR EN LA TABLA "PERSONA":
    // ****************************************************************************************************************************
    public function findTel($valor)
    {
        return $this->db->table('persona')
                        ->where('telefono', $valor)
                        ->get()
                        ->getResult();
    }

    // ****************************************************************************************************************************
    // *!*   BUCAR REGISTRO DE UN CORREO PARA VALIDAR EN LA TABLA "VOLUNTARIO":
    // ****************************************************************************************************************************
    public function findEmail($valor)
    {
        return $this->db->table('voluntario')
                        ->where('email', $valor)
                        ->get()
                        ->getResult();
    }

    // ****************************************************************************************************************************
    // *!*   CREAR UN REGISTRO EN LA TABLA "PERSONA":
    // ****************************************************************************************************************************
    public function insertPersona($data)
    {
        return $this->db->table('persona')->insert($data);
    }

    // ****************************************************************************************************************************
    // *!*   CREAR UN REGISTRO EN LA TABLA "VOLUNTARIO":
    // ****************************************************************************************************************************
    public function insertVoluntario($data)
    {
        return $this->db->table('voluntario')->insert($data);
    }

    // ****************************************************************************************************************************
    // *!*   CREAR UN REGISTRO EN LA TABLA "SOLICITUD":
    // ****************************************************************************************************************************
    public function insertSolicitud($data)
    {
        return $this->db->table('solicitud')->insert($data);
    }

    // ****************************************************************************************************************************
    // *!*   CREAR UN REGISTRO EN LA TABLA "REFERENCIA PERSONAL":
    // ****************************************************************************************************************************
    public function insertReferencia($data)
    {
        return $this->db->table('referencia_personal')->insert($data);
    }

    // ****************************************************************************************************************************
    // *!*   OBTIENE LAS SOLICITUDES DE LOS VOLUNTARIOS MAYORES DE EDAD:
    // ****************************************************************************************************************************
    public function mostrarSolicitudMayor()
    {
        return $this->select('v.id_voluntario, p.nombres, p.apellidos, d.nombre_departamento, s.fecha_ingreso, s.fecha_finalizacion')
                    ->from('voluntario v')
                    ->join('persona p', 'v.id_persona = p.id_persona')
                    ->join('solicitud s', 's.id_voluntario = v.id_voluntario')
                    ->join('departamento d', 'd.id_departamento = v.departamento_residencia')
                    ->where('p.edad >=', 18)
                    ->get()
                    ->getResultArray();
    }

    // ****************************************************************************************************************************
    // *!*   OBTIENE TODAS LAS SOLICITUDES DE LOS VOLUNTARIOS MAYORES (AJAX DATATABLE):
    // ****************************************************************************************************************************
    public function getTotalVoluntarioMayor($searchValue)
    {
        // Consulta base desde la vista
        $builder = $this->db->table('vw_solicitud_mayor');

        // Total sin filtro
        $totalRecords = $builder->countAllResults(false); // Evita reiniciar el builder

        // Total filtrado (si existe búsqueda)
        if ($searchValue) {
            $builder->groupStart()
                    ->like('nombre_completo', $searchValue)
                    ->orLike('nombre_departamento', $searchValue)
                    ->orLike('fecha_ingreso', $searchValue)
                    ->orLike('fecha_finalizacion', $searchValue)
                    ->groupEnd();
        }

        $totalFiltered = $builder->countAllResults();
        return ['totalRecords' => $totalRecords, 'totalFiltered' => $totalFiltered];
    }

    // ****************************************************************************************************************************
    // *!*   OBTIENE LAS LAS SOLICITUDES DE LOS VOLUNTARIOS MAYORES PAGINADAS (AJAX DATATABLE):
    // ****************************************************************************************************************************
    public function getVoluntarioMayorPaginado($start, $length, $searchValue)
    {
        // Consulta base desde la vista
        $builder = $this->db->table('vw_solicitud_mayor');

        // Filtro de búsqueda
        if ($searchValue) {
            $builder->groupStart()
                    ->like('nombre_completo', $searchValue)
                    ->orLike('nombre_departamento', $searchValue)
                    ->orLike('fecha_ingreso', $searchValue)
                    ->orLike('fecha_finalizacion', $searchValue)
                    ->groupEnd();
        }

        // Aplicar paginación
        return $builder->limit($length, $start)->get()->getResultArray();
    }

    // ****************************************************************************************************************************
    // *!*   OBTIENE LAS SOLICITUDES DE LOS VOLUNTARIOS MENORES DE EDAD:
    // ****************************************************************************************************************************
    public function mostrarSolicitudMenor()
    {
        return $this->select('v.id_voluntario, p.nombres, p.apellidos, pe.nombres as nombre_parantesco, 
                              pe.apellidos as apellido_parantesco, pe.DUI as dui_refe, d.nombre_departamento, 
                              s.fecha_ingreso, s.fecha_finalizacion')
                    ->from('voluntario v')
                    ->join('persona p', 'v.id_persona = p.id_persona')
                    ->join('solicitud s', 's.id_voluntario = v.id_voluntario')
                    ->join('referencia_personal rp', 'rp.id_referencia = s.id_referencia')
                    ->join('persona pe', 'pe.id_persona = rp.id_persona')
                    ->join('departamento d', 'd.id_departamento = v.departamento_residencia')
                    ->where('p.edad <', 18)
                    ->get()
                    ->getResultArray();
    }

    // ****************************************************************************************************************************
    // *!*   OBTIENE TODAS LAS SOLICITUDES DE LOS VOLUNTARIOS MENORES (AJAX DATATABLE):
    // ****************************************************************************************************************************
    public function getTotalVoluntarioMenor($searchValue)
    {
        // Consulta base desde la vista
        $builder = $this->db->table('vw_solicitud_menor');

        // Total sin filtro
        $totalRecords = $builder->countAllResults(false); // Evita reiniciar el builder

        // Total filtrado (si existe búsqueda)
        if ($searchValue) {
            $builder->groupStart()
                    ->like('nombre_completo', $searchValue)
                    ->like('nombre_completo_refe', $searchValue)
                    ->like('dui_refe', $searchValue)
                    ->orLike('nombre_departamento', $searchValue)
                    ->orLike('fecha_ingreso', $searchValue)
                    ->orLike('fecha_finalizacion', $searchValue)
                    ->groupEnd();
        }

        $totalFiltered = $builder->countAllResults();
        return ['totalRecords' => $totalRecords, 'totalFiltered' => $totalFiltered];
    }

    // ****************************************************************************************************************************
    // *!*   OBTIENE LAS SOLICITUDES DE LOS VOLUNTARIOS MENORES PAGINADaS (AJAX DATATABLE):
    // ****************************************************************************************************************************
    public function getVoluntarioMenorPaginado($start, $length, $searchValue)
    {
        // Consulta base desde la vista
        $builder = $this->db->table('vw_solicitud_menor');

        // Filtro de búsqueda
        if ($searchValue) {
            $builder->groupStart()
                    ->like('nombre_completo', $searchValue)
                    ->like('nombre_completo_refe', $searchValue)
                    ->like('dui_refe', $searchValue)
                    ->orLike('nombre_departamento', $searchValue)
                    ->orLike('fecha_ingreso', $searchValue)
                    ->orLike('fecha_finalizacion', $searchValue)
                    ->groupEnd();
        }

        // Aplicar paginación
        return $builder->limit($length, $start)->get()->getResultArray();
    }












    // ****************************************************************************************************************************
    // *!*   OBTIENE VOLUNTARIO MAYOR DE EDAD (PARA VER PDF):
    // ****************************************************************************************************************************
    public function getVoluntarioMayor($dui, $telefono)
    {
        return $this->select('v.id_voluntario, p.nombres, p.apellidos, s.fecha_ingreso, s.fecha_finalizacion')
                    ->from('voluntario v')
                    ->join('persona p', 'v.id_persona = p.id_persona')
                    ->join('solicitud s', 's.id_voluntario = v.id_voluntario')
                    ->where('p.DUI', $dui)
                    ->where('p.telefono', $telefono)
                    ->get()
                    ->getRowArray();
    }

    // ****************************************************************************************************************************
    // *!*   OBTIENE VOLUNTARIO MENOR DE EDAD (PARA VER PDF):
    // ****************************************************************************************************************************
    public function getVoluntarioMenor($dui, $telefono)
    {
        return $this->select('v.id_voluntario, pv.nombres, pv.apellidos, pr.nombres AS nombre_parantesco,
	                        pr.apellidos AS apellido_parantesco, pr.DUI AS dui_refe, s.fecha_ingreso, s.fecha_finalizacion')
                    ->from('solicitud s')
                    ->join('voluntario v', 's.id_voluntario = v.id_voluntario')
                    ->join('referencia_personal rp', 'rp.id_referencia = s.id_referencia')
                    ->join('persona pv', 'pv.id_persona = v.id_persona')
                    ->join('persona pr', 'pr.id_persona = rp.id_persona')
                    ->where('pr.DUI', $dui)
                    ->where('pr.telefono', $telefono)
                    ->get()
                    ->getRowArray();
    }

}
