<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MasterQuisioner extends Migration
{
    public function up()
    {
        $this->forge->addField([

            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'unique'     => true,
            ],
            'description' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'unique'     => true,
            ],
            'created_at DATETIME NULL',
            'updated_at DATETIME NULL',
            'deleted_at DATETIME NULL',

        ]);

        $this->forge->addKey('id', true);

        $this->forge->createTable('master_quisioner');
    }

    public function down()
    {
        $this->forge->dropTable('master_quisioner');
    }
}
