<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RolesSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'rol'            => 'Admin',
                'fecha_creacion' => date('Y-m-d H:i:s')
            ],
            [
                'rol'            => 'Usuario',
                'fecha_creacion' => date('Y-m-d H:i:s')
            ]
        ];

        $this->db->table('roles')->insertBatch($data);
    }
}
