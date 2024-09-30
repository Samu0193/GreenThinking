<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TablaSolicitud extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_solicitud' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
                'null'           => false
            ],
            'id_voluntario' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false
            ],
            'id_referencia' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true
            ],
            'fecha_ingreso' => [
                'type'       => 'DATE',
                'null'       => false
            ],
            'fecha_finalizacion' => [
                'type'       => 'DATE',
                'null'       => false
            ],
            'fecha_creacion' => [
                'type' => 'DATETIME',
                'null' => false
            ]
        ]);

        $this->forge->addPrimaryKey('id_solicitud');
        $this->forge->addForeignKey('id_voluntario', 'voluntario', 'id_voluntario', 'CASCADE', 'CASCADE', 'solicitud_voluntario_foreign');
        $this->forge->addForeignKey('id_referencia', 'referencia_personal', 'id_referencia', 'CASCADE', 'CASCADE', 'solicitud_referencia_foreign');
        $this->forge->createTable('solicitud');
    }

    public function down()
    {
        $this->forge->dropForeignKey('solicitud', 'solicitud_voluntario_foreign');
        $this->forge->dropForeignKey('solicitud', 'solicitud_referencia_foreign');
        $this->forge->dropTable('solicitud');
    }
}
