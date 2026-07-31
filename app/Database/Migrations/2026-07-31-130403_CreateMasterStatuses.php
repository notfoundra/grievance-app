<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMasterStatuses extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 50
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'color' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true
            ],
            'sort_order' => [
                'type' => 'INT',
                'default' => 0
            ],
            'is_active' => [
                'type' => 'BOOLEAN',
                'default' => 1
            ],
            'created_at DATETIME NULL',
            'updated_at DATETIME NULL',
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('master_statuses');
    }

    public function down()
    {
        $this->forge->dropTable('master_statuses');
    }
}
