<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUpdateDateToGrievanceUpdates extends Migration
{
    public function up()
    {
        $this->forge->addColumn('grievance_updates', [
            'update_date' => [
                'type'    => 'DATE',
                'null'    => true,
                'after'   => 'status_id',
                'comment' => 'Tanggal kejadian follow-up (dipilih user), beda dari created_at (kapan dicatat ke sistem)',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('grievance_updates', 'update_date');
    }
}
