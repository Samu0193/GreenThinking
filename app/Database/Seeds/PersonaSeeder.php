<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PersonaSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id_persona'     => 1,
                'nombres'        => 'Cesar',
                'apellidos'      => 'Grande',
                'edad'           => 28,
                'dui'            => '05256595-2',
                'telefono'       => '7126-9556',
                'fecha_creacion' => date('Y-m-d H:i:s')
            ],
            [
                'id_persona'     => 2,
                'nombres'        => 'Gisela',
                'apellidos'      => 'Ramos',
                'edad'           => 25,
                'dui'            => '05632595-2',
                'telefono'       => '7855-9556',
                'fecha_creacion' => date('Y-m-d H:i:s')
            ],
            [
                'id_persona'     => 3,
                'nombres'        => 'Maria Esmeralda',
                'apellidos'      => 'Fuentes',
                'edad'           => 32,
                'dui'            => '05642859-2',
                'telefono'       => '7885-9556',
                'fecha_creacion' => date('Y-m-d H:i:s')
            ],
            [
                'id_persona'     => 4,
                'nombres'        => 'Byron Ernesto',
                'apellidos'      => 'Lopez',
                'edad'           => 40,
                'dui'            => '05282595-2',
                'telefono'       => '7155-9556',
                'fecha_creacion' => date('Y-m-d H:i:s')
            ],
            [
                'id_persona'     => 5,
                'nombres'        => 'Jordan Emerson',
                'apellidos'      => 'Fuentes',
                'edad'           => 16,
                'dui'            => null,
                'telefono'       => null,
                'fecha_creacion' => date('Y-m-d H:i:s')
            ],
            [
                'id_persona'     => 6,
                'nombres'        => 'Tania Sofia',
                'apellidos'      => 'Grande',
                'edad'           => 28,
                'dui'            => null,
                'telefono'       => '7898-7789',
                'fecha_creacion' => date('Y-m-d H:i:s')
            ],
            [
                'id_persona'     => 7,
                'nombres'        => 'Josue Isaias',
                'apellidos'      => 'Beltran',
                'edad'           => 22,
                'dui'            => '05141558-8',
                'telefono'       => '7898-5689',
                'fecha_creacion' => date('Y-m-d H:i:s')
            ]
        ];

        $this->db->table('persona')->insertBatch($data);
    }
}
