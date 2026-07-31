<?php

namespace App\Models;

class MasterMessageTypeModel extends BaseModel
{
    protected $table = 'master_message_types';

    protected $primaryKey = 'id';

    protected $allowedFields = [
        'name',
        'description',
        'is_active'
    ];
}
