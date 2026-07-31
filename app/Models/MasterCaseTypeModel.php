<?php

namespace App\Models;

class MasterCaseTypeModel extends BaseModel
{
    protected $table = 'master_case_types';

    protected $primaryKey = 'id';

    protected $allowedFields = [
        'name',
        'description',
        'is_active'
    ];
}
