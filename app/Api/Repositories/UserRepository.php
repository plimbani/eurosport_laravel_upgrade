<?php

namespace Laraspace\Api\Repositories;

use Laraspace\Models\User;
use DB;

class UserRepository {
    public function getAllUsers()
    {
        return User::all();
    }

    public function getUsersByRegisterType($registerType)
    {
        if($registerType=="desktop") {
            $isMobileUser=0;
        } else if($registerType=="mobile") {
            $isMobileUser=1;
        }
        return User::with(["personDetail", "roles"])->where('is_mobile_user', $isMobileUser)->get();
    }

    public function create($data)
    {
        return User::create($data);
    }

    public function delete($data)
    {
        return User::find($data['id'])->delete();
    }

    public function edit($userId)
    {
        $user=DB::table('users')
            ->join('people', 'users.person_id', '=', 'people.id')
            ->join('role_user', 'users.id', '=', 'role_user.user_id')
            ->select("users.id as id", "users.email as emailAddress", "users.organisation as organisation", "people.first_name as name", "people.last_name as surname", "role_user.role_id as userType")
            ->where("users.id", "=", $userId)
            ->first();
        return json_encode($user);
    }

    public function update($data, $userId)
    {
        return User::where('id', $userId)->update($data);
    }

    public function getUserById($userId)
    {
        return User::with(["personDetail", "roles"])->findOrFail($userId);
    }
}
