<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddImportTrackingToGrievanceCases extends Migration
{
    public function up()
    {
        $this->forge->addColumn('grievance_cases', [
            'source' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'null'       => true,
                'after'      => 'case_number',
                'comment'    => 'null = manual input, "wovo_import" = dari import Excel WOVO',
            ],
            'external_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
                'after'      => 'source',
                'comment'    => 'Case Id asli dari WOVO, dipakai cegah duplikat saat re-import',
            ],
        ]);

        $this->forge->addKey(['source', 'external_id']);
        $this->forge->processIndexes('grievance_cases');
    }

    public function down()
    {
        $this->forge->dropColumn('grievance_cases', ['source', 'external_id']);
    }
}
