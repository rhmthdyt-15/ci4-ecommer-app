<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Orders extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'id_user' => [
                'type' => 'INT'
            ],
            'date' => [
                'type' => 'INT'
            ],
            'invoice' => [
                'type' => 'VARCHAR',
                'constraint' => 100
              
            ],
            'total' => [
                'type' => 'INT',
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 225
            ],
            'address' => [
                'type' => 'VARCHAR',
                'constraint' => 225
            ],
            'phone' => [
                'type' => 'VARCHAR',
                'constraint' => 15
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['waiting', 'paid', 'delivered', 'cancel'],
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
        $this->forge->createTable('orders');
    }

    public function down()
    {
        $this->forge->dropTable('orders');
    }
}