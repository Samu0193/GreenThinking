<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class VoluntarioSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id_voluntario'           => 1,
                'id_persona'              => 5,
                'email'                   => 'jordaan@example.com',
                'departamento_residencia' => 6,
                'municipio_residencia'    => 1,
                'direccion'               => 'Col. Esmeralda, Barrio Lourdes',
                'fecha_creacion'          => date('Y-m-d H:i:s')
            ],
            [
                'id_voluntario'           => 2,
                'id_persona'              => 6,
                'email'                   => 'sofiaong@example.com',
                'departamento_residencia' => 6,
                'municipio_residencia'    => 1,
                'direccion'               => 'Col. Los Alpes, Pje, los Girasoles',
                'fecha_creacion'          => date('Y-m-d H:i:s')
            ],
            [
                'id_voluntario'           => 3,
                'id_persona'              => 7,
                'email'                   => 'josueong@example.com',
                'departamento_residencia' => 6,
                'municipio_residencia'    => 114,
                'direccion'               => 'Col. Sierra morena 2, Pje 10',
                'fecha_creacion'          => date('Y-m-d H:i:s')
            ]
        ];

        $this->db->table('voluntario')->insertBatch($data);
    }
}
