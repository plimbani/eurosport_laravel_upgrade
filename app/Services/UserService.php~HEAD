<?php

namespace Laraspace\Api\Services;

use Laraspace\Model\UserAffiliates;
use DB;
use Laraspace\Api\Contracts\UserContract;
use Validator;
use Laraspace\Model\Role;
use Illuminate\Support\Facades\Password;
use Illuminate\Mail\Message;

class UserService implements UserContract
{
    public function __construct()
    {
        $this->userRepoObj = new \Laraspace\Api\Repositories\UserRepository();
    }

    public function getAllUsers()
    {
        return $this->userRepoObj->getAllUsers();
    }
}
