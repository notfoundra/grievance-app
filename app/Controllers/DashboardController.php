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
        $filter = [

            'site_id'       => $this->request->getGet('site_id'),
            'year'          => $this->request->getGet('year'),
            'month'         => $this->request->getGet('month'),
            'department_id' => $this->request->getGet('department_id'),
            'status_id'     => $this->request->getGet('status_id'),
            'case_type_id'  => $this->request->getGet('case_type_id'),

        ];

        return $this->response->setJSON(

            $this->caseModel->getDashboardSummary($filter)

        );
    }
}
