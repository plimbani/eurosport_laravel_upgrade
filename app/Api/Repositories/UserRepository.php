<?php

namespace Laraspace\Api\Repositories;

use Laraspace\Models\User;
use Laraspace\Models\UserFavourites;
use Laraspace\Models\Settings;
use DB;
use Hash;

class UserRepository {

    public function __construct()
    {
      $this->userImagePath = getenv('S3_URL').'/assets/img/users/';
    }
    public function getAllUsers()
    {
        return User::all();
    }
    public function createUserFavourites($userFavouriteData)
    {
      return UserFavourites::create($userFavouriteData);
    }
    public function getUserDetails($data)
    {
        // dd($data);
       $email = $data['userData']['email'];
        $user = User:: join('role_user', 'users.id', '=', 'role_user.user_id')
                ->join('roles', 'roles.id', '=', 'role_user.role_id')
                ->where('users.email',trim($email))
                ->select("users.*", "roles.name as role_name","roles.slug as role_slug",
                  DB::raw('CONCAT("'.$this->userImagePath.'", users.user_image) AS user_image')
                  )
                ->get();
        return $user;
    }

    public function getUsersByRegisterType($data)
    {
        $registerType = $data['registerType'];

        if($registerType=="desktop") {
            $isMobileUser=0;
        } else if($registerType=="mobile") {
            $isMobileUser=1;
        }

        $user = User::with(["personDetail", "roles"])
                    ->where('users.is_mobile_user', $isMobileUser);

        if(isset($data['userData'])) {
            $user->where(function($query) use($data) {
                $query->where('users.email', 'like', "%" . $data['userData'] . "%")
                    ->orWhereHas('personDetail', function ($query1) use($data) {
                        if(isset($data['userData'])) {
                            $query1->where('people.first_name', 'like', "%" . $data['userData'] . "%");
                        }
                        if(isset($data['userData'])) {
                            $query1->orWhere('people.last_name', 'like', "%" . $data['userData'] . "%");
                        }
                    });
            });
        }
         $user->orderBy('users.created_at','desc');
         $userData = $user->get();

         $dataArray = array();

         if(isset($data['report_download']) &&  $data['report_download'] == 'yes') {

            foreach ($userData as $user) {

               // print_r($user->roles);exit;

                if($user->is_mobile_user ==1){
                    $status = ($user->is_verified == 1) ? 'Verified': 'Resend';
                } else {
                    $status = ($user->is_verified == 1) ? 'Accepted': 'Resend';
                }

                $roleName = $user->roles[0]->name;
                if($registerType == 'desktop') {
                    $ddata = [
                        $user->personDetail['first_name'],
                        $user->personDetail['last_name'],
                        $user->email,
                        $user->organisation,
                        $roleName,
                        $status
                    ];
                } else {
                    $ddata = [
                        $user->personDetail['first_name'],
                        $user->personDetail['last_name'],
                        $user->email,
                        date_format($user->created_at,"H:i d M Y"),
                        //HH:mm  DD MMM YYYY
                        $status
                    ];
                }

                array_push($dataArray, $ddata);
            }
             $otherParams = [
                    'sheetTitle' =>"UserReport",
                    'sheetName' => "UserReport",
                    'boldLastRow' => false
                ];
           if($registerType == 'desktop') {
                $lableArray = [
                    'Name','Surname' ,'Email address', 'Organisation','User type', 'Status'
                ];
            } else {
               $lableArray = [
                    'Name','Surname' ,'Email address', 'Date & time','Status'
                ];
            }
            //Total Stakes, Total Revenue, Amount & Balance fields are set as Number statically.
        \Laraspace\Custom\Helper\Common::toExcel($lableArray,$dataArray,$otherParams,'xlsx','yes');
         }

         return  $user->get();
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
        'is_active' => (isset($data['is_mobile_user'])) ? 0 : 1,
        'is_blocked' => 0 ,
        'is_mobile_user' => (isset($data['is_mobile_user'])) ? $data['is_mobile_user'] : 0,
        'user_image'=>(isset($data['user_image']) && $data['user_image']!='') ?  $data['user_image'] : ''
        ];
        $deletedUser = User::onlyTrashed()->where('email',$data['email'])->first();
        // if($deletedUser){
        //     $user = $deletedUser->restore();
        // }
         // $deletedUser;
        try {
            if($deletedUser){
                $deletedUser->restore();

                $userData = User::find($deletedUser['id'])->update($data);
               
                // $userData->roles()->detatch();
                $user = User::find($deletedUser['id']);
                $user->roles()->sync($data['userType']);
                return ['status' => 'updated', 'user' => $user];

                // return {'status':'updated','user':$user};
               
                 // return  $deletedUser->attachRole($data['userType']);
            }else{
                    $user = User::create($userData);
                    $user->attachRole($data['userType']);
                    return ['status'=>'created','user'=>$user];


                    // print_r($user);
                   // return  $user->attachRole($data['userType']);
              }
        }
        catch (\PDOException  $e) {
         return ['status'=>false];
          //return $e->errorInfo[1]);
        }

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
            ->select("users.id as id", "users.email as emailAddress",
               DB::raw('IF(users.user_image is not null,CONCAT("'.$this->userImagePath.'", users.user_image),"" ) as image'),
             "users.organisation as organisation", "people.first_name as name", "people.last_name as surname", "role_user.role_id as userType")
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
        $password = (isset($usersDetail['password']) && $usersDetail['password']!='') ? $usersDetail['password'] : '';
        $usersPassword = User::where('token', $key)->first();
        // echo "<pre>";print_r($usersPassword);echo "</pre>";exit;
        $users = User::where("id", $usersPassword->id)->first();
        $users->is_verified = 1;
        $users->is_active = 1;
        $users->token = '';
        if($password != '')
          $users->password = Hash::make($password);
        // $users->password = $password;
        $user =  $users->save();
        return ($users->is_mobile_user == 1) ? 'Mobile' : 'Desktop';

    }
    public function createUserSettings($userData)
    {
      return Settings::create($userData);
    }
    public function getSetting($userData)
    {
      $userId = $userData['user_id'];
      return Settings::where('user_id','=',$userId)->get();
      //return Settings::with(['user'])->where('user_id','=',$userId)->get();
    }
    public function postSetting($userData)
    {
        
      \Log::info($userData);
      $userId= $userData['userId'];
      $updatedValue = ['value' => json_encode($userData['userSettings'])];

      //$updatedValue = array('value'=>$userData['userSettings']);
      return Settings::where('user_id', $userId)->update($updatedValue);
    }
    public function setFCM($data) {
      $email = $data['email'];
      $fcmId = $data['fcm_id'];
      $updatedValue = ['fcm_id'=>$fcmId];
      return User::where('email',$email)->update($updatedValue);
    }

}
