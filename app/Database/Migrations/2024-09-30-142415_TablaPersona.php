<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TablaPersona extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_persona' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false
            ],
            'nombres' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false
            ],
            'apellidos' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false
            ],
            'edad' => [
                'type'       => 'INT',
                'constraint' => 2,
                'null'       => false
            ],
            'dui' => [
                'type'       => 'VARCHAR',
                'constraint' => '12',
                'unique'     => true,
                'null'       => true
            ],
            'telefono' => [
                'type'       => 'VARCHAR',
                'constraint' => '10',
                'unique'     => true,
                'null'       => true
            ],
            'fecha_creacion' => [
                'type' => 'DATETIME',
                'null' => false
            ]
        ]);

        $this->forge->addPrimaryKey('id_persona');
        $this->forge->createTable('persona');
    }

    public function down()
    {
        $this->forge->dropTable('persona');
    }
}
