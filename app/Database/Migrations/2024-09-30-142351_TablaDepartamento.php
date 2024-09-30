<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TablaDepartamento extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_departamento' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
                'null'           => false
            ],
            'nombre_departamento' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false
            ],
            'fecha_creacion' => [
                'type' => 'DATETIME',
                'null' => false
            ]
        ]);

        $this->forge->addPrimaryKey('id_departamento');
        $this->forge->createTable('departamento');
    }

    public function down()
    {
        $this->forge->dropTable('departamento');
    }
}
