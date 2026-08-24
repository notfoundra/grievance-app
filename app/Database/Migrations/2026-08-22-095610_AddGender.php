<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddGender extends Migration
{
    public function up()
    {
        $this->forge->addColumn('grievance_cases', [
            'gender' => [
                'type'       => 'ENUM',
                'constraint' => ['Male', 'Female'],
                'null'       => true,
                'after'      => 'site_id',
                'comment'    => 'Jenis kelamin pemberi saran, dipakai di report FOR-HR-019',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('grievance_cases', 'gender');
    }
}
