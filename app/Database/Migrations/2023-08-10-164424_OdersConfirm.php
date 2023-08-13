<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class OdersConfirm extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'id_orders' => [
                'type' => 'INT'
            ],
            'account_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'account_number' => [
                'type' => 'VARCHAR',
                'constraint' => 50
            ],
            'nominal' => [
                'type' => 'INT',
            ],
            'note' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'image' => [
                'type' => 'VARCHAR',
                'constraint' => 255
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
        $this->forge->createTable('orders_confirm');
    }

    public function down()
    {
        $this->forge->dropTable('orders_confirm');
    }
}