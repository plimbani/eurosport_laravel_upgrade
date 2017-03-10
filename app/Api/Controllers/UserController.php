<?php

namespace Laraspace\Api\Controllers;

use Brotzka\DotenvEditor\DotenvEditor;
use Dingo\Api\Routing\Helpers;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

// Need to Define Only Contracts
use Laraspace\Api\Contracts\UserContract;
use JWTAuth;

/**
 * Matches Resource Description.
 *
 * @Resource("users")
 *
 * @Author mtilokani@aecordigital.com
 */
class UserController extends BaseController
{
    public function __construct(UserContract $userObj)
    {
        $this->userObj = $userObj;
        // $this->middleware('auth');
        // $this->middleware('jwt.auth');
    }

    /**
     * Show all Match Results Details.
     *
     * Get a JSON representation of all the Users.
     *
     * @Get("/users")
     * @Versions({"v1"})
     * @Response(200, body={"id": 10, "username": "foo"})
     */
    public function getUsers()
    {
        return $this->userObj->getAllUsers();
    }

    /**
     * Create New Match Result.
     *
     * @Post("/user/create")
     *
     * @Versions({"v1"})
     * @Request("name=test", contentType="application/x-www-form-urlencoded")
     */
    public function createUser(Request $request)
    {
        return $this->userObj->create($request);
    }

    /**
     * Edit  Match result.
     *
     * @Post("/match/edit/{$id}")
     *
     * @Versions({"v1"})
     * @Request("name=test", contentType="application/x-www-form-urlencoded")
     */
    public function edit(Request $request, $userId)
    {
        return $this->userObj->edit($request, $userId);
    }

    public function deleteUser($deleteId)
    {
        return $this->userObj->deleteUser($deleteId);
    }
}
