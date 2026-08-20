<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\GrievanceCaseModel;

class DashboardController extends BaseController
{
    protected GrievanceCaseModel $caseModel;

    public function __construct()
    {
        $this->caseModel = new GrievanceCaseModel();
    }

    public function summary()
    {
        $dateFrom = $this->request->getGet('date_from');
        $dateTo   = $this->request->getGet('date_to');

        // Default: 6 bulan terakhir, biar gak narik seluruh histori tiap dashboard dibuka
        if (empty($dateFrom) || empty($dateTo)) {
            $dateFrom = date('Y-m-01', strtotime('-5 months'));
            $dateTo   = date('Y-m-d');
        }

        $filter = [
            'site_id'       => scoped_site_id($this->request->getGet('site_id')),
            'date_from'     => $dateFrom,
            'date_to'       => $dateTo,
            'department_id' => $this->request->getGet('department_id'),
            'status_id'     => $this->request->getGet('status_id'),
            'case_type_id'  => $this->request->getGet('case_type_id'),
        ];

        return $this->response->setJSON(
            $this->caseModel->getDashboardSummary($filter)
        );
    }
}
