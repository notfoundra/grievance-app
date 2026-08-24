<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Menyelaraskan master_case_types dengan 17 "Kategori Saran" pada formulir
 * FOR-HR-019 (Tanggapan Saran-Saran Anda), dan menambahkan kolom sort_order
 * supaya urutan kategori pada report Excel konsisten dengan urutan di form.
 *
 * Id kategori yang sudah ada TIDAK dihapus/diacak ulang, hanya di-rename bila
 * ejaannya berbeda dari form, supaya referensi grievance_cases.case_type_id
 * yang sudah ada tetap valid.
 */
class UpdateMasterCaseTypesFormAlignment extends Migration
{
    /**
     * Mapping nama lama (hasil seed sebelumnya) -> nama sesuai form.
     *
     * @var array<string, string>
     */
    private array $renames = [
        'Occupational Health, Safety & Environment'          => 'Occupational HSE',
        'Wages & Incentives'                                  => 'Wages and incentives',
        'Working Hours'                                       => 'Working hours',
        'Recruitment & Contract'                              => 'Recruitment and contract',
        'Personal Change & Performance Appraisal'             => 'Personal change and performance appraisal',
        'Disciplinary Action'                                 => 'Disciplinary actions',
        'Workplace Disputes'                                  => 'Workplace disputes',
        'Communication & Grievance Channels'                  => 'Communication and grievance channels',
        'Freedom of association and workers representation'  => 'Freedom of association and worker representations',
        'Personal Affairs'                                    => 'Personal affairs',
        'Others'                                               => 'Other',
    ];

    /**
     * Urutan resmi 17 kategori sesuai form (dipakai untuk sort_order).
     *
     * @var array<string, int>
     */
    private array $sortOrder = [
        'Occupational HSE'                                   => 1,
        'Wages and incentives'                               => 2,
        'Benefits'                                            => 3,
        'General Facilities'                                  => 4,
        'Harassment and abuse'                                => 5,
        'Working hours'                                       => 6,
        'Production'                                          => 7,
        'Recruitment and contract'                            => 8,
        'Termination and resignation'                         => 9,
        'Personal change and performance appraisal'           => 10,
        'Disciplinary actions'                                => 11,
        'Workplace disputes'                                  => 12,
        'Communication and grievance channels'                => 13,
        'Freedom of association and worker representations'   => 14,
        'Personal affairs'                                    => 15,
        'Other'                                                => 16,
        'Junk'                                                 => 17,
    ];

    public function up()
    {
        if (! $this->db->fieldExists('sort_order', 'master_case_types')) {
            $this->forge->addColumn('master_case_types', [
                'sort_order' => [
                    'type'    => 'INT',
                    'default' => 0,
                    'after'   => 'description',
                ],
            ]);
        }

        // 1) Rename kategori lama supaya ejaannya sama persis dengan form.
        foreach ($this->renames as $old => $new) {
            $this->db->table('master_case_types')
                ->where('name', $old)
                ->update(['name' => $new, 'updated_at' => date('Y-m-d H:i:s')]);
        }

        // 2) Tambahkan kategori yang belum ada sama sekali di sistem.
        $existingNames = array_column(
            $this->db->table('master_case_types')->select('name')->get()->getResultArray(),
            'name'
        );

        foreach (array_keys($this->sortOrder) as $name) {
            if (! in_array($name, $existingNames, true)) {
                $this->db->table('master_case_types')->insert([
                    'name'       => $name,
                    'is_active'  => 1,
                    'sort_order' => $this->sortOrder[$name],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }

        // 3) Sinkronkan sort_order semua kategori supaya urutannya persis form.
        foreach ($this->sortOrder as $name => $order) {
            $this->db->table('master_case_types')
                ->where('name', $name)
                ->update(['sort_order' => $order]);
        }
    }

    public function down()
    {
        foreach (array_flip($this->renames) as $new => $old) {
            $this->db->table('master_case_types')
                ->where('name', $new)
                ->update(['name' => $old, 'updated_at' => date('Y-m-d H:i:s')]);
        }

        $this->db->table('master_case_types')
            ->whereIn('name', ['Harassment and abuse', 'Termination and resignation', 'Junk'])
            ->delete();

        if ($this->db->fieldExists('sort_order', 'master_case_types')) {
            $this->forge->dropColumn('master_case_types', 'sort_order');
        }
    }
}
