<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Quisioner extends Migration
{
    public function up()
    {
        $this->forge->addField([

            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'master_quisioner_id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
            ],

            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'unique'     => true,
            ],
            'pretest' => [
                'type'       => 'int',
                'constraint' => 30,
            ],
            'posttest' => [
                'type'       => 'int',
                'constraint' => 30,
            ],
            'keterangan' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
            ],
            'created_at DATETIME NULL',
            'updated_at DATETIME NULL',
            'deleted_at DATETIME NULL',

        ]);

        $this->forge->addKey('id', true);

        $this->forge->createTable('quisioner');
    }

    public function down()
    {
        $this->forge->dropTable('quisioner');
    }
}
