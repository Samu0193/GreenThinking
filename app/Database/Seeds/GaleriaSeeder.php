<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class GaleriaSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'ruta_archivo'   => 'public/assets/img/galery/galeria1.jpg',
                'usuario_crea'   => 1,
                'fecha_creacion' => date('Y-m-d H:i:s')
            ],
            [
                'ruta_archivo'   => 'public/assets/img/galery/galeria2.jpg',
                'usuario_crea'   => 1,
                'fecha_creacion' => date('Y-m-d H:i:s')
            ],
            [
                'ruta_archivo'   => 'public/assets/img/galery/galeria3.jpg',
                'usuario_crea'   => 1,
                'fecha_creacion' => date('Y-m-d H:i:s')
            ],
            [
                'ruta_archivo'   => 'public/assets/img/galery/galeria4.jpg',
                'usuario_crea'   => 1,
                'fecha_creacion' => date('Y-m-d H:i:s')
            ],
            [
                'ruta_archivo'   => 'public/assets/img/galery/galeria5.jpg',
                'usuario_crea'   => 1,
                'fecha_creacion' => date('Y-m-d H:i:s')
            ],
            [
                'ruta_archivo'   => 'public/assets/img/galery/galeria6.jpg',
                'usuario_crea'   => 1,
                'fecha_creacion' => date('Y-m-d H:i:s')
            ],
            [
                'ruta_archivo'   => 'public/assets/img/galery/galeria7.jpg',
                'usuario_crea'   => 1,
                'fecha_creacion' => date('Y-m-d H:i:s')
            ],
            [
                'ruta_archivo'   => 'public/assets/img/galery/galeria8.jpg',
                'usuario_crea'   => 1,
                'fecha_creacion' => date('Y-m-d H:i:s')
            ],
            [
                'ruta_archivo'   => 'public/assets/img/galery/galeria9.jpg',
                'usuario_crea'   => 1,
                'fecha_creacion' => date('Y-m-d H:i:s')
            ],
            [
                'ruta_archivo'   => 'public/assets/img/galery/galeria10.jpg',
                'usuario_crea'   => 1,
                'fecha_creacion' => date('Y-m-d H:i:s')
            ],
            [
                'ruta_archivo'   => 'public/assets/img/galery/galeria11.jpg',
                'usuario_crea'   => 1,
                'fecha_creacion' => date('Y-m-d H:i:s')
            ],
            [
                'ruta_archivo'   => 'public/assets/img/galery/galeria12.jpg',
                'usuario_crea'   => 1,
                'fecha_creacion' => date('Y-m-d H:i:s')
            ],
            [
                'ruta_archivo'   => 'public/assets/img/galery/galeria13.jpg',
                'usuario_crea'   => 1,
                'fecha_creacion' => date('Y-m-d H:i:s')
            ],
            [
                'ruta_archivo'   => 'public/assets/img/galery/galeria14.jpg',
                'usuario_crea'   => 1,
                'fecha_creacion' => date('Y-m-d H:i:s')
            ],
            [
                'ruta_archivo'   => 'public/assets/img/galery/galeria15.jpg',
                'usuario_crea'   => 1,
                'fecha_creacion' => date('Y-m-d H:i:s')
            ],
            [
                'ruta_archivo'   => 'public/assets/img/galery/galeria16.jpg',
                'usuario_crea'   => 1,
                'fecha_creacion' => date('Y-m-d H:i:s')
            ],
            [
                'ruta_archivo'   => 'public/assets/img/galery/galeria17.jpg',
                'usuario_crea'   => 1,
                'fecha_creacion' => date('Y-m-d H:i:s')
            ],
            [
                'ruta_archivo'   => 'public/assets/img/galery/galeria18.jpg',
                'usuario_crea'   => 1,
                'fecha_creacion' => date('Y-m-d H:i:s')
            ],
            [
                'ruta_archivo'   => 'public/assets/img/galery/galeria19.jpg',
                'usuario_crea'   => 1,
                'fecha_creacion' => date('Y-m-d H:i:s')
            ],
            [
                'ruta_archivo'   => 'public/assets/img/galery/galeria20.jpg',
                'usuario_crea'   => 1,
                'fecha_creacion' => date('Y-m-d H:i:s')
            ],
        ];

        $this->db->table('galeria')->insertBatch($data);
    }
}
