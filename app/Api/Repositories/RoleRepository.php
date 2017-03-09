<?php

namespace Laraspace\Api\Repositories;

use Laraspace\Models\Role;

class RoleRepository {
    public function getAllRoles()
    {
        return Role::all();
    }

    public function getRolesForSelect() {
        return Role::All()->pluck("name", "id");
    }
}
