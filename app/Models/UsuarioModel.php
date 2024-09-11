<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table = 'usuario';
    protected $primaryKey = 'id_usuario';
    protected $allowedFields = [
        'id_usuario',
        'id_persona',
        'id_rol',
        'usuario',
        'password',
        'email',
        'hash_key',
        'hash_expiry',
        'estado',
        'fecha_creacion'
    ];

    /*************************************************************************************************************************
    *!*     VALIDATOR:
     *************************************************************************************************************************/
    public $validatorUser = [
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
            'rules'  => 'required|valid_email|is_unique[usuario.email]',
            'errors' => [
                'required'    => 'Email requerido.',
                'valid_email' => 'Ingrese un email válido.',
                'is_unique'   => 'Este email ya está registrado.'
            ]
        ],
        'nombre_usuario' => [
            'rules'  => 'required|is_unique[usuario.usuario]',
            'errors' => [
                'required'  => 'Usuario requerido.',
                'is_unique' => 'Este usuario ya está registrado.'
            ]
        ],
        'password' => [
            'rules'  => 'required|passwordMatch[re_password]',
            'errors' => [
                'required'      => 'Contraseña requerida.',
                'passwordMatch' => 'Las contraseñas no coinciden.'
            ]
        ],
        're_password' => [
            'rules'  => 'required|passwordMatch[password]',
            'errors' => [
                'required'      => 'Repetir contraseña requerido.',
                'passwordMatch' => 'Las contraseñas no coinciden.'
            ]
        ]
        // 're_password' => [
        //     'rules'  => 'required|matches[password]',
        //     'errors' => [
        //         'required' => 'Repetir contraseña requerido.',
        //         'matches'  => 'Las contraseñas no coinciden.'
        //     ]
        // ]
    ];

    // *************************************************************************************************************************
    //    OBTIENE Y GENERA EL ID MAXIMO DE LA TABLA "PERSONA":
    public function maxPersona()
    {
        $maxID = $this->db->table('persona')
            ->selectMax('id_persona')
            ->get()
            ->getRowArray();
        return $maxID['id_persona'] ? $maxID['id_persona'] + 1 : 1;
    }

    // *************************************************************************************************************************
    //    OBTIENE Y GENERA EL ID MAXIMO DE LA TABLA "USUARIO":
    public function maxUsuario()
    {
        // $maxId = $this->selectMax('id_usuario')->get()->getRowArray();
        $maxID = $this->db->table('usuario')
            ->selectMax('id_usuario')
            ->get()
            ->getRowArray();
        return $maxID['id_usuario'] ? $maxID['id_usuario'] + 1 : 1;
    }

    // *************************************************************************************************************************
    //    BUCAR REGISTRO DE UN CORREO PARA VALIDAR EN LA TABLA "USUARIO":
    public function findEmail($valor)
    {
        return $this->where('email', $valor)->first();
    }

    // *************************************************************************************************************************
    //    BUCAR REGISTRO DE UN USUARIO PARA VALIDAR EN LA TABLA "USUARIO":
    public function findUser($valor)
    {
        return $this->where('usuario', $valor)->first();
    }

    // *************************************************************************************************************************
    //    OBTIENE TODOS LOS ROLES DE LA TABLA "ROLES":
    public function getRolesModel()
    {
        return $this->db->table('roles')->get()->getResultArray();
    }

    // *************************************************************************************************************************
    //    OBTIENE TODOS LOS USUARIOS CON NIVEL BAJO EN LA TABLA "USUARIOS":
    public function tblUsuariosModel()
    {
        return $this->db->table('usuario AS u')
                        ->select('u.id_usuario, u.id_persona, u.id_rol, u.usuario, u.email, u.fecha_creacion, u.estado, p.nombres, p.apellidos, p.telefono, r.rol')
                        ->join('persona AS p', 'p.id_persona = u.id_persona')
                        ->join('roles AS r', 'r.id_rol = u.id_rol')
                        ->where('u.id_rol', 2)
                        ->get()
                        ->getResultArray();
    }

    public function getTotalUsuarios($searchValue)
    {
        // Consulta base desde la vista
        $builder = $this->db->table('vw_usuarios');

        // Total sin filtro
        $totalRecords = $builder->countAllResults(false); // Evita reiniciar el builder

        // Total filtrado (si existe búsqueda)
        if ($searchValue) {
            $builder->groupStart()
                    ->like('nombre_apellido', $searchValue)
                    ->orLike('nombre_rol', $searchValue)
                    ->orLike('usuario', $searchValue)
                    ->orLike('telefono', $searchValue)
                    ->orLike('email', $searchValue)
                    ->orLike('fecha_creacion', $searchValue)
                    ->orLike('estado', $searchValue) // Búsqueda de estado "Activo" o "Inactivo"
                    ->groupEnd();
        }

        $totalFiltered = $builder->countAllResults();
        return ['totalRecords' => $totalRecords, 'totalFiltered' => $totalFiltered];
    }

    public function getUsuariosPaginados($start, $length, $searchValue)
    {
        // Consulta base desde la vista
        $builder = $this->db->table('vw_usuarios');

        // Filtro de búsqueda
        if ($searchValue) {
            $builder->groupStart()
                    ->like('nombre_apellido', $searchValue)
                    ->orLike('nombre_rol', $searchValue)
                    ->orLike('usuario', $searchValue)
                    ->orLike('telefono', $searchValue)
                    ->orLike('email', $searchValue)
                    ->orLike('fecha_creacion', $searchValue)
                    ->orLike('estado', $searchValue) // Búsqueda de estado "Activo" o "Inactivo"
                    ->groupEnd();
        }

        // Aplicar paginación
        return $builder->limit($length, $start)->get()->getResultArray();
    }




    // *************************************************************************************************************************
    //    OBTIENE EL ESTADO DE UN USUARIO DE LA TABLA "USUARIOS":
    public function getEstadoModel($id_usuario)
    {
        return $this->select('estado')->where('id_usuario', $id_usuario)->first();
    }

    // *************************************************************************************************************************
    //    CAMBIAR EL ESTADO DE UN USUARIO EN LA TABLA "USUARIOS":
    public function cambiarEstadoModel($id_usuario, $estado)
    {
        return $this->set('estado', $estado)->where('id_usuario', $id_usuario)->update();
    }

    // *************************************************************************************************************************
    //    CREAR REGISTRO EN LA TABLA "USUARIO":
    public function insertUsuario($data)
    {
        return $this->db->table('usuario')->insert($data); // Retorna 1 si es exitoso
    }

    // *************************************************************************************************************************
    //    CREAR REGISTRO EN LA TABLA "PERSONA":
    public function insertPersona($data)
    {
        return $this->db->table('persona')->insert($data);
    }

    // *************************************************************************************************************************
    //    ACTUALIZAR REGISTRO EN LA TABLA "PERSONA":
    public function actualizarPersona($data, $id_persona)
    {
        return $this->db->table('persona')->where('id_persona', $id_persona)->update($data);
    }

    // *************************************************************************************************************************
    //    ACTUALIZAR REGISTRO EN LA TABLA "USUARIO":
    public function actualizarUsuario($data, $id_usuario)
    {
        return $this->update($id_usuario, $data);
    }

    // *************************************************************************************************************************
    //    EDITAR REGISTRO EN LA TABLA "USUARIO":
    public function editarUsuario($id_usuario)
    {
        return $this->select('u.*, p.*')
            ->from('usuario u')
            ->join('persona p', 'u.id_persona = p.id_persona')
            ->where('u.id_usuario', $id_usuario)
            ->first();
    }

    // *************************************************************************************************************************
    //    ACTUALIZAR REGISTRO EN LA TABLA "USUARIO" (METODO ALTERNATIVO):
    public function actualizar($data)
    {
        return $this->update($data['id_usuario'], $data);
    }

    // *************************************************************************************************************************
    //    ELIMINAR REGISTRO EN LA TABLA "USUARIO":
    public function eliminar($id_usuario)
    {
        return $this->delete($id_usuario);
    }
}
