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
 * Users Resource Description.
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
     * Show all User Results Details.
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
     * Show all User Results Details.
     *
     * Get a JSON representation of all the Users.
     *
     * @Get("/users")
     * @Versions({"v1"})
     * @Response(200, body={"id": 10, "username": "foo"})
     */
    public function getUsersByRegisterType($registerType)
    {
        return $this->userObj->getUsersByRegisterType($registerType);
    }

    /**
     * Create New User Result.
     *
     * @Post("/user/create")
     *
     * @Versions({"v1"})
     * @Request("name=test", contentType="application/x-www-form-urlencoded")
     */
    public function createUser(Request $request)
    {
          dd($request->all());
        return $this->userObj->create($request);
    }

    /**
     * Edit User
     *
     * @Post("/user/edit/{$id}")
     *
     * @Request("name=test", contentType="application/x-www-form-urlencoded")
     */
    public function edit($userId)
    {
        return $this->userObj->edit($userId);
    }

    /**
     * Update User
     *
     * @Post("/user/edit/{$id}")
     *
     * @Request("name=test", contentType="application/x-www-form-urlencoded")
     */
    public function update(Request $request, $userId)
    {

        return $this->userObj->update($request, $userId);
    }

    /**
     * Delete User
     * 
     * @param  [type] $id User Id
     * 
     * @return [type]           [description]
     */
    public function deleteUser($id)
    {
        return $this->userObj->delete($id);
    }
}
