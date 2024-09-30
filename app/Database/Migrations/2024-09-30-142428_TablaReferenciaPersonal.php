<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TablaReferenciaPersonal extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_referencia' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false
            ],
            'id_persona' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false
            ],
            'parentesco' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => false
            ],
            'fecha_creacion' => [
                'type' => 'DATETIME',
                'null' => false
            ]
        ]);

        $this->forge->addPrimaryKey('id_referencia');
        $this->forge->addForeignKey('id_persona', 'persona', 'id_persona', 'CASCADE', 'CASCADE', 'referencia_persona_foreign');
        $this->forge->createTable('referencia_personal');
    }

    public function down()
    {
        $this->forge->dropForeignKey('referencia_personal', 'referencia_persona_foreign');
        $this->forge->dropTable('referencia_personal');
    }
}
