<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAnonymousToGender extends Migration
{
    public function up()
    {
        $this->forge->modifyColumn('grievance_cases', [
            'gender' => [
                'name'       => 'gender',
                'type'       => 'ENUM',
                'constraint' => ['Male', 'Female', 'Anonymous'],
                'null'       => true,
                'comment'    => 'Jenis kelamin pemberi saran; Anonymous untuk yang tidak ingin mengungkapkan',
            ],
        ]);
    }

    public function down()
    {
        // Case yang sudah bernilai 'Anonymous' akan otomatis jadi '' (kosong)
        // kalau migration ini di-rollback, karena 'Anonymous' tidak lagi valid di enum.
        $this->forge->modifyColumn('grievance_cases', [
            'gender' => [
                'name'       => 'gender',
                'type'       => 'ENUM',
                'constraint' => ['Male', 'Female'],
                'null'       => true,
            ],
        ]);
    }
}
