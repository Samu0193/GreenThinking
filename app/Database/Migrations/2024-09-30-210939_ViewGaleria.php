<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ViewGaleria extends Migration
{
    public function up()
    {
        $this->db->query("
            CREATE VIEW vw_galeria AS
            SELECT g.id_galeria, g.ruta_archivo, g.usuario_crea, u.usuario, g.fecha_creacion
            FROM galeria AS g
            JOIN usuario AS u ON u.id_usuario = g.usuario_crea
            ORDER BY g.id_galeria;
        ");
    }

    public function down()
    {
        $this->db->query("DROP VIEW IF EXISTS vw_galeria");
    }

}
