<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TablaUsuario extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_usuario' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false
            ],
            'id_persona' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false
            ],
            'id_rol' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false
            ],
            'usuario' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'unique'     => true,
                'null'       => false
            ],
            'password' => [
                'type'       => 'VARBINARY',
                'constraint' => '100',
                'null'       => false
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'unique'     => true,
                'null'       => false
            ],
            'hash_key' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true
            ],
            'hash_expiry' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true
            ],
            'estado' => [
                'type' => 'BOOLEAN',
                'null' => false
            ],
            'fecha_creacion' => [
                'type' => 'DATETIME',
                'null' => false
            ]
        ]);

        $this->forge->addPrimaryKey('id_usuario');
        $this->forge->addForeignKey('id_persona', 'persona', 'id_persona', 'CASCADE', 'CASCADE', 'usuario_persona_foreign');
        $this->forge->addForeignKey('id_rol', 'roles', 'id_rol', 'CASCADE', 'CASCADE', 'usuario_rol_foreign');
        $this->forge->createTable('usuario');
    }

    public function down()
    {
        $this->forge->dropForeignKey('usuario', 'usuario_persona_foreign');
        $this->forge->dropForeignKey('usuario', 'usuario_rol_foreign');
        $this->forge->dropTable('usuario');
    }

}
