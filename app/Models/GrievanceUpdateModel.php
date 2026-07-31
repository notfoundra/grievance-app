<?php

namespace App\Models;

class GrievanceUpdateModel extends BaseModel
{
    protected $table = 'grievance_updates';

    protected $primaryKey = 'id';

    protected $allowedFields = [
        'case_id',
        'status_id',
        'note',
        'updated_by'
    ];
}
