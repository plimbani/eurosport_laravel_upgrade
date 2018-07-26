<?php

namespace Laraspace\Api\Contracts;

interface RoleContract
{
    /*
     * Get All Roles
     *
     * @return response
     */
    public function getAllRoles();

    /*
     * Get All Roles For Select
     *
     * @return response
     */
    public function getRolesForSelect();
}
