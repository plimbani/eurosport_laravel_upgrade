<?php

namespace App\Api\Services;

use App\Api\Contracts\RoleContract;

class RoleService implements RoleContract
{
    public function __construct()
    {
        $this->roleRepoObj = new \App\Api\Repositories\RoleRepository();
    }

    public function getAllRoles()
    {
        return $this->roleRepoObj->getAllRoles();
    }

    public function getRolesForSelect()
    {
        return $this->roleRepoObj->getRolesForSelect();
    }
}
