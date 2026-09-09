<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTanggalQuisioner extends Migration
{
    public function up()
    {
        $this->forge->addColumn('quisioner', [
            'tanggal' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'null'       => true,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('quisioner', 'tanggal');
    }
}
