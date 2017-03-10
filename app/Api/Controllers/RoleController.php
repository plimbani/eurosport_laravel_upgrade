<?php

namespace Laraspace\Api\Controllers;

use Brotzka\DotenvEditor\DotenvEditor;
use Dingo\Api\Routing\Helpers;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

// Need to Define Only Contracts
use Laraspace\Api\Contracts\RoleContract;

/**
 * Matches Resource Description.
 *
 * @Resource("roles")
 *
 * @Author mtilokani@aecordigital.com
 */
class RoleController extends BaseController
{
    public function __construct(RoleContract $roleObj)
    {
        $this->roleObj = $roleObj;
    }

    /**
     * Show all Match Results Details.
     *
     * Get a JSON representation of all the Roles.
     *
     * @Get("/roles")
     * @Versions({"v1"})
     * @Response(200, body={"id": 10, "username": "foo"})
     */
    public function getRoles()
    {
        return $this->roleObj->getAllRoles();
    }

    /**
     * Show all Match Results Details.
     *
     * Get a JSON representation of all the Roles.
     *
     * @Get("/roles-for-select")
     * @Versions({"v1"})
     * @Response(200, body={"id": 10, "username": "foo"})
     */
    public function getRolesForSelect()
    {
        return $this->roleObj->getRolesForSelect();
    }
}
