<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateGrievanceUpdates extends Migration
{
    public function up()
    {
        $this->forge->addField([

            'id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true
            ],

            'case_id' => [
                'type' => 'BIGINT',
                'unsigned' => true
            ],

            'status_id' => [
                'type' => 'INT'
            ],

            'note' => [
                'type' => 'TEXT',
                'null' => true
            ],

            'updated_by' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true
            ],

            'created_at DATETIME NULL',

        ]);

        $this->forge->addKey('id', true);

        $this->forge->addKey('case_id');

        $this->forge->createTable('grievance_updates');
    }

    public function down()
    {
        $this->forge->dropTable('grievance_updates');
    }
}
