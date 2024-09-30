<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ViewSolicitudMenores extends Migration
{
    public function up()
    {
        $this->db->query("
            CREATE VIEW vw_solicitud_menor AS
            SELECT s.id_solicitud, v.id_voluntario, CONCAT(p.nombres, ' ', p.apellidos) AS nombre_completo,
                d.nombre_departamento AS departamento, m.nombre_municipio AS municipio, v.email, p.telefono,
                v.direccion, CONCAT(pr.nombres, ' ', pr.apellidos) AS nombre_completo_refe, pr.edad AS edad_refe,
                pr.dui AS dui_refe, pr.telefono AS telefono_refe, rp.parentesco, s.fecha_ingreso, s.fecha_finalizacion
            FROM solicitud           AS s
            JOIN voluntario          AS v  ON v.id_voluntario   = s.id_voluntario
            JOIN persona             AS p  ON p.id_persona      = v.id_persona
            JOIN departamento        AS d  ON d.id_departamento = v.departamento_residencia
            JOIN municipio           AS m  ON m.id_municipio    = v.municipio_residencia
            JOIN referencia_personal AS rp ON rp.id_referencia  = s.id_referencia
            JOIN persona             AS pr ON pr.id_persona     = rp.id_persona
            WHERE p.edad < 18;
        ");
    }

    public function down()
    {
        $this->db->query("DROP VIEW IF EXISTS vw_solicitud_menor");
    }
}
