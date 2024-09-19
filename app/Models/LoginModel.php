<?php

namespace App\Models;

use CodeIgniter\Model;

class LoginModel extends Model
{
    protected $table = 'usuario';
    protected $primaryKey = 'id_usuario';
    protected $allowedFields = [
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

    public $validatorPassword = [
        'password' => [
            'rules'  => 'required',
            'errors' => [
                'required' => 'Contraseña requerida.'
            ]
        ],
        're_password' => [
            'rules'  => 'required|passwordMatch[password]',
            'errors' => [
                'required'      => 'Repetir contraseña requerido.',
                'passwordMatch' => 'Las contraseñas no coinciden.'
            ]
        ]
    ];

    // *************************************************************************************************************************
    //    VALIDAR USUARIO Y CONTRASEÑA:
    public function loginView($nombre, $password)
    {
        return $this->where(['usuario' => $nombre, 'password' => $password])->first();
    }

    // *************************************************************************************************************************
    //    VALIDAR CORREO PARA RECUPERACION DE CONTRASEÑA:
    public function validateEmail($email)
    {
        // return $this->where('email', $email)->first();
        return $this->db->table('vw_usuarios')->where('email', $email)->get()->getRowArray();
    }

    // *************************************************************************************************************************
    //    ACTUALIZAR CONTRASEÑA Y HASH PARA RECUPERACION DE CONTRASEÑA:
    public function updatePasswordHash($data, $email)
    {
        return $this->where('email', $email)->set($data)->update();
    }

    // *************************************************************************************************************************
    //    OBTIENE EL HASH PARA VALIDAR LA RECUPERACION DE CONTRASEÑA:
    public function getHashDetails($hash)
    {
        return $this->where('hash_key', $hash)->first();
    }

    // *************************************************************************************************************************
    //    ACTUALIZA EL HASH Y LA CONTRASEÑA:
    public function updateNewPassword($data, $hash)
    {
        // try {
        //     // Verificamos si la columna 'hash_keys' existe antes de hacer la consulta
        //     if ($this->db->fieldExists('hash_keys', 'usuarios')) {
        //         // Realizar la actualización
        //         return $this->where('hash_keys', $hash)->set($data)->update();
        //     } else {
        //         // Lanza una excepción personalizada si la columna no existe
        //         log_message('debug', 'El campo hash_keys no existe en la base de datos.');
        //         throw new \Exception('El campo hash_keys no existe en la base de datos.');
        //     }
        // } catch (\Exception $e) {
        //     // Manejo del error (logearlo o devolver un mensaje amigable)
        //     log_message('debug', 'Error ocurrio' . $e->getMessage());
        //     return false; // o un mensaje de error más amigable
        // }
        return $this->where('hash_key', $hash)->set($data)->update();
    }

}
