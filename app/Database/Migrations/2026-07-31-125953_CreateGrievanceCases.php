<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateGrievanceCases extends Migration
{
    public function up()
    {
        $this->forge->addField([

            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'case_number' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'unique'     => true,
            ],

            'site_id' => [
                'type' => 'INT',
                'null' => false,
            ],

            'channel_id' => [
                'type' => 'INT',
            ],

            'message_type_id' => [
                'type' => 'INT',
            ],

            'case_type_id' => [
                'type' => 'INT',
            ],

            'department_id' => [
                'type' => 'INT',
            ],

            'pic_id' => [
                'type' => 'INT',
            ],

            'priority_id' => [
                'type' => 'INT',
            ],

            'status_id' => [
                'type' => 'INT',
            ],

            'received_date' => [
                'type' => 'DATE',
            ],

            'target_response_date' => [
                'type' => 'DATE',
                'null' => true,
            ],

            'target_closure_date' => [
                'type' => 'DATE',
                'null' => true,
            ],

            'response_date' => [
                'type' => 'DATE',
                'null' => true,
            ],

            'closed_date' => [
                'type' => 'DATE',
                'null' => true,
            ],

            'message' => [
                'type' => 'TEXT',
            ],

            'management_response' => [
                'type' => 'TEXT',
                'null' => true,
            ],

            'root_cause' => [
                'type' => 'TEXT',
                'null' => true,
            ],

            'corrective_action' => [
                'type' => 'TEXT',
                'null' => true,
            ],

            'confidential' => [
                'type' => 'ENUM',
                'constraint' => ['Yes', 'No'],
                'default' => 'No',
            ],

            'repeated_case' => [
                'type' => 'ENUM',
                'constraint' => ['Yes', 'No'],
                'default' => 'No',
            ],

            'rating' => [
                'type' => 'INT',
                'constraint' => 1,
                'null' => true,
            ],

            'satisfaction' => [
                'type' => 'VARCHAR',
                'constraint' => 30,
                'null' => true,
            ],
            'pic' => [
                'type' => 'VARCHAR',
                'constraint' => 30,
                'null' => true,
            ],

            'created_at DATETIME NULL',
            'updated_at DATETIME NULL',
            'deleted_at DATETIME NULL',

        ]);

        $this->forge->addKey('id', true);

        $this->forge->createTable('grievance_cases');
    }

    public function down()
    {
        $this->forge->dropTable('grievance_cases');
    }
}
