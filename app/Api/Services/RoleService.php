<?php

namespace Laraspace\Api\Services;

use Laraspace\Api\Contracts\RoleContract;

class RoleService implements RoleContract
{
    public function __construct()
    {
        $this->roleRepoObj = new \Laraspace\Api\Repositories\RoleRepository();
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
