<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixQuisionerUniqueConstraint extends Migration
{
    public function up()
    {
        // Nama peserta seharusnya cuma perlu unik per sesi quisioner
        // (master_quisioner_id + name), bukan unik di seluruh tabel.
        // Kalau nama index unique lama di DB lo ternyata bukan "name",
        // sesuaikan nama index di baris DROP INDEX di bawah (cek phpMyAdmin).
        $this->db->query('ALTER TABLE quisioner DROP INDEX name');

        $this->forge->addUniqueKey(['master_quisioner_id', 'name']);
        $this->forge->processIndexes('quisioner');
    }

    public function down()
    {
        $this->db->query('ALTER TABLE quisioner DROP INDEX master_quisioner_id');
        $this->forge->addUniqueKey('name');
        $this->forge->processIndexes('quisioner');
    }
}
