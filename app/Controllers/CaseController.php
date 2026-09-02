<?php

namespace App\Controllers;

use App\Models\GrievanceAttachmentModel;
use App\Models\GrievanceCaseModel;
use App\Models\GrievanceUpdateModel;
use App\Models\MasterSiteModel;
use App\Models\MasterDepartmentModel;
use App\Models\MasterChannelModel;
use App\Models\MasterPriorityModel;
use App\Models\MasterCaseTypeModel;
use App\Models\MasterMessageTypeModel;
use App\Models\MasterStatusModel;

class CaseController extends BaseController
{
    protected $caseModel;
    protected \App\Libraries\AttachmentHandler $attachments;

    public function __construct()
    {
        $this->caseModel = new GrievanceCaseModel();
        $this->attachments = new \App\Libraries\AttachmentHandler();
    }

    public function ajaxList()
    {
        return $this->response->setJSON(
            $this->caseModel->getDatatable(scoped_site_id())
        );
    }

    public function caseDetail($id)
    {
        $case = $this->caseModel->getDetail($id);

        if (! $case || ! user_owns_site($case['site_id'])) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $attachmentModel = new GrievanceAttachmentModel();
        $updateModel     = new GrievanceUpdateModel();

        $allAttachments = $attachmentModel->forCase((int) $id);

        $caseAttachments = array_values(array_filter($allAttachments, fn($a) => $a['update_id'] === null));

        $attachmentsByUpdate = [];
        foreach ($allAttachments as $a) {
            if ($a['update_id'] !== null) {
                $attachmentsByUpdate[$a['update_id']][] = $a;
            }
        }

        $updates = $updateModel
            ->where('case_id', $id)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        // Tempelkan nama status & attachment ke tiap update, biar view tinggal pakai
        $statusModel = new MasterStatusModel();
        $statuses    = $statusModel->findAll();
        $statusMap   = array_column($statuses, 'name', 'id');

        foreach ($updates as &$u) {
            $u['status_name']  = $statusMap[$u['status_id']] ?? '-';
            $u['attachments']  = $attachmentsByUpdate[$u['id']] ?? [];
        }
        unset($u);

        $data = [
            'case'              => $case,
            'caseAttachments'   => $caseAttachments,
            'updates'           => $updates,
            'departments'       => (new MasterDepartmentModel())->where('is_active', 1)->orderBy('name')->findAll(),
            'priorities'        => (new MasterPriorityModel())->where('is_active', 1)->findAll(),
            'statuses'          => $statuses,
        ];

        return view('grievance/case_detail', $data);
    }

    public function newCase()
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
            'sites'        => $sites,
            'departments'  => (new MasterDepartmentModel())->where('is_active', 1)->findAll(),
            'channels'     => (new MasterChannelModel())->where('is_active', 1)->findAll(),
            'priorities'   => (new MasterPriorityModel())->where('is_active', 1)->findAll(),
            'caseTypes'    => (new MasterCaseTypeModel())->where('is_active', 1)->findAll(),
            'messageTypes' => (new MasterMessageTypeModel())->where('is_active', 1)->findAll(),
        ];

        return view('grievance/new_case', $data);
    }

    public function store()
    {
        $model = new GrievanceCaseModel();

        $rules = [
            'site_id'               => 'required|integer',
            'department_id'         => 'required|integer',
            'channel_id'            => 'required|integer',
            'message_type_id'       => 'required|integer',
            'case_type_id'          => 'required|integer',
            'priority_id'           => 'required|integer',
            'gender'           => 'required|min_length[4]',
            'message'               => 'required|min_length[10]',
            'target_response_date'  => 'required|valid_date',
            'target_closure_date'   => 'required|valid_date',
        ];

        if (! $this->validate($rules)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status' => false,
                'errors' => $this->validator->getErrors(),
            ]);
        }

        $targetResponse = $this->request->getPost('target_response_date');
        $targetClosure  = $this->request->getPost('target_closure_date');

        if (strtotime($targetClosure) < strtotime($targetResponse)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status' => false,
                'errors' => ['target_closure_date' => 'Target closure date cannot be earlier than target response date.'],
            ]);
        }

        $files      = $this->request->getFileMultiple('attachment') ?? [];
        $fileErrors = $this->attachments->validate($files);

        if (! empty($fileErrors)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status' => false,
                'errors' => ['attachment' => $fileErrors],
            ]);
        }

        $forcedSiteId = has_role(\App\Models\UserModel::ROLE_ADMIN) ? null : current_user()['site_id'];

        $id = $model->createCase($this->request, $forcedSiteId);

        $this->attachments->store($id, $files);

        $case = $model->find($id);

        return $this->response->setJSON([
            'status'      => true,
            'message'     => 'Case created successfully.',
            'id'          => $id,
            'case_number' => $case['case_number'] ?? null,
        ]);
    }

    /**
     * Edit data administratif case (bukan status — status ganti lewat addUpdate()).
     */
    public function update($id)
    {
        $case = $this->caseModel->find($id);

        if (! $case || ! user_owns_site($case['site_id'])) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $rules = [
            'department_id'         => 'required|integer',
            'priority_id'           => 'required|integer',
            'target_response_date'  => 'required|valid_date',
            'target_closure_date'   => 'required|valid_date',
            'rating'                => 'permit_empty|integer|greater_than[0]|less_than[6]',
        ];

        if (! $this->validate($rules)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status' => false,
                'errors' => $this->validator->getErrors(),
            ]);
        }

        $targetResponse = $this->request->getPost('target_response_date');
        $targetClosure  = $this->request->getPost('target_closure_date');

        if (strtotime($targetClosure) < strtotime($targetResponse)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status' => false,
                'errors' => ['target_closure_date' => 'Target closure date cannot be earlier than target response date.'],
            ]);
        }

        $data = [
            'department_id'        => $this->request->getPost('department_id'),
            'priority_id'          => $this->request->getPost('priority_id'),
            'pic'                  => $this->request->getPost('pic'),
            'target_response_date' => $targetResponse,
            'target_closure_date'  => $targetClosure,
            'root_cause'           => $this->request->getPost('root_cause'),
            'corrective_action'    => $this->request->getPost('corrective_action'),
            'management_response'  => $this->request->getPost('management_response'),
            'confidential'         => $this->request->getPost('confidential') ? 'Yes' : 'No',
            'repeated_case'        => $this->request->getPost('repeated_case') ? 'Yes' : 'No',
            'rating'               => $this->request->getPost('rating') ?: null,
            'satisfaction'         => $this->request->getPost('satisfaction') ?: null,
        ];

        $this->caseModel->update($id, $data);

        return $this->response->setJSON([
            'status'  => true,
            'message' => 'Case updated successfully.',
        ]);
    }

    /**
     * Tambah entri follow-up: ganti status + catatan wajib + lampiran opsional.
     * Ini yang jadi sumber timeline case.
     */
    public function addUpdate($id)
    {
        $case = $this->caseModel->find($id);

        if (! $case || ! user_owns_site($case['site_id'])) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $rules = [
            'status_id' => 'required|integer',
            'note'      => 'required|min_length[5]',
        ];

        if (! $this->validate($rules)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status' => false,
                'errors' => $this->validator->getErrors(),
            ]);
        }

        $files      = $this->request->getFileMultiple('attachment') ?? [];
        $fileErrors = $this->validateAttachments($files);

        if (! empty($fileErrors)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status' => false,
                'errors' => ['attachment' => $fileErrors],
            ]);
        }

        $updateModel = new GrievanceUpdateModel();

        $updateId = $updateModel->insert([
            'case_id'    => $id,
            'status_id'  => $this->request->getPost('status_id'),
            'note'       => $this->request->getPost('note'),
            'updated_by' => current_user()['name'] ?? 'System',
        ], true);

        $this->attachments->store((int) $id, $files, (int) $updateId);

        // Sinkronkan status_id case + isi response_date/closed_date otomatis
        $newStatusId  = (int) $this->request->getPost('status_id');
        $statusModel  = new MasterStatusModel();
        $statusName   = $statusModel->find($newStatusId)['name'] ?? '';

        $caseData = ['status_id' => $newStatusId];

        if (empty($case['response_date']) && strtolower($statusName) !== 'open') {
            $caseData['response_date'] = date('Y-m-d');
        }

        if (strtolower($statusName) === 'closed' && empty($case['closed_date'])) {
            $caseData['closed_date'] = date('Y-m-d');
        }

        $this->caseModel->update($id, $caseData);

        return $this->response->setJSON([
            'status'  => true,
            'message' => 'Follow up added successfully.',
        ]);
    }


    // Serves the file only through this controller — never expose writable/ publicly.
    public function downloadAttachment($attachmentId)
    {
        $attachment = (new GrievanceAttachmentModel())->find($attachmentId);

        if (! $attachment || ! is_file(WRITEPATH . $attachment['file_path'])) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return $this->response
            ->download(WRITEPATH . $attachment['file_path'], null)
            ->setFileName($attachment['original_name']);
    }
    public function delete($id)
    {
        $case = $this->caseModel->find($id);

        if (! $case || ! user_owns_site($case['site_id'])) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        if (! has_role(\App\Models\UserModel::ROLE_ADMIN)) {
            return $this->response->setStatusCode(403)->setJSON([
                'status'  => false,
                'message' => 'Hanya admin yang dapat menghapus case.',
            ]);
        }

        $this->caseModel->delete($id);

        return $this->response->setJSON([
            'status'  => true,
            'message' => 'Case berhasil dihapus.',
        ]);
    }
}
