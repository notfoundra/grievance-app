<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\GrievanceCaseModel;
use App\Models\GrievanceAttachmentModel;
use App\Models\GrievanceUpdateModel;
use App\Models\MasterSiteModel;
use App\Models\MasterDepartmentModel;
use App\Models\MasterCaseTypeModel;
use App\Models\MasterStatusModel;
use App\Models\MasterPriorityModel;

class Grievance extends BaseController
{
    protected GrievanceCaseModel $caseModel;
    protected GrievanceAttachmentModel $attachmentModel;
    protected GrievanceUpdateModel $updateModel;

    public function __construct()
    {
        $this->caseModel       = new GrievanceCaseModel();
        $this->attachmentModel = new GrievanceAttachmentModel();
        $this->updateModel     = new GrievanceUpdateModel();
    }

    public function index()
    {
        $siteModel       = new MasterSiteModel();
        $deptModel       = new MasterDepartmentModel();
        $caseTypeModel   = new MasterCaseTypeModel();
        $statusModel     = new MasterStatusModel();
        $data = [

            'sites'       => $siteModel->where('is_active', 1)->findAll(),

            'departments' => $deptModel->where('is_active', 1)->orderBy('name')->findAll(),

            'caseTypes'   => $caseTypeModel->where('is_active', 1)->orderBy('name')->findAll(),

            'statuses'    => $statusModel->where('is_active', 1)->orderBy('sort_order')->findAll()

        ];

        return view('grievance/index', $data);
    }
    public function caseLog()
    {
        $data = [

            'sites' => (new MasterSiteModel())
                ->where('is_active', 1)
                ->findAll(),

            'departments' => (new MasterDepartmentModel())
                ->where('is_active', 1)
                ->orderBy('name')
                ->findAll(),

            'priorities' => (new MasterPriorityModel())
                ->where('is_active', 1)
                ->findAll(),

            'statuses' => (new MasterStatusModel())
                ->where('is_active', 1)
                ->orderBy('sort_order')
                ->findAll(),

        ];


        return view('grievance/case_log', $data);
    }
}
