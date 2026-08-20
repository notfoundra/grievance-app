<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUpdateAt extends Migration
{
    public function up()
    {
        $this->forge->addColumn('grievance_updates', [
            'updated_at' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'null'       => true,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('grievance_updates', ['updated_at']);
    }
}
