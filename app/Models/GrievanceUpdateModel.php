<?php

namespace App\Models;

class GrievanceUpdateModel extends BaseModel
{
    protected $table = 'grievance_updates';

    protected $primaryKey = 'id';

    protected $allowedFields = [
        'case_id',
        'status_id',
        'update_date',
        'note',
        'updated_by'
    ];
}
