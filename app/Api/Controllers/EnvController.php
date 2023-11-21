<?php

namespace Laraspace\Api\Controllers;

// Need to Define Only Contracts
use Laraspace\Api\Contracts\UserContract;

class EnvController extends BaseController
{
    public function __construct(UserContract $userObj)
    {

        $this->userObj = $userObj;
    }

    public function test2()
    {
        return $this->userObj->getAllUsers();
    }
}
