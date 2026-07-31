<?php

namespace App\Models;

class MasterPriorityModel extends BaseModel
{
    protected $table = 'master_priorities';

    protected $primaryKey = 'id';

    protected $allowedFields = [
        'name',
        'description',
        'is_active'
    ];
}
