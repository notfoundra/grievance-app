<?php

namespace App\Models;

class MasterDepartmentModel extends BaseModel
{
    protected $table = 'master_departments';

    protected $primaryKey = 'id';

    protected $allowedFields = [
        'name',
        'description',
        'is_active'
    ];
}
