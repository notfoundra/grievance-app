<?php

namespace App\Models;

class UserModel extends BaseModel
{
    public const ROLE_ADMIN  = 'admin';
    public const ROLE_SOCKS  = 'socks';
    public const ROLE_GARMEN = 'garmen';

    protected $table          = 'users';
    protected $primaryKey     = 'id';
    protected $useSoftDeletes = true;
    protected $deletedField   = 'deleted_at';

    protected $allowedFields = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'site_id',
        'is_active',
        'last_login_at',
    ];

    public function findByUsername(string $username)
    {
        return $this->groupStart()
            ->where('username', $username)
            ->orWhere('email', $username)
            ->groupEnd()
            ->first();
    }

    public function touchLastLogin(int $id): void
    {
        $this->update($id, ['last_login_at' => date('Y-m-d H:i:s')]);
    }
}
