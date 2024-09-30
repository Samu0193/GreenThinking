<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ViewSolicitudMayores extends Migration
{
    public function up()
    {
        $this->db->query("
            CREATE VIEW vw_solicitud_mayor AS
            SELECT s.id_solicitud, v.id_voluntario, CONCAT(p.nombres, ' ', p.apellidos) AS nombre_completo,
                d.nombre_departamento AS departamento, m.nombre_municipio AS municipio, v.email, p.telefono,
                p.dui, v.direccion, s.fecha_ingreso, s.fecha_finalizacion
            FROM solicitud    AS s
            JOIN voluntario   AS v ON v.id_voluntario   = s.id_voluntario
            JOIN persona      AS p ON p.id_persona      = v.id_persona
            JOIN departamento AS d ON d.id_departamento = v.departamento_residencia
            JOIN municipio    AS m ON m.id_municipio    = v.municipio_residencia
            WHERE p.edad >= 18;
        ");
    }

    public function down()
    {
        $this->db->query("DROP VIEW IF EXISTS vw_solicitud_mayor");
    }
}
