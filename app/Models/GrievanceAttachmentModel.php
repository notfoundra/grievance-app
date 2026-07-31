<?php

namespace App\Models;

class GrievanceAttachmentModel extends BaseModel
{
    protected $table = 'grievance_attachments';

    protected $primaryKey = 'id';

    protected $allowedFields = [
        'case_id',
        'update_id',
        'original_name',
        'stored_name',
        'file_path',
        'extension',
        'mime_type',
        'file_size'
    ];
}
