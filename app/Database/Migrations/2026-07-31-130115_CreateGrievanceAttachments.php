<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateGrievanceAttachments extends Migration
{
    public function up()
    {
        $this->forge->addField([

            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'case_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
            ],

            'update_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => true,
                'comment'  => 'NULL jika attachment berasal dari case awal',
            ],

            'original_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],

            'stored_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],

            'file_path' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],

            'extension' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
            ],

            'mime_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],

            'file_size' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'comment'    => 'Byte',
            ],

            'created_at DATETIME NULL',
            'updated_at DATETIME NULL',

        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('case_id');
        $this->forge->addKey('update_id');

        // Uncomment kalau semua tabel sudah ada
        /*
        $this->forge->addForeignKey(
            'case_id',
            'grievance_cases',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->forge->addForeignKey(
            'update_id',
            'grievance_updates',
            'id',
            'CASCADE',
            'SET NULL'
        );
        */

        $this->forge->createTable('grievance_attachments');
    }

    public function down()
    {
        $this->forge->dropTable('grievance_attachments');
    }
}
