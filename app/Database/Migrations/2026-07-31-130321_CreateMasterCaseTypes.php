<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMasterCaseTypes extends Migration
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
                'constraint' => 150
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'is_active' => [
                'type' => 'BOOLEAN',
                'default' => 1
            ],
            'created_at DATETIME NULL',
            'updated_at DATETIME NULL',
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('master_case_types');
    }

    public function down()
    {
        $this->forge->dropTable('master_case_types');
    }
}
