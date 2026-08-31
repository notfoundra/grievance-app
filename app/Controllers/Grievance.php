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
use App\Models\UserModel;
use App\Models\MasterChannelModel;

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
        $siteModel     = new MasterSiteModel();
        $deptModel     = new MasterDepartmentModel();
        $caseTypeModel = new MasterCaseTypeModel();
        $statusModel   = new MasterStatusModel();
        $channelModel  = new MasterChannelModel();

        $sites = $siteModel->where('is_active', 1)->findAll();

        if (! has_role(\App\Models\UserModel::ROLE_ADMIN)) {
            $sites = array_values(array_filter($sites, fn($s) => (int) $s['id'] === (int) current_user()['site_id']));
        }

        $data = [
            'sites'       => $sites,
            'departments' => $deptModel->where('is_active', 1)->orderBy('name')->findAll(),
            'caseTypes'   => $caseTypeModel->where('is_active', 1)->orderBy('name')->findAll(),
            'statuses'    => $statusModel->where('is_active', 1)->orderBy('sort_order')->findAll(),
            'channels'    => $channelModel->where('is_active', 1)->orderBy('name')->findAll(),
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
    public function followUp()
    {
        $siteModel = new MasterSiteModel();
        $sites = $siteModel->where('is_active', 1)->findAll();

        if (! has_role(\App\Models\UserModel::ROLE_ADMIN)) {
            $sites = array_values(array_filter(
                $sites,
                fn($s) => (int) $s['id'] === (int) current_user()['site_id']
            ));
        }

        $data = [
            'sites'       => $sites,
            'departments' => (new MasterDepartmentModel())->where('is_active', 1)->orderBy('name')->findAll(),
            'caseTypes'   => (new MasterCaseTypeModel())->where('is_active', 1)->orderBy('name')->findAll(),
            'priorities'  => (new MasterPriorityModel())->where('is_active', 1)->findAll(),
        ];

        return view('grievance/follow_up', $data);
    }

    public function followUpData()
    {
        $filter = [
            'site_id'        => scoped_site_id($this->request->getGet('site_id')),
            'year'           => $this->request->getGet('year') ?: date('Y'),
            'department_id'  => $this->request->getGet('department_id'),
            'case_type_id'   => $this->request->getGet('case_type_id'),
            'priority_id'    => $this->request->getGet('priority_id'),
            'include_closed' => $this->request->getGet('include_closed'),
        ];

        return $this->response->setJSON(
            $this->caseModel->getFollowUpBoard($filter)
        );
    }
    public function masterdata()
    {
        $siteModel = new MasterSiteModel();
        $sites = $siteModel->where('is_active', 1)->findAll();

        if (! has_role(\App\Models\UserModel::ROLE_ADMIN)) {
            $sites = array_values(array_filter(
                $sites,
                fn($s) => (int) $s['id'] === (int) current_user()['site_id']
            ));
        }

        $data = [
            'sites'       => $sites,
            'departments' => (new MasterDepartmentModel())->where('is_active', 1)->orderBy('name')->findAll(),
            'caseTypes'   => (new MasterCaseTypeModel())->where('is_active', 1)->orderBy('name')->findAll(),
            'priorities'  => (new MasterPriorityModel())->where('is_active', 1)->findAll(),
        ];
        return view('grievance/masterdata', $data);
    }
    public function users()
    {

        $data = [
            'user' => (new UserModel())->orderBy('name')->findAll(),
        ];
        return view('grievance/user', $data);
    }
}
