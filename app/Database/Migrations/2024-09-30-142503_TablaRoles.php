<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TablaRoles extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_rol' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
                'null'           => false
            ],
            'rol' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false
            ],
            'fecha_creacion' => [
                'type' => 'DATETIME',
                'null' => false
            ]
        ]);

        $this->forge->addPrimaryKey('id_rol');
        $this->forge->createTable('roles');
    }

    public function down()
    {
        $this->forge->dropTable('roles');
    }
}
