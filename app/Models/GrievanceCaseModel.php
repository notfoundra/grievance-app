<?php

namespace App\Models;

class GrievanceCaseModel extends BaseModel
{
    protected $table = 'grievance_cases';

    protected $primaryKey = 'id';

    protected $useSoftDeletes = true;

    protected $deletedField = 'deleted_at';

    protected $allowedFields = [
        'case_number',
        'site_id',
        'channel_id',
        'message_type_id',
        'case_type_id',
        'department_id',
        'pic_id',
        'priority_id',
        'status_id',
        'received_date',
        'target_response_date',
        'target_closure_date',
        'response_date',
        'closed_date',
        'message',
        'management_response',
        'root_cause',
        'corrective_action',
        'confidential',
        'repeated_case',
        'rating',
        'satisfaction'
    ];

    protected function baseQuery()
    {
        return $this->builder()

            ->select('
            grievance_cases.*,

            master_sites.name site,
            master_channels.name channel,
            master_message_types.name message_type,
            master_case_types.name case_type,
            master_departments.name department,
            master_priorities.name priority,
            master_statuses.name status,

    
        ')

            ->join('master_sites', 'master_sites.id = grievance_cases.site_id', 'left')

            ->join('master_channels', 'master_channels.id = grievance_cases.channel_id', 'left')

            ->join('master_message_types', 'master_message_types.id = grievance_cases.message_type_id', 'left')

            ->join('master_case_types', 'master_case_types.id = grievance_cases.case_type_id', 'left')

            ->join('master_departments', 'master_departments.id = grievance_cases.department_id', 'left')

            ->join('master_priorities', 'master_priorities.id = grievance_cases.priority_id', 'left')

            ->join('master_statuses', 'master_statuses.id = grievance_cases.status_id', 'left');
    }
    public function getDatatable()
    {
        return $this->db
            ->table('grievance_cases gc')
            ->select('
        gc.id,
        gc.case_number,
        gc.received_date,
        gc.target_closure_date,
        gc.pic,
        md.name AS department,
        mc.name AS case_type,
        mp.name AS priority,
        ms.name AS status
    ')
            ->join('master_departments md', 'md.id=gc.department_id', 'left')
            ->join('master_case_types mc', 'mc.id=gc.case_type_id', 'left')
            ->join('master_priorities mp', 'mp.id=gc.priority_id', 'left')
            ->join('master_statuses ms', 'ms.id=gc.status_id', 'left')
            ->get()
            ->getResultArray();
    }
    public function getDetail($id)
    {
        return $this->db->table('grievance_cases gc')

            ->select('
            gc.*,

            ms.name site,
            md.name department,
            mc.name case_type,
            mp.name priority,
            mst.name status,
            mch.name channel,
            mt.name message_type
        ')

            ->join('master_sites ms', 'ms.id=gc.site_id', 'left')
            ->join('master_departments md', 'md.id=gc.department_id', 'left')
            ->join('master_case_types mc', 'mc.id=gc.case_type_id', 'left')
            ->join('master_priorities mp', 'mp.id=gc.priority_id', 'left')
            ->join('master_statuses mst', 'mst.id=gc.status_id', 'left')
            ->join('master_channels mch', 'mch.id=gc.channel_id', 'left')
            ->join('master_message_types mt', 'mt.id=gc.message_type_id', 'left')

            ->where('gc.id', $id)

            ->get()

            ->getRowArray();
    }
    private function applyFilter($builder, array $filter)
    {
        if (!empty($filter['site_id'])) {
            $builder->where('site_id', $filter['site_id']);
        }

        if (!empty($filter['year'])) {
            $builder->where("YEAR(received_date)", (int)$filter['year'], false);
        }

        if (!empty($filter['month'])) {
            $builder->where('MONTH(received_date)', $filter['month']);
        }

        if (!empty($filter['department_id'])) {
            $builder->where('department_id', $filter['department_id']);
        }

        if (!empty($filter['status_id'])) {
            $builder->where('status_id', $filter['status_id']);
        }

        if (!empty($filter['case_type_id'])) {
            $builder->where('case_type_id', $filter['case_type_id']);
        }

        return $builder;
    }
    public function getDashboardSummary(array $filter = []): array
    {
        $db = db_connect();

        /*
    |--------------------------------------------------------------------------
    | SUMMARY
    |--------------------------------------------------------------------------
    */

        $summary = [

            'total' => $this->applyFilter(
                $db->table($this->table),
                $filter
            )->countAllResults(),

            'open' => $this->applyFilter(
                $db->table($this->table),
                $filter
            )->where('status_id', 1)->countAllResults(),

            'progress' => $this->applyFilter(
                $db->table($this->table),
                $filter
            )->where('status_id', 2)->countAllResults(),

            'closed' => $this->applyFilter(
                $db->table($this->table),
                $filter
            )->where('status_id', 3)->countAllResults(),

            'overdue' => $this->applyFilter(
                $db->table($this->table),
                $filter
            )
                ->where('status_id !=', 3)
                ->where('target_closure_date <', date('Y-m-d'))
                ->countAllResults(),

            'response' => '0 Day'

        ];

        /*
    |--------------------------------------------------------------------------
    | MONTHLY TREND
    |--------------------------------------------------------------------------
    */

        $trend = [];

        for ($i = 1; $i <= 12; $i++) {

            $builder = $this->applyFilter(
                $db->table($this->table),
                $filter
            );

            $builder
                ->where('MONTH(received_date)', $i)
                ->where('YEAR(received_date)', !empty($filter['year']) ? $filter['year'] : date('Y'));

            $trend[] = $builder->countAllResults();
        }

        /*
    |--------------------------------------------------------------------------
    | DEPARTMENT
    |--------------------------------------------------------------------------
    */

        $builder = $db->table('grievance_cases gc')
            ->select('md.name, COUNT(*) total')
            ->join('master_departments md', 'md.id = gc.department_id', 'left');

        if (!empty($filter['site_id'])) {
            $builder->where('gc.site_id', $filter['site_id']);
        }

        if (!empty($filter['year'])) {
            $builder->where('YEAR(gc.received_date)', $filter['year']);
        }

        if (!empty($filter['month'])) {
            $builder->where('MONTH(gc.received_date)', $filter['month']);
        }

        if (!empty($filter['status_id'])) {
            $builder->where('gc.status_id', $filter['status_id']);
        }

        if (!empty($filter['case_type_id'])) {
            $builder->where('gc.case_type_id', $filter['case_type_id']);
        }

        $departmentRows = $builder
            ->groupBy('gc.department_id')
            ->orderBy('total', 'DESC')
            ->limit(5)
            ->get()
            ->getResultArray();

        $department = [
            'labels' => [],
            'data' => []
        ];

        foreach ($departmentRows as $row) {

            $department['labels'][] = $row['name'];
            $department['data'][] = (int)$row['total'];
        }

        /*
    |--------------------------------------------------------------------------
    | CASE TYPE
    |--------------------------------------------------------------------------
    */

        $builder = $db->table('grievance_cases gc')
            ->select('mc.name, COUNT(*) total')
            ->join('master_case_types mc', 'mc.id = gc.case_type_id', 'left');

        if (!empty($filter['site_id'])) {
            $builder->where('gc.site_id', $filter['site_id']);
        }

        if (!empty($filter['year'])) {
            $builder->where('YEAR(gc.received_date)', $filter['year']);
        }

        if (!empty($filter['month'])) {
            $builder->where('MONTH(gc.received_date)', $filter['month']);
        }

        if (!empty($filter['department_id'])) {
            $builder->where('gc.department_id', $filter['department_id']);
        }

        if (!empty($filter['status_id'])) {
            $builder->where('gc.status_id', $filter['status_id']);
        }

        $typeRows = $builder
            ->groupBy('gc.case_type_id')
            ->orderBy('total', 'DESC')
            ->get()
            ->getResultArray();

        $caseType = [
            'labels' => [],
            'data' => []
        ];

        foreach ($typeRows as $row) {

            $caseType['labels'][] = $row['name'];
            $caseType['data'][] = (int)$row['total'];
        }

        /*
    |--------------------------------------------------------------------------
    | RECENT CASE
    |--------------------------------------------------------------------------
    */

        $builder = $db->table('grievance_cases gc')
            ->select('
            gc.case_number,
            md.name AS department,
            mc.name AS case_type,
            ms.name AS status,
            gc.pic,
            gc.target_closure_date
        ')
            ->join('master_departments md', 'md.id = gc.department_id', 'left')
            ->join('master_case_types mc', 'mc.id = gc.case_type_id', 'left')
            ->join('master_statuses ms', 'ms.id = gc.status_id', 'left');

        $this->applyFilter($builder, $filter);

        $recent = $builder
            ->orderBy('gc.received_date', 'DESC')
            ->limit(8)
            ->get()
            ->getResultArray();

        /*
    |--------------------------------------------------------------------------
    | OVERDUE
    |--------------------------------------------------------------------------
    */

        $builder = $db->table('grievance_cases gc')
            ->select('
            gc.case_number,
            md.name AS department,
            DATEDIFF(CURDATE(), gc.target_closure_date) days
        ')
            ->join('master_departments md', 'md.id = gc.department_id', 'left');

        $this->applyFilter($builder, $filter);

        $overdue = $builder
            ->where('gc.status_id !=', 3)
            ->where('gc.target_closure_date <', date('Y-m-d'))
            ->orderBy('gc.target_closure_date')
            ->limit(5)
            ->get()
            ->getResultArray();

        /*
|--------------------------------------------------------------------------
| SATISFACTION
|--------------------------------------------------------------------------
*/

        $builder = $db->table($this->table);

        $this->applyFilter($builder, $filter);

        $rows = $builder

            ->select('satisfaction, COUNT(*) total')

            ->where('satisfaction IS NOT NULL', null, false)

            ->groupBy('satisfaction')

            ->orderBy('total', 'DESC')

            ->get()

            ->getResultArray();

        $satisfaction = [

            'labels' => [],

            'data' => []

        ];

        foreach ($rows as $row) {

            $satisfaction['labels'][] = $row['satisfaction'];

            $satisfaction['data'][] = (int)$row['total'];
        }
        return [

            'summary'      => $summary,

            'trend'        => $trend,

            'department'   => $department,

            'case_type'    => $caseType,

            'satisfaction' => $satisfaction,

            'recent'       => $recent,

            'overdue'      => $overdue

        ];
    }
    public function createCase($request)
    {
        $data = [
            'case_number'         => $this->generateCaseNumber(),
            'site_id'              => $request->getPost('site_id'),
            'department_id'        => $request->getPost('department_id'),
            'channel_id'           => $request->getPost('channel_id'),
            'message_type_id'      => $request->getPost('message_type_id'),
            'case_type_id'         => $request->getPost('case_type_id'),
            'priority_id'          => $request->getPost('priority_id'),
            'status_id'            => 1, // Open
            'received_date'        => date('Y-m-d'),
            'target_response_date' => $request->getPost('target_response_date'),
            'target_closure_date'  => $request->getPost('target_closure_date'),
            'message'              => $request->getPost('message'),
            'management_response'  => null,
            'root_cause'           => null,
            'corrective_action'    => null,
            'confidential'         => $request->getPost('confidential') ? 'Yes' : 'No',
            'repeated_case'        => $request->getPost('repeated_case') ? 'Yes' : 'No',
            'pic'                  => null,
        ];

        $this->insert($data);
        return $this->getInsertID();
    }

    public function getMonthlyTrend($year)
    {
        return $this->builder()

            ->select("
            MONTH(received_date) month,
            COUNT(*) total
        ")

            ->where('YEAR(received_date)', $year)

            ->groupBy('MONTH(received_date)')

            ->orderBy('MONTH(received_date)')

            ->get()

            ->getResultArray();
    }
    public function getOverdueCases()
    {
        return $this->baseQuery()

            ->where('closure_due_date <', date('Y-m-d'))

            ->where('status_id !=', 3)

            ->orderBy('closure_due_date', 'ASC')

            ->get()

            ->getResultArray();
    }
    public function generateCaseNumber()
    {
        $year = date('Y');

        $last = $this->builder()

            ->like('case_number', "GRV-{$year}", 'after')

            ->orderBy('id', 'DESC')

            ->get(1)

            ->getRowArray();

        $number = 1;

        if ($last) {

            $number = (int) substr($last['case_number'], -5);

            $number++;
        }

        return sprintf(
            'GRV-%s-%05d',
            $year,
            $number
        );
    }
}
