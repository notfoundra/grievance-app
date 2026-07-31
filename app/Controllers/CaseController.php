<?php

namespace App\Controllers;

use App\Models\GrievanceAttachmentModel;
use App\Models\GrievanceCaseModel;
use App\Models\MasterSiteModel;
use App\Models\MasterDepartmentModel;
use App\Models\MasterChannelModel;
use App\Models\MasterPriorityModel;
use App\Models\MasterCaseTypeModel;
use App\Models\MasterMessageTypeModel;
use App\Models\GrievanceUpdateModel;

class CaseController extends BaseController
{
    protected $caseModel;

    public function __construct()
    {
        $this->caseModel = new GrievanceCaseModel();
    }

    public function ajaxList()
    {
        return $this->response->setJSON(

            $this->caseModel->getDatatable()

        );
    }

    public function caseDetail($id)
    {
        $data['case'] = $this->caseModel->getDetail($id);

        if (!$data['case']) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('grievance/case_detail', $data);
    }
    public function newCase()
    {
        $data = [

            'sites' => (new MasterSiteModel())->findAll(),

            'departments' => (new MasterDepartmentModel())->findAll(),

            'channels' => (new MasterChannelModel())->findAll(),

            'priorities' => (new MasterPriorityModel())->findAll(),

            'caseTypes' => (new MasterCaseTypeModel())->findAll(),

            'messageTypes' => (new MasterMessageTypeModel())->findAll(),

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

        // Validate files BEFORE creating the case, so a bad attachment
        // never leaves an orphaned case behind.
        $files      = $this->request->getFileMultiple('attachment') ?? [];
        $fileErrors = $this->validateAttachments($files);

        if (! empty($fileErrors)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status' => false,
                'errors' => ['attachment' => $fileErrors],
            ]);
        }

        $id = $model->createCase($this->request);

        $this->storeAttachments($id, $files);

        return $this->response->setJSON([
            'status'  => true,
            'message' => 'Case created successfully.',
            'id'      => $id,
        ]);
    }

    private function validateAttachments(array $files): array
    {
        $allowedExt = ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx'];
        $maxSize    = 5 * 1024 * 1024; // 5 MB
        $errors     = [];

        foreach ($files as $file) {
            if (! $file->isValid()) {
                continue; // empty slot, nothing was actually attached
            }

            $ext = strtolower($file->getClientExtension());

            if (! in_array($ext, $allowedExt, true)) {
                $errors[] = "{$file->getClientName()}: file type not allowed.";
            }

            if ($file->getSize() > $maxSize) {
                $errors[] = "{$file->getClientName()}: file exceeds 5 MB.";
            }
        }

        return $errors;
    }

    private function storeAttachments(int $caseId, array $files): void
    {
        if (empty($files)) {
            return;
        }

        $attachmentModel = new GrievanceAttachmentModel();
        $uploadPath       = WRITEPATH . 'uploads/grievance/' . $caseId;

        if (! is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        foreach ($files as $file) {
            if (! $file->isValid() || $file->hasMoved()) {
                continue;
            }

            $storedName = $file->getRandomName();
            $file->move($uploadPath, $storedName);

            $attachmentModel->insert([
                'case_id'       => $caseId,
                'update_id'     => null,
                'original_name' => $file->getClientName(),
                'stored_name'   => $storedName,
                'file_path'     => 'uploads/grievance/' . $caseId . '/' . $storedName,
                'extension'     => $file->getClientExtension(),
                'mime_type'     => $file->getClientMimeType(),
                'file_size'     => $file->getSize(),
            ]);
        }
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
}
