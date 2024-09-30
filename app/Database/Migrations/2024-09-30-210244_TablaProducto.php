<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TablaProducto extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_producto' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false
            ],
            'ruta_archivo' => [
                'type'       => 'VARCHAR',
                'constraint' => '250',
                'null'       => false
            ],
            'nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false
            ],
            'descripcion' => [
                'type'       => 'VARCHAR',
                'constraint' => '250',
                'null'       => false
            ],
            'precio' => [
                'type'       => 'DECIMAL',
                'constraint' => '18, 2',
                'null'       => false
            ],
            'estado' => [
                'type' => 'BOOLEAN',
                'null' => false
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

        $this->forge->addPrimaryKey('id_producto');
        $this->forge->addForeignKey('usuario_crea', 'usuario', 'id_usuario', 'CASCADE', 'CASCADE', 'producto_usuario_foreign');
        $this->forge->createTable('producto');
    }

    public function down()
    {
        $this->forge->dropForeignKey('producto', 'producto_usuario_foreign');
        $this->forge->dropTable('producto');
    }
}
