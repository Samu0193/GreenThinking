<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SolicitudSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id_voluntario'      => 1,
                'id_referencia'      => 1,
                'fecha_ingreso'      => '2024-10-05',
                'fecha_finalizacion' => '2024-12-31',
                'fecha_creacion'     => date('Y-m-d H:i:s')
            ],
            [
                'id_voluntario'      => 2,
                'id_referencia'      => 2,
                'fecha_ingreso'      => '2024-10-05',
                'fecha_finalizacion' => '2024-12-31',
                'fecha_creacion'     => date('Y-m-d H:i:s')
            ],
            [
                'id_voluntario'      => 3,
                'id_referencia'      => null,
                'fecha_ingreso'      => '2024-10-05',
                'fecha_finalizacion' => '2024-12-31',
                'fecha_creacion'     => date('Y-m-d H:i:s')
            ]
        ];

        $this->db->table('solicitud')->insertBatch($data);
    }
}
