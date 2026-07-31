<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DropColomPicId extends Migration
{
    public function up()
    {
        $this->forge->dropColumn('grievance_cases', 'pic_id');
    }

    public function down()
    {
        //
    }
}
