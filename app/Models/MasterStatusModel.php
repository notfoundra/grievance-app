<?php

namespace App\Models;

class MasterStatusModel extends BaseModel
{
    protected $table = 'master_statuses';

    protected $primaryKey = 'id';

    protected $allowedFields = [
        'name',
        'description',
        'color',
        'sort_order',
        'is_active'
    ];
}
