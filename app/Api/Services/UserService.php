<?php

namespace Laraspace\Api\Services;

use Laraspace\Model\UserAffiliates;
use DB;
use Laraspace\Api\Contracts\UserContract;
use Validator;
use Illuminate\Support\Facades\Password;
use App\Custom\Helper\Common;
use Illuminate\Mail\Message;
use Laraspace\Models\User;
use Hash;

use App\Mail\SendMail;
use Illuminate\Support\Facades\Mail;

class UserService implements UserContract
{
    public function __construct()
    {
        $this->userRepoObj = new \Laraspace\Api\Repositories\UserRepository();
        $this->peopleRepoObj = new \Laraspace\Api\Repositories\PeopleRepository();
    }

    public function getAllUsers()
    {
        return $this->userRepoObj->getAllUsers();
    }

    public function getUsersByRegisterType($registerType)
    {
        return $this->userRepoObj->getUsersByRegisterType($registerType);
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
        $data = $data->all();
        $userData=array();
        $userData['people']=array();
        $userData['user']=array();

        $userData['people']['first_name']=$data['name'];
        $userData['people']['last_name']=$data['surname'];
        $peopleObj = $this->peopleRepoObj->create($userData['people']);
        $userData['user']['person_id']=$peopleObj->id;
        $userData['user']['username']=$data['emailAddress'];
        $userData['user']['name']=$data['name']." ".$data['surname'];
        $userData['user']['email']=$data['emailAddress'];
        $userData['user']['organisation']=$data['organisation'];

       if($data['user_image']!='')
        {
            $imagename = $this->saveUsersLogo($data);
            $userData['user']['user_image']=$imagename;
        }
        
        $userData['user']['password'] = Hash::make('password');
        // dd($userData['user']);
        $userObj = $this->userRepoObj->create($userData['user']);
        
        $userObj->attachRole($data['userType']);

        if ($data) {
            $email_details = array();
            $email_details['name'] = $data['name'];
            $recipient = $data['emailAddress'];
            // echo "<pre>";print_r($recipient);echo "</pre>";exit;
            // $mailSent = Mail::to('kparikh@aecordigital.com')->send('Hello');
            // dd($mailSent);
            // $email_sent = Common::sendMail($email_details, $recipient, 'Eurosport - Set Password', 'emails.users.create');
            // $email_sent = Mail::to($recipient)->send(new SendMail($email_details));
            
            return ['status_code' => '200', 'message' => 'Data Sucessfully Inserted'];
        }
    }
    public function saveUsersLogo($data)
    { 
       if($data['user_image'] != '')
       {    
            $image_string = $data['user_image']; 

            $img = explode(',', $image_string);        
            $imgData = base64_decode($img[1]);        

            $name = $data['name'];
            
            $now = new \DateTime();
            
            $timeStamp = $now->getTimestamp();
            $path = public_path().'/assets/img/users/'.$timeStamp.'.png';      
            file_put_contents($path, $imgData);      
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

        $imagename ='';
        if($data['user_image']!='')
        {
            $imagename = $this->saveUsersLogo($data);
        }

        $userData['user']['name']=$data['name']." ".$data['surname'];
        $userData['user']['email']=$data['emailAddress'];
        $userData['user']['organisation']=$data['organisation'];
        $userData['user']['user_image']=$imagename;

        $this->userRepoObj->update($userData['user'], $userId);

        $userObj=User::findOrFail($userId);
        $userObj->detachAllRoles();
        $userObj->attachRole($data['userType']);

        $userData['people']['first_name']=$data['name'];
        $userData['people']['last_name']=$data['surname'];
        $peopleObj = $this->peopleRepoObj->edit($userData['people'], $userObj->person_id);

        
        if ($data) {
            return ['status_code' => '200', 'message' => 'Data Successfully Updated'];
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
}
