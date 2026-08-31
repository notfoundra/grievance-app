<?php

namespace App\Libraries;

use App\Models\GrievanceAttachmentModel;

class AttachmentHandler
{
    protected array $allowedExt = ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx'];
    protected int $maxSize = 5 * 1024 * 1024; // 5 MB

    public function validate(array $files): array
    {
        $errors = [];

        foreach ($files as $file) {
            if (! $file->isValid()) {
                continue;
            }

            $ext = strtolower($file->getClientExtension());

            if (! in_array($ext, $this->allowedExt, true)) {
                $errors[] = "{$file->getClientName()}: file type not allowed.";
            }

            if ($file->getSize() > $this->maxSize) {
                $errors[] = "{$file->getClientName()}: file exceeds 5 MB.";
            }
        }

        return $errors;
    }

    public function store(int $caseId, array $files, ?int $updateId = null): void
    {
        if (empty($files)) {
            return;
        }

        $attachmentModel = new GrievanceAttachmentModel();
        $uploadPath      = WRITEPATH . 'uploads/grievance/' . $caseId;

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
                'update_id'     => $updateId,
                'original_name' => $file->getClientName(),
                'stored_name'   => $storedName,
                'file_path'     => 'uploads/grievance/' . $caseId . '/' . $storedName,
                'extension'     => $file->getClientExtension(),
                'mime_type'     => $file->getClientMimeType(),
                'file_size'     => $file->getSize(),
            ]);
        }
    }
}
