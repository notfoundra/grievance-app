<?php

namespace App\Models;

use CodeIgniter\Model;

class GrievanceAttachmentModel extends Model
{
    protected $table            = 'grievance_attachments';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'case_id',
        'update_id',
        'original_name',
        'stored_name',
        'file_path',
        'extension',
        'mime_type',
        'file_size',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function forCase(int $caseId)
    {
        return $this->where('case_id', $caseId)->findAll();
    }
}
