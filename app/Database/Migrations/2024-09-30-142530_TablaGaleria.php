<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TablaGaleria extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_galeria' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
                'null'           => false
            ],
            'ruta_archivo' => [
                'type'       => 'VARCHAR',
                'constraint' => '250',
                'null'       => false
            ],
            'usuario_crea' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false
            ],
            'fecha_creacion' => [
                'type' => 'DATETIME',
                'null' => false
            ]
        ]);

        $this->forge->addPrimaryKey('id_galeria');
        $this->forge->addForeignKey('usuario_crea', 'usuario', 'id_usuario', 'CASCADE', 'CASCADE', 'galeria_usuario_foreign');
        $this->forge->createTable('galeria');
    }

    public function down()
    {
        $this->forge->dropForeignKey('galeria', 'galeria_usuario_foreign');
        $this->forge->dropTable('galeria');
    }
}
