<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class OrdersDetail extends Migration
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
            'id_product' => [
                'type' => 'INT'
            ],
            'qty' => [
                'type' => 'INT',
                'constraint' => 4
            ],
            'subtotal' => [
                'type' => 'INT',
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
        $this->forge->createTable('orders_detail');
    }

    public function down()
    {
        $this->forge->dropTable('orders_detail');
    }
}