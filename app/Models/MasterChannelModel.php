<?php

namespace App\Models;

class MasterChannelModel extends BaseModel
{
    protected $table = 'master_channels';

    protected $primaryKey = 'id';

    protected $allowedFields = [
        'name',
        'description',
        'is_active'
    ];
}
