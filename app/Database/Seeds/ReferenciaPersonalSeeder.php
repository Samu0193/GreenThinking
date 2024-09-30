<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ReferenciaPersonalSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id_referencia'  => 1,
                'id_persona'     => 3,
                'parentesco'     => 'Madre',
                'fecha_creacion' => date('Y-m-d H:i:s')
            ],
            [
                'id_referencia'  => 2,
                'id_persona'     => 4,
                'parentesco'     => 'Padre',
                'fecha_creacion' => date('Y-m-d H:i:s')
            ]
        ];

        $this->db->table('referencia_personal')->insertBatch($data);
    }
}
