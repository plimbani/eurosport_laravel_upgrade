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

        $userData['people']['first_name']=$data['first_name'];
        $userData['people']['last_name']=$data['last_name'];
        $peopleObj = $this->peopleRepoObj->create($userData['people']);

        $userData['user']['person_id']=$peopleObj->id;
        $userData['user']['username']=$data['username'];
        $userData['user']['name']=$data['first_name']." ".$data['last_name'];
        $userData['user']['email']=$data['email'];
        $userData['user']['password']=Hash::make($data['password']);

        $userObj=$this->userRepoObj->create($userData['user']);

        $userObj->attachRole($data['role_id']);

        $email_details = array();
        $email_details['name'] = $data['first_name'];
        $recipient = $data['email'];
        Common::sendMail($contact_details, $recipient, 'Eurosport - Set Password', 'emails.users.create');

        if ($data) {
            return ['status_code' => '200', 'message' => 'Data Sucessfully Inserted'];
        }
    }

    /**
     * Edit User.
     *
     * @param array $data
     *
     * @return [type]
     */
    public function edit($data, $userId)
    {
        $data = $data->all();

        $userData=array();
        $userData['people']=array();
        $userData['user']=array();

        $userData['user']['username']=$data['username'];
        $userData['user']['name']=$data['first_name']." ".$data['last_name'];
        $userData['user']['email']=$data['email'];
        $userData['user']['password']=Hash::make($data['password']);

        $this->userRepoObj->edit($userData['user'], $userId);

        $userObj=User::findOrFail($userId);
        $userObj->detachAllRoles();
        $userObj->attachRole($data['role_id']);

        $userData['people']['first_name']=$data['first_name'];
        $userData['people']['last_name']=$data['last_name'];
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
    public function delete($data)
    {
        $data = $data->all();
        $data = $this->userRepoObj->delete($data);
        if ($data) {
            return ['status_code' => '200', 'message' => 'Data Successfully Deleted'];
        }
    }
}
