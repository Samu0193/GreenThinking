<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProductoSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id_producto'    => 1,
                'ruta_archivo'   => 'assets/img/products/producto1.jpeg',
                'nombre'         => 'Llaveros metal',
                'descripcion'    => 'Llaveros elaborados a base de pestañas de latas, unidas por cintas de distintos colores, decorados con piedras naturales',
                'precio'         => 1.00,
                'estado'         => true,
                'usuario_crea'   => 1,
                'fecha_creacion' => date('Y-m-d H:i:s')
            ],
            [
                'id_producto'    => 2,
                'ruta_archivo'   => 'assets/img/products/producto2.jpeg',
                'nombre'         => 'Llaveros papel',
                'descripcion'    => 'Llaveros elaborados a base de papel reciclado, decorados con piedras naturales',
                'precio'         => 1.00,
                'estado'         => true,
                'usuario_crea'   => 1,
                'fecha_creacion' => date('Y-m-d H:i:s')
            ],
            [
                'id_producto'    => 3,
                'ruta_archivo'   => 'assets/img/products/producto3.jpeg',
                'nombre'         => 'Llaveros papel',
                'descripcion'    => 'Llaveros elaborados a base de papel reciclado, decorados con piedras naturales',
                'precio'         => 1.00,
                'estado'         => true,
                'usuario_crea'   => 1,
                'fecha_creacion' => date('Y-m-d H:i:s')
            ],
            [
                'id_producto'    => 4,
                'ruta_archivo'   => 'assets/img/products/producto4.jpeg',
                'nombre'         => 'Llaveros papel',
                'descripcion'    => 'Llaveros elaborados a base de papel reciclado, decorados con piedras naturales',
                'precio'         => 1.00,
                'estado'         => true,
                'usuario_crea'   => 1,
                'fecha_creacion' => date('Y-m-d H:i:s')
            ],
            [
                'id_producto'    => 5,
                'ruta_archivo'   => 'assets/img/products/producto5.jpeg',
                'nombre'         => 'Llaveros papel',
                'descripcion'    => 'Llaveros elaborados a base de papel reciclado, decorados con piedras naturales',
                'precio'         => 1.00,
                'estado'         => true,
                'usuario_crea'   => 1,
                'fecha_creacion' => date('Y-m-d H:i:s')
            ],
            [
                'id_producto'    => 6,
                'ruta_archivo'   => 'assets/img/products/producto6.jpeg',
                'nombre'         => 'Aretes',
                'descripcion'    => 'Elaborados a base de popotes y botellas recicladas, de distintos colores y estilos',
                'precio'         => 0.75,
                'estado'         => true,
                'usuario_crea'   => 1,
                'fecha_creacion' => date('Y-m-d H:i:s')
            ],
            [
                'id_producto'    => 7,
                'ruta_archivo'   => 'assets/img/products/producto7.jpeg',
                'nombre'         => 'Aretes',
                'descripcion'    => 'Elaborados a base de popotes y botellas recicladas, de distintos colores y estilos',
                'precio'         => 0.75,
                'estado'         => true,
                'usuario_crea'   => 1,
                'fecha_creacion' => date('Y-m-d H:i:s')
            ],
            [
                'id_producto'    => 8,
                'ruta_archivo'   => 'assets/img/products/producto8.jpeg',
                'nombre'         => 'Aretes',
                'descripcion'    => 'Elaborados a base de popotes y botellas recicladas, de distintos colores y estilos',
                'precio'         => 0.75,
                'estado'         => true,
                'usuario_crea'   => 1,
                'fecha_creacion' => date('Y-m-d H:i:s')
            ]
        ];

        $this->db->table('producto')->insertBatch($data);
    }

}
