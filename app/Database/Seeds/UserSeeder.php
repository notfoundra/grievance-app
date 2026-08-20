<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        $this->db->table('users')->insertBatch([
            [
                'name'       => 'Administrator',
                'username'   => 'admin',
                'email'      => 'admin@kahatex.co.id',
                'password'   => password_hash('admin123', PASSWORD_DEFAULT),
                'role'       => 'admin',
                'site_id'    => null,
                'is_active'  => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name'       => 'PIC Socks',
                'username'   => 'socks',
                'email'      => 'socks@kahatex.co.id',
                'password'   => password_hash('socks123', PASSWORD_DEFAULT),
                'role'       => 'socks',
                'site_id'    => 1, // sesuaikan id master_sites
                'is_active'  => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name'       => 'PIC Garmen',
                'username'   => 'garmen',
                'email'      => 'garmen@kahatex.co.id',
                'password'   => password_hash('garmen123', PASSWORD_DEFAULT),
                'role'       => 'garmen',
                'site_id'    => 2, // sesuaikan id master_sites
                'is_active'  => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
