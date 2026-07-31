<?php

namespace App\Models;

class MasterSiteModel extends BaseModel
{
    protected $table = 'master_sites';

    protected $primaryKey = 'id';

    protected $allowedFields = [
        'name',
        'description',
        'is_active'
    ];
}
