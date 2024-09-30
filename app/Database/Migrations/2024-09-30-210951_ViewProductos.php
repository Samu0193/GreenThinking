<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ViewProductos extends Migration
{
    public function up()
    {
        $this->db->query("
            CREATE VIEW vw_productos AS
            SELECT p.id_producto, p.ruta_archivo, p.nombre, p.descripcion, p.precio,
                CASE WHEN p.estado = 1 THEN 'Activo' ELSE 'Inactivo' END AS estado,
                p.usuario_crea, u.usuario, p.fecha_creacion
            FROM producto AS p
            JOIN usuario  AS u ON u.id_usuario = p.usuario_crea
            ORDER BY p.id_producto;
        ");
    }

    public function down()
    {
        $this->db->query("DROP VIEW IF EXISTS vw_productos");
    }

}
