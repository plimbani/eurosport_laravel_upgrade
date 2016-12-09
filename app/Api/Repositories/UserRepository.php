<?php

namespace App\Api\Repositories;

use App\Models\User;
class UserRepository
    public function getAllUsers()
    {
        /*  Use Join for Fetch Club Income Data with Club Table */
        return User::all();
    }
}
