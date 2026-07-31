<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use PhpOffice\PhpSpreadsheet\IOFactory;

class GrievanceSeeder extends Seeder
{
    public function run()
    {
        $file = ROOTPATH . 'samplecase.xlsx'; // pindahkan excel ke root project

        $spreadsheet = IOFactory::load($file);

        $rows = $spreadsheet->getActiveSheet()->toArray();

        array_shift($rows); // hapus header

        foreach ($rows as $row) {

            [
                $channel,
                $caseNumber,
                $receivedDate,
                $responseDate,
                $responseHour,
                $messageType,
                $message,
                $managementResponse,
                $department,
                $caseType,
                $correctiveAction,
                $status,
                $closedDate,
                $closureDays,
                $repeatedCase,
                $satisfaction,
                $pic,
                $year,
                $month,
                $monthNo
            ] = $row;

            $this->db->table('grievance_cases')->insert([

                'case_number' => 'GRV-' . $caseNumber,

                'site_id' => 1,

                'channel_id' => $this->findId(
                    'master_channels',
                    $channel
                ),

                'message_type_id' => $this->findId(
                    'master_message_types',
                    $messageType
                ),

                'case_type_id' => $this->findId(
                    'master_case_types',
                    trim($caseType)
                ),

                'department_id' => $this->findId(
                    'master_departments',
                    trim($department)
                ),

                'priority_id' => 2,

                'status_id' => $this->convertStatus($status),

                'received_date' => $receivedDate,

                'response_date' => $responseDate,

                'closed_date' => $closedDate,

                'target_response_date' => $responseDate,

                'target_closure_date' => $closedDate,

                'message' => $message,

                'management_response' => $managementResponse,

                'corrective_action' => $correctiveAction,

                'root_cause' => null,

                'confidential' => 'No',

                'repeated_case' => empty($repeatedCase) ? 'No' : 'Yes',

                'rating' => null,

                'satisfaction' => $satisfaction,

                'pic' => $pic,

                'status_id' => $this->convertStatus($status),

                'created_at' => $receivedDate,

                'updated_at' => date('Y-m-d H:i:s')

            ]);
        }
    }

    private function findId(string $table, ?string $name): int
    {
        if (empty($name)) {
            return 1;
        }

        $row = $this->db
            ->table($table)
            ->where('LOWER(name)', strtolower(trim($name)))
            ->get()
            ->getRow();

        return $row ? (int) $row->id : 1;
    }

    private function convertStatus(?string $status): int
    {
        $status = strtolower(trim((string) $status));

        return match ($status) {

            'open' => 1,

            'in progress' => 2,

            'close',
            'closed' => 3,

            'overdue' => 4,

            default => 1
        };
    }
}
