<?php

namespace Laraspace\Services;

use Laraspace\Contracts\UserContract;

class UserService implements UserContract
{
    public function __construct()
    {
        $this->userRepoObj = new \Laraspace\Repositories\UserRepository();
    }

    public function getAllUsers()
    {
        return $this->userRepoObj->getAllUsers();
    }
}
