<?php

namespace Laraspace\Api\Services;


use DB;
use Laraspace\Api\Contracts\UserContract;
use Validator;
use Illuminate\Support\Facades\Password;
use Laraspace\Custom\Helper\Common;
use Illuminate\Mail\Message;
use Laraspace\Models\User;
use Hash;

use App\Mail\SendMail;
use Illuminate\Support\Facades\Mail;
use Laraspace\Models\UserFavourites;

class UserService implements UserContract
{
    public function __construct()
    {
        $this->userRepoObj = new \Laraspace\Api\Repositories\UserRepository();
        $this->peopleRepoObj = new \Laraspace\Api\Repositories\PeopleRepository();
        $this->s3  = \Storage::disk('s3');
        $this->getAWSUrl = getenv('S3_URL');
    }

    public function getAllUsers()
    {
        return $this->userRepoObj->getAllUsers();
    }

    public function getUsersByRegisterType($data)
    {
        return $this->userRepoObj->getUsersByRegisterType($data);
    }

    public function getUserTableData($data)
    {
       return $this->userRepoObj->getUsersByRegisterType($data);
       // return $this->userRepoObj->getUserTableData($data);
    }


    /**
     * Create New User.
     *
     * @param  [type]
     * @param mixed $data
     *
     * @return [type]
     */
    public function create($data)
    {
      // dd($data);
  
        // Data Initilization
        $data = $data->all();

        \Log::info('User Create Method Called');
        $userData=array();
        $userData['people']=array();
        $userData['user']=array();
        $userPassword = NULL;
        $token = str_random(30);
        //$data['is_mobile_user'] = 0;
        // Validation checks for Email Validation

        // Data Assignment
        // TODO: we put condition for Set up for Mobile User Data
        // TODO Check For Mobile Users
        $isMobileUsers = \Request::header('IsMobileUser');
        $data['is_mobile_user'] = false;
        if($isMobileUsers != '' ) {
          $data['is_mobile_user'] = true;
        }

        // Also check From Desktop
         $userData['user']['is_mobile_user'] = false;
        if(isset($data['registerType']) && trim($data['registerType']) == 'mobile') {
          $userData['user']['is_mobile_user'] = true;
          $data['userType'] = '5';
          $data['organisation'] = 'EuroSportring';
         // $userPassword = Hash::make(trim($data['password']));
        }
        // here we check that if userType is

        if(isset($isMobileUsers) && $isMobileUsers == true)
        {
          $data['name'] = $data['first_name'];
          $data['surname'] = $data['sur_name'];
          $data['emailAddress'] = $data['email'];
          $data['organisation'] = 'EuroSportring';
          $data['userType'] = '5';
          \Log::info('passwod b4 encrupt '.$data['password']);
          $userPassword = Hash::make(trim($data['password']));
          $data['tournament_id']=$data['tournament_id'];
          $userData['user']['is_mobile_user'] = 1;
          \Log::info('passwod after encrypt '.$userPassword);

         // $token = 1;
        }

        $userData['people']['first_name']=$data['name'];
        $userData['people']['last_name']=$data['surname'];
        \Log::info('Insert in PeopleTable');
        $peopleObj = $this->peopleRepoObj->create($userData['people']);

        $userData['user']['person_id']=$peopleObj->id;
        $userData['user']['username']=$data['emailAddress'];
        $userData['user']['name']=$data['name']." ".$data['surname'];
        $userData['user']['email']=$data['emailAddress'];
        $userData['user']['organisation']=$data['organisation'];
        $userData['user']['userType']=$data['userType'];

       if(isset($data['user_image']) && $data['user_image']!='')
        {
            \Log::info('Insert in Image');
            $imagename = $this->saveUsersLogo($data);
            $userData['user']['user_image']=$imagename;
        }

        // $userData['user']['password'] = Hash::make('password');
        // // dd($userData['user']);
        // $userObj = $this->userRepoObj->create($userData['user']);
        // TODO: default is vaue for password
       // $userData['user']['password']=Hash::make('password');

        // We cant Allow untikl its set password
        $userData['user']['password']=$userPassword;


        $userData['user']['token'] = $token;

        \Log::info('Insert in UserTable');
        $userRes=$this->userRepoObj->create($userData['user']);
        \Log::info('deleted user');
        if($userRes['status'] == false )
          {
            return ['status_code' => '200', 'message' => 'Email already Exist'];
          }
        $userObj = $userRes['user'];
        // $userObj->roles()->sync($data['userType'])
        // $userObj->attachRole($data['userType']);
        // Here we add code for Mobile Users to relate tournament to users
       if(isset($isMobileUsers) && $isMobileUsers!= '' && ($userRes['status'] == 'created'))
        {
          \Log::info('Insert in User Favourite table');
          $user_id = $userObj->id;
          $userFavouriteData['user_id']=$user_id;
          if($data['tournament_id'] == '' || $data['tournament_id'] == 0)
                $data['tournament_id'] = 1;
          $userFavouriteData['tournament_id'] = $data['tournament_id'];
          $this->userRepoObj->createUserFavourites($userFavouriteData);
          // Also Add settings Data
          $userSettings['user_id'] = $user_id;
          $userSettings['value'] = '{"is_sound":"true","is_vibration":"true","is_notification":"true"}';
           $this->userRepoObj->createUserSettings($userSettings);
        //  return ['status_code' => '200', 'message' => 'Mobile Data Sucessfully Inserted'];
        }
        if ($data) {
            \Log::info('Sent email');
            $email_details = array();
            $email_details['name'] = $data['name'];
            $email_details['token'] = $token;
            $email_details['is_mobile_user'] = 0;
            $recipient = $data['emailAddress'];
            $email_templates = 'emails.users.create';
            $email_msg = 'Euro-Sportring Tournament Planner - Set password';
            if($userObj->is_mobile_user == 1) {
           //   $email_templates = 'emails.users.mobile_create';
              $email_msg = 'Euro-Sportring email verification';
              $email_details['is_mobile_user'] = 1;
            }
            Common::sendMail($email_details, $recipient, $email_msg, $email_templates);
            return ['status_code' => '200', 'message' => 'Please check your inbox to verify your email address and complete your account registration.'];
        }
    }

    public function resendEmail($data) {

    }
    public function saveUsersLogo($data, $id='')
    {
       if($data['user_image'] != '')
       {
            if(strpos($data['user_image'],$this->getAWSUrl) !==  false) {
              $path = $this->getAWSUrl.'/assets/img/users/';
              $imageLogo = str_replace($path,"",$data['user_image']);
              return $imageLogo;
            }

            $imagePath = '/assets/img/users/';
            $image_string = $data['user_image'];

            $img = explode(',', $image_string);
            $imgData = base64_decode($img[1]);

            //$name = $data['name'];
            if($id == '') {
              $now = new \DateTime();
              $timeStamp = $now->getTimestamp();
            } else {
              $timeStamp = $id;
            }
            // TODO: move to s3
            //$info = $s3->has('dev-esr/'.$imagePath);
            $path = $imagePath.$timeStamp.'.png';
            $this->s3->put($path, $imgData);

            // OLD Code:
            //$path = public_path().'/assets/img/users/'.$timeStamp.'.png';
            //file_put_contents($path, $imgData);
            // Resize image to 100*100

            // TODO: need to resize
            //$img = \Image::make($path)->resize(250, 250);
            // Save it
            //$img->save($path);
            return $timeStamp.'.png';
        } else {
            return '';
        }
    }
    /**
     * Edit User.
     *
     * @param array $data
     *
     * @return [type]
     */
    public function edit($userId)
    {
      return $this->userRepoObj->edit($userId);
    }

    public function getUserDetails($data)
    {

        $data =  $this->userRepoObj->getUserDetails($data);
        if ($data) {
            return ['status_code' => '200', 'data' => $data];
        }

    }

    /**
     * Update User.
     *
     * @param array $data
     *
     * @return [type]
     */
    public function update($data, $userId)
    {

        $data = $data->all();
        $userData=array();
        $userData['people']=array();
        $userData['user']=array();

        $imagename =NULL;
        // Data Initlization for Mobile User
        $isMobileUsers = \Request::header('IsMobileUser');
        if($isMobileUsers != '') {
          // here we change the data variable
          \Log::info('Update in Uses table');

          $data['name'] = $data['first_name'];
          $data['surname'] = $data['last_name'];
         // \Log::info('Update in password'.$data['password']);
         // $userData['user']['password'] = Hash::make(trim($data['password']));
          $data['emailAddress'] = '';
          $data['organisation'] = 'Euro-Sportring';
          $data['userType'] = '5';
          // here we add code for Tournament id update

        }

        if(isset($data['user_image']) && $data['user_image']!='')
        {
         // echo \Route::current();
            //$isBase64 = btoa(atob($data['user_image']));

            //$info = $this->s3->has('dev-esr/'.$data['user_image']);
            //if($info) {
              // $imagename = $data['user_image'];
            //} else {
              $imagename = $this->saveUsersLogo($data, $data['id']);
              $userData['user']['user_image']=$imagename;
           // }
        } else {

          $userData['user']['user_image']=$imagename;
        }


        $userData['user']['name']=$data['name']." ".$data['surname'];
        ($data['emailAddress']!= '') ? $userData['user']['email']=$data['emailAddress'] : '';
        ($data['organisation']!= '') ? $userData['user']['organisation']=$data['organisation']: '';
        (isset($data['locale']) && $data['locale']!='') ? $userData['user']['locale'] = $data['locale'] : '';

        $this->userRepoObj->update($userData['user'], $userId);

        $userObj = User::findOrFail($userId);
        $userObj->detachAllRoles();
        $userObj->attachRole($data['userType']);

        $userData['people']['first_name']=$data['name'];
        $userData['people']['last_name']=$data['surname'];
        $peopleObj = $this->peopleRepoObj->edit($userData['people'], $userObj->person_id);

        if ($data) {

          return ['status_code' => '200', 'message' => 'Profile updated successfully.'];
        }
    }

    /**
     * Delete User.
     *
     * @param array $data
     *
     * @return [type]
     */
    public function delete($id)
    {
        $data = $this->userRepoObj->delete($id);
        if ($data) {
            return ['status_code' => '200', 'message' => 'Data Successfully Deleted'];
        }
    }
    /**
     * Change User Status
     *
     * @param array $data
     *
     * @return [type]
     */
    public function changeUserStatus($id)
    {

        $data = $this->userRepoObj->changeUserStatus($id);
        if ($data) {

            $status = ($id['userData']['status'] == 1) ? 'User has been de-activate Successfully' : 'User has been active Successfully';
            return ['status_code' => '200',
            'message' => $status];
        }
    }
    // Here we add entry in database
    public function setFavourite($data)
    {

      \Log::info('setFavourite Method Called');
      \Log::info('UserId'.$data['user_id'].'TournamentId'.$data['tournament_id']);
      // here we have to entry in database
      $user_id = $data['user_id'];
      $tournament_id = $data['tournament_id'];
      // First check if its exist if not then insert it
      $data = UserFavourites::where('user_id','=',$user_id)
              ->where('tournament_id','=',$tournament_id)->get();
      if(count($data) == 0) {
        //  Insert it
        \Log::info('setFavouriteData inserted');
        $userFavouriteData = array();
        $userFavouriteData['user_id'] =  $user_id;
        $userFavouriteData['tournament_id']  = $tournament_id;
        $data =   UserFavourites::create($userFavouriteData);
        if($data) {
          \Log::info('setFavouriteData Return');
          return ['status_code'=>'200','message'=>'User favourite data is inserted'];
        }
      } else {
        \Log::info('setFavouriteData not Return');
          return ['status_code'=>'200','message'=>'alreay set favourite'];
      }
    }
    public function removeFavourite($data)
    {
       $user_id = $data['user_id'];
       $tournament_id = $data['tournament_id'];
       // remvoe it from database
       $data = UserFavourites::where('user_id','=',$user_id)
              ->where('tournament_id','=',$tournament_id)->delete();
       $msg = 'User favourite data deleted';

       if($data == 0)
          $msg = 'User favourite already deleted';
      return ['status_code'=>'200','message'=>$msg];
    }
    public function setDefaultFavourite($data)
    {
       $user_id = $data['user_id'];
       $tournament_id = $data['tournament_id'];

       // Make it default for that record
       $userFavouriteData = UserFavourites::where('user_id','=',$user_id)
              ->where('tournament_id','=',$tournament_id)->get();
      if(count($userFavouriteData) == 0) {
        // Insert value and set default
        $userFavouriteData = array();
        $userFavouriteData['user_id'] =  $user_id;
        $userFavouriteData['tournament_id']  = $tournament_id;
        $userFavouriteData['is_default']  = 1;

        $data =   UserFavourites::create($userFavouriteData);
        unset($userFavouriteData);
        return ['status_code'=>'200','message'=>'Default tournament created successfully'];
      } else {
        // Update and set default
        // First Set NULL
        $data =  UserFavourites::where('user_id','=',$user_id)
                 ->update(['is_default'=>0]);
         // Update it
        $data =  UserFavourites::where('user_id','=',$user_id)
              ->where('tournament_id','=',$tournament_id)
              ->update(['is_default'=>1]);
        if($data) {
          return ['status_code'=>'200','message'=>'Default tournament updated successfully'];
        }
      }
    }

    public function getSetting($userData)
    {
      $data = $this->userRepoObj->getSetting($userData);
      if($data) {
        return ['status_code'=>'200','data'=>$data];
      }
    }
    public function postSetting($userData)
    {
      $data = $this->userRepoObj->postSetting($userData['userData']);
      if($data) {
        return ['status_code'=>'200','message'=>'User Settings Updated successfully'];
      }
    }
    public function setUserImage($data)
    {

      $userId = $data['user_id'];
      $userImg = $data['user_image'];
      //$this->saveUsersLogo($userImg, $userId);
      // Update in DB
      $userData = array();
      $userData['user_image']= $this->saveUsersLogo($data, $userId);
      $userPath = getenv('S3_URL').'/assets/img/users/'.$userData['user_image'];
      $data = $this->userRepoObj->update($userData,$userId);
      if($data) {
        return ['status_code'=>'200','message'=>'User profile image Updated successfully','data'=>$userPath];
      }
      // Add code for Edit Profile image for User
    }
    public function setFCM($data) {
      //$userEmail = $data['email'];
      //$gcmId = $data['fcm_id'];
      if(!isset($data['fcm_id'])) {
        return ['status_code'=>'300','message'=>'FCM ID is Missing'];
      }
       if(!isset($data['email'])) {
        return ['status_code'=>'300','message'=>'Email Address is Missing'];
      }
      $data = $this->userRepoObj->setFCM($data);
      if($data) {
        return ['status_code'=>'200','message'=>'GCM Updated successfully'];
      } else {
        return ['status_code'=>'200','message'=>'Problem on updating'];
      }
    }

    public function getAllAppUsers($data) {

       $appUsers = User::whereHas('roles', function($query)
                  {
                      $query->where('slug', 'mobile.user');
                  })->get();
        if($appUsers) {
          return ['status_code'=>200,'message'=>'success','data'=>$appUsers];
        }

    }

}
