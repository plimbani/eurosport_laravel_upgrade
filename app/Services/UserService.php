<?php

namespace App\Api\Services;

use App\Model\UserAffiliates;
use DB;
use App\Api\Contracts\UserContract;
use Validator;
use App\Model\Role;
use Illuminate\Support\Facades\Password;
use Illuminate\Mail\Message;

class UserService implements UserContract
{
    public function __construct()
    {
        $this->userRepoObj = new \App\Api\Repositories\UserRepository();
    }

    public function getAllUsers()
    {
        return $this->userRepoObj->getAllUsers();
    }
}
