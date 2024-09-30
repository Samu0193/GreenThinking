<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UsuarioSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id_usuario'     => 1,
                'id_persona'     => 1,
                'id_rol'         => 1,
                'usuario'        => 'cesar2021',
                'password'       => sha1('root'),
                'email'          => 'barrerasansesamueldavid@gmail.com',
                'hash_key'       => null,
                'hash_expiry'    => null,
                'estado'         => true,
                'fecha_creacion' => date('Y-m-d H:i:s')
            ],
            [
                'id_usuario'     => 2,
                'id_persona'     => 2,
                'id_rol'         => 1,
                'usuario'        => 'gisela2021',
                'password'       => sha1('root'),
                'email'          => '5barrerasansesamueldavid@gmail.com',
                'hash_key'       => null,
                'hash_expiry'    => null,
                'estado'         => true,
                'fecha_creacion' => date('Y-m-d H:i:s')
            ]
        ];

        $this->db->table('usuario')->insertBatch($data);
    }
    
}
