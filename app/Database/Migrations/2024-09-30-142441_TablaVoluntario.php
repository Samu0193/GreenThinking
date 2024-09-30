<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TablaVoluntario extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_voluntario' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false
            ],
            'id_persona' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'null'       => false
            ],
            'departamento_residencia' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false
            ],
            'municipio_residencia' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false
            ],
            'direccion' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'null'       => true
            ],
            'fecha_creacion' => [
                'type' => 'DATETIME',
                'null' => false
            ]
        ]);

        $this->forge->addPrimaryKey('id_voluntario');
        $this->forge->addForeignKey('id_persona', 'persona', 'id_persona', 'CASCADE', 'CASCADE', 'voluntario_persona_foreign');
        $this->forge->addForeignKey('departamento_residencia', 'departamento', 'id_departamento', 'CASCADE', 'CASCADE', 'voluntario_departamento_foreign');
        $this->forge->addForeignKey('municipio_residencia', 'municipio', 'id_municipio', 'CASCADE', 'CASCADE', 'voluntario_municipio_foreign');
        $this->forge->createTable('voluntario');
    }

    public function down()
    {
        $this->forge->dropForeignKey('voluntario', 'voluntario_persona_foreign');
        $this->forge->dropForeignKey('voluntario', 'voluntario_departamento_foreign');
        $this->forge->dropForeignKey('voluntario', 'voluntario_municipio_foreign');
        $this->forge->dropTable('voluntario');
    }
}
