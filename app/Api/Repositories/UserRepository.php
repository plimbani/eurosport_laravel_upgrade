<?php

namespace Laraspace\Api\Repositories;

use Laraspace\Models\User;
use DB;
use Hash;

class UserRepository {
    public function getAllUsers()
    {
        return User::all();
    }
    public function getUserDetails($data)
    {
        // dd($data);
       $email = $data['userData']['email'];
        $user = User:: join('role_user', 'users.id', '=', 'role_user.user_id')
                ->join('roles', 'roles.id', '=', 'role_user.role_id')
                ->where('users.email',trim($email))
                ->select("users.*", "roles.name as role_name","roles.slug as role_slug")
                ->get();
        return $user;
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
        $userData = [
        'person_id' => $data['person_id'],
        'username' => $data['username'],
        'name' => $data['name'],
        'email' => $data['email'],
        'organisation' => $data['organisation'],
        'password' => $data['password'],
        'token' => $data['token'],
        'is_verified' => 0,
        'is_online' => 0,
        'is_active' => 0,
        'is_blocked' => 0 ,
        'is_mobile_user' => 0,
        'user_image'=>(isset($data['user_image']) && $data['user_image']!='') ?  $data['user_image'] : ''
        ];
        return User::create($userData);
    }

    public function delete($id)
    {
        return User::find($id)->delete();
    }
    public function changeUserStatus($data)
    {
        $id = $data['userData']['id'];
        $status = ($data['userData']['status'] == 1) ? '0' : '1';
        return User::where('id',$id)->update(['is_active'=>$status]);
    }
    public function edit($userId)
    {
       $user=DB::table('users')
            ->join('people', 'users.person_id', '=', 'people.id')
            ->join('role_user', 'users.id', '=', 'role_user.user_id')
            ->select("users.id as id", "users.email as emailAddress","users.user_image as image", "users.organisation as organisation", "people.first_name as name", "people.last_name as surname", "role_user.role_id as userType")
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

    public function createPassword($usersDetail)
    {
        $key = $usersDetail['key'];
        $password = $usersDetail['password'];
        $usersPassword = User::where('token', $key)->first();
        // echo "<pre>";print_r($usersPassword);echo "</pre>";exit;
        $users = User:: where("id", $usersPassword->id)->first();
        $users->is_verified = 1;
        $users->is_active = 1;
        $users->token = '';
        $users->password = Hash::make($password);
        // $users->password = $password;
        $user =  $users->save();

    }
}
