<?php

namespace App\Services;

use App\Contracts\UserContract;

class UserService implements UserContract
{
    public function __construct()
    {
        $this->userRepoObj = new \App\Repositories\UserRepository();
    }

    public function getAllUsers()
    {
        return $this->userRepoObj->getAllUsers();
    }
}
