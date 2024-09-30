<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TablaMunicipio extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_municipio' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
                'null'           => false
            ],
            'nombre_municipio' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false
            ],
            'id_departamento' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false
            ],
            'fecha_creacion' => [
                'type' => 'DATETIME',
                'null' => false
            ]
        ]);

        $this->forge->addPrimaryKey('id_municipio');
        $this->forge->addForeignKey('id_departamento', 'departamento', 'id_departamento', 'CASCADE', 'CASCADE', 'municipio_departamento_foreign');
        $this->forge->createTable('municipio');
    }

    public function down()
    {
        $this->forge->dropForeignKey('municipio', 'municipio_departamento_foreign'); // Cambia el nombre según tu convención
        $this->forge->dropTable('municipio');
    }

}
