<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MasterDataSeeder extends Seeder
{
    public function run()
    {
        /*
        |--------------------------------------------------------------------------
        | MASTER SITES
        |--------------------------------------------------------------------------
        */
        $this->db->table('master_sites')->insertBatch([
            [
                'name' => 'Cijerah',
                'description' => 'PT Kahatex Cijerah',
                'is_active' => 1
            ],
            [
                'name' => 'Majalaya',
                'description' => 'PT Kahatex Majalaya',
                'is_active' => 1
            ]
        ]);

        /*
        |--------------------------------------------------------------------------
        | MASTER CHANNELS
        |--------------------------------------------------------------------------
        */
        $this->db->table('master_channels')->insertBatch([
            ['name' => 'Suggestion Box', 'description' => 'Kotak Saran', 'is_active' => 1],
            ['name' => 'WhatsApp / Hotline', 'description' => 'WhatsApp', 'is_active' => 1],
            ['name' => 'Direct (Face to Face)', 'description' => 'Tatap Muka', 'is_active' => 1],
            ['name' => 'Worker Representative', 'description' => 'Perwakilan Pekerja', 'is_active' => 1],
        ]);

        /*
        |--------------------------------------------------------------------------
        | MESSAGE TYPES
        |--------------------------------------------------------------------------
        */
        $this->db->table('master_message_types')->insertBatch([
            ['name' => 'Ask', 'description' => 'Pertanyaan', 'is_active' => 1],
            ['name' => 'Suggestion', 'description' => 'Saran', 'is_active' => 1],
            ['name' => 'Report', 'description' => 'Laporan', 'is_active' => 1],
        ]);

        /*
        |--------------------------------------------------------------------------
        | CASE TYPES
        |--------------------------------------------------------------------------
        */
        $this->db->table('master_case_types')->insertBatch([
            ['name' => 'Occupational Health, Safety & Environment', 'is_active' => 1],
            ['name' => 'Wages & Incentives', 'is_active' => 1],
            ['name' => 'Benefits', 'is_active' => 1],
            ['name' => 'General Facilities', 'is_active' => 1],
            ['name' => 'Working Hours', 'is_active' => 1],
            ['name' => 'Production', 'is_active' => 1],
            ['name' => 'Recruitment & Contract', 'is_active' => 1],
            ['name' => 'Personal Change & Performance Appraisal', 'is_active' => 1],
            ['name' => 'Disciplinary Action', 'is_active' => 1],
            ['name' => 'Workplace Disputes', 'is_active' => 1],
            ['name' => 'Communication & Grievance Channels', 'is_active' => 1],
            ['name' => 'Freedom of association and workers representation', 'is_active' => 1],
            ['name' => 'Personal Affairs', 'is_active' => 1],
            ['name' => 'Others', 'is_active' => 1],

        ]);

        /*
        |--------------------------------------------------------------------------
        | DEPARTMENTS
        |--------------------------------------------------------------------------
        */
        $this->db->table('master_departments')->insertBatch([
            ['name' => 'HRD', 'is_active' => 1],
            ['name' => 'Production', 'is_active' => 1],
            ['name' => 'GA', 'is_active' => 1],
            ['name' => 'Engineering', 'is_active' => 1],
            ['name' => 'HSE & Compliance', 'is_active' => 1],
            ['name' => 'IT', 'is_active' => 1],
            ['name' => 'PPIC', 'is_active' => 1],
            ['name' => 'Security', 'is_active' => 1],
            ['name' => 'Lean', 'is_active' => 1],
            ['name' => 'Union/Worker Representative', 'is_active' => 1],
            ['name' => 'Others', 'is_active' => 1],
        ]);

        /*
        |--------------------------------------------------------------------------
        | PRIORITIES
        |--------------------------------------------------------------------------
        */
        $this->db->table('master_priorities')->insertBatch([
            ['name' => 'Urgent', 'description' => 'U', 'is_active' => 1],
            ['name' => 'Medium', 'description' => 'Medium Priority', 'is_active' => 1],
            ['name' => 'Low', 'description' => 'Low Priority', 'is_active' => 1],
        ]);

        /*
        |--------------------------------------------------------------------------
        | STATUSES
        |--------------------------------------------------------------------------
        */
        $this->db->table('master_statuses')->insertBatch([
            [
                'name' => 'Open',
                'description' => 'Waiting for response',
                'color' => 'danger',
                'sort_order' => 1,
                'is_active' => 1
            ],
            [
                'name' => 'In Progress',
                'description' => 'Currently handled',
                'color' => 'warning',
                'sort_order' => 2,
                'is_active' => 1
            ],
            [
                'name' => 'Closed',
                'description' => 'Case finished',
                'color' => 'success',
                'sort_order' => 3,
                'is_active' => 1
            ],
            [
                'name' => 'Overdue',
                'description' => 'Past due date',
                'color' => 'secondary',
                'sort_order' => 4,
                'is_active' => 1
            ],
        ]);
    }
}
