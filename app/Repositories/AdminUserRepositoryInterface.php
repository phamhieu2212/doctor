<?php

namespace App\Repositories;

interface AdminUserRepositoryInterface extends AuthenticatableRepositoryInterface
{
    public function getAllAdminUserByRole($role);
}
