<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ViewUsuarios extends Migration
{
    public function up()
    {
        // Instrucción SQL para crear la vista
        $this->db->query("
            CREATE VIEW vw_usuarios AS
            SELECT u.id_usuario, u.id_persona, u.id_rol, CONCAT(p.nombres, ' ', p.apellidos) AS nombre_apellido,
                r.rol AS nombre_rol, u.usuario, p.telefono, u.email, u.fecha_creacion,
                CASE WHEN u.estado = 1 THEN 'Activo' ELSE 'Inactivo' END AS estado
            FROM usuario AS u
            JOIN persona AS p ON p.id_persona = u.id_persona
            JOIN roles   AS r ON r.id_rol     = u.id_rol;
        ");
    }

    public function down()
    {
        // Elimina la vista si se hace un rollback
        $this->db->query("DROP VIEW IF EXISTS vw_usuarios");
    }

}
