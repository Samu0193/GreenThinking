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
        return $this->where('email', $email)->first();
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
        return $this->where('hash_key', $hash)->set($data)->update();
    }
}
