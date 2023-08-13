<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Products extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'id_category' => [
                'type' => 'INT'
            ],
            'slug' => [
                'type' => 'VARCHAR',
                'constraint' => 225
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 225
            ],
            'description' => [
                'type' => 'VARCHAR',
                'constraint' => 225
            ],
            'price' => [
                'type' => 'INT',
            ],
            'is_available' => [
                'type' => 'BOOLEAN',
                'default' => true
            ],
            'image' => [
                'type'=> 'VARCHAR',
                'constraint' => 225
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ]
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('products');
    }

    public function down()
    {
        $this->forge->dropTable('products');
    }
}