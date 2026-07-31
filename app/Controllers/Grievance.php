<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\GrievanceCaseModel;
use App\Models\GrievanceAttachmentModel;
use App\Models\GrievanceUpdateModel;

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
        return view('grievance_main');
    }
    public function datatable()
    {
        $filter = [

            'site'       => $this->request->getGet('site'),

            'status'     => $this->request->getGet('status'),

            'department' => $this->request->getGet('department'),

            'keyword'    => $this->request->getGet('search'),

        ];

        $data = $this->caseModel
            ->getDatatable($filter)
            ->get()
            ->getResultArray();

        return $this->response->setJSON([
            'data' => $data
        ]);
    }
    public function detail($id)
    {
        $case = $this->caseModel->getDetail($id);

        if (!$case) {

            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return $this->response->setJSON($case);
    }
    public function dashboardSummary()
    {
        return $this->response->setJSON(

            $this->caseModel->getDashboardSummary()

        );
    }
    public function monthlyTrend()
    {
        $year = $this->request->getGet('year') ?? date('Y');

        return $this->response->setJSON(

            $this->caseModel->getMonthlyTrend($year)

        );
    }
    public function overdueCases()
    {
        return $this->response->setJSON(

            $this->caseModel->getOverdueCases()

        );
    }
    public function store()
    {
        $rules = [

            'site_id' => 'required',

            'channel_id' => 'required',

            'message_type_id' => 'required',

            'case_type_id' => 'required',

            'department_id' => 'required',

            'priority_id' => 'required',

            'message' => 'required'

        ];

        if (!$this->validate($rules)) {

            return $this->response
                ->setStatusCode(422)
                ->setJSON([
                    'status' => false,
                    'errors' => $this->validator->getErrors()
                ]);
        }

        $data = $this->request->getPost();

        $data['case_no'] = $this->caseModel->generateCaseNumber();

        $data['status_id'] = 1;

        $data['created_by'] = session()->get('user_id');

        $this->caseModel->insert($data);

        return $this->response->setJSON([
            'status' => true,
            'message' => 'Case created successfully.'
        ]);
    }
    public function updateStatus($id)
    {
        $this->caseModel->update($id, [

            'status_id' => $this->request->getPost('status_id')

        ]);

        return $this->response->setJSON([
            'status' => true
        ]);
    }
    public function delete($id)
    {
        $this->caseModel->delete($id);

        return $this->response->setJSON([
            'status' => true
        ]);
    }
}
