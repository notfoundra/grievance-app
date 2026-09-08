<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddGenderToQuisioner extends Migration
{
    public function up()
    {
        $this->forge->addColumn('quisioner', [
            'gender' => [
                'type'       => 'ENUM',
                'constraint' => ['L', 'P'],
                'null'       => true,
                'after'      => 'name',
                'comment'    => 'L = Laki-laki, P = Perempuan, sesuai kolom GENDER di template import',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('quisioner', 'gender');
    }
}
