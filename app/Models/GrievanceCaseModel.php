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

            ->join('master_statuses', 'master_statuses.id = grievance_cases.status_id', 'left')

    }
    public function getDatatable(array $filter = [])
    {
        $builder = $this->baseQuery();

        if (!empty($filter['site'])) {
            $builder->where('site_id', $filter['site']);
        }

        if (!empty($filter['status'])) {
            $builder->where('status_id', $filter['status']);
        }

        if (!empty($filter['department'])) {
            $builder->where('department_id', $filter['department']);
        }

        if (!empty($filter['keyword'])) {

            $builder
                ->groupStart()
                ->like('case_no', $filter['keyword'])
                ->orLike('message', $filter['keyword'])
                ->groupEnd();
        }

        return $builder;
    }
    public function getDetail($id)
{
    return $this->baseQuery()
        ->where('grievance_cases.id',$id)
        ->get()
        ->getRowArray();
}

    public function getDashboardSummary()
{
    return [

        'total' => $this->countAll(),

        'open' => $this->where('status_id',1)->countAllResults(),

        'progress' => $this->where('status_id',2)->countAllResults(),

        'closed' => $this->where('status_id',3)->countAllResults(),

        'overdue' => $this->builder()
            ->where('closure_due_date <',date('Y-m-d'))
            ->where('status_id !=',3)
            ->countAllResults()

    ];
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

        ->where('closure_due_date <',date('Y-m-d'))

        ->where('status_id !=',3)

        ->orderBy('closure_due_date','ASC')

        ->get()

        ->getResultArray();
}
public function generateCaseNumber()
{
    $year = date('Y');

    $last = $this->builder()

        ->like('case_no', "GRV-{$year}", 'after')

        ->orderBy('id','DESC')

        ->get(1)

        ->getRowArray();

    $number = 1;

    if ($last) {

        $number = (int) substr($last['case_no'], -5);

        $number++;

    }

    return sprintf(
        'GRV-%s-%05d',
        $year,
        $number
    );
}
}
