<?php

namespace Laraspace\Api\Repositories;

use Laraspace\Models\User;

class UserRepository {
    public function getAllUsers()
    {
        return User::all();
    }

    public function create($data)
    {
        return User::create($data);
    }

    public function delete($data)
    {
        return User::find($data['id'])->delete();
    }

    public function edit($data, $userId)
    {
        return User::where('id', $userId)->update($data);
    }

    public function getUserFromId($userId)
    {
        return User::find($userId);
    }
}
