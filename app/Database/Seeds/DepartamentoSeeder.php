<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DepartamentoSeeder extends Seeder
{
    public function run()
    {
        // Datos para insertar en la tabla 'departamento'
        $data = [
            [
                'nombre_departamento' => 'Ahuachapán',
                'fecha_creacion'      => date('Y-m-d H:i:s')
            ],
            [
                'nombre_departamento' => 'Santa Ana',
                'fecha_creacion'      => date('Y-m-d H:i:s')
            ],
            [
                'nombre_departamento' => 'Sonsonate',
                'fecha_creacion'      => date('Y-m-d H:i:s')
            ],
            [
                'nombre_departamento' => 'La Libertad',
                'fecha_creacion'      => date('Y-m-d H:i:s')
            ],
            [
                'nombre_departamento' => 'Chalatenango',
                'fecha_creacion'      => date('Y-m-d H:i:s')
            ],
            [
                'nombre_departamento' => 'San Salvador',
                'fecha_creacion'      => date('Y-m-d H:i:s')
            ],
            [
                'nombre_departamento' => 'Cuscatlán',
                'fecha_creacion'      => date('Y-m-d H:i:s')
            ],
            [
                'nombre_departamento' => 'La Paz',
                'fecha_creacion'      => date('Y-m-d H:i:s')
            ],
            [
                'nombre_departamento' => 'Cabañas',
                'fecha_creacion'      => date('Y-m-d H:i:s')
            ],
            [
                'nombre_departamento' => 'San Vicente',
                'fecha_creacion'      => date('Y-m-d H:i:s')
            ],
            [
                'nombre_departamento' => 'Usulután',
                'fecha_creacion'      => date('Y-m-d H:i:s')
            ],
            [
                'nombre_departamento' => 'Morazán',
                'fecha_creacion'      => date('Y-m-d H:i:s')
            ],
            [
                'nombre_departamento' => 'San Miguel',
                'fecha_creacion'      => date('Y-m-d H:i:s')
            ],
            [
                'nombre_departamento' => 'La Unión',
                'fecha_creacion'      => date('Y-m-d H:i:s')
            ]
        ];

        // Insertar los datos en la tabla 'departamento'
        $this->db->table('departamento')->insertBatch($data);
    }
}
