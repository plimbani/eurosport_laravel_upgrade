<?php

namespace Laraspace\Api\Controllers;

use Brotzka\DotenvEditor\DotenvEditor;
use Dingo\Api\Routing\Helpers;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

// Need to Define Only Contracts
use Laraspace\Api\Contracts\UserContract;
use JWTAuth;
use Laraspace\Models\User;
use Laraspace\Api\Repositories\UserRepository;
use Laraspace\Custom\Helper\Common;

/**
 * Users Resource Description.
 *
 * @Resource("users")
 *
 * @Author mtilokani@aecordigital.com
 */
class UserController extends BaseController
{
    public function __construct(UserContract $userObj)
    {
        $this->userObj = $userObj;
        $this->userRepoObj = new UserRepository();
        // $this->middleware('auth');
        // $this->middleware('jwt.auth');
    }

    /**
     * Show all User Results Details.
     *
     * Get a JSON representation of all the Users.
     *
     * @Get("/users")
     * @Versions({"v1"})
     * @Response(200, body={"id": 10, "username": "foo"})
     */
    public function getUsers()
    {
        return $this->userObj->getAllUsers();
    }
    public function getUserDetails(Request $request)
    {
        return $this->userObj->getUserDetails($request->all());
    }

    /**
     * Show all User Results Details.
     *
     * Get a JSON representation of all the Users.
     *
     * @Get("/users")
     * @Versions({"v1"})
     * @Response(200, body={"id": 10, "username": "foo"})
     */
    public function getUsersByRegisterType($registerType)
    {

        return $userData =  $this->userObj->getUsersByRegisterType($registerType);

    }

    /**
     * Create New User Result.
     *
     * @Post("/user/create")
     *
     * @Versions({"v1"})
     * @Request("name=test", contentType="application/x-www-form-urlencoded")
     */
    public function createUser(Request $request)
    {
        return $this->userObj->create($request);
    }

    /**
     * Edit User
     *
     * @GET("/user/edit/{$id}")
     *
     */
    public function edit(Request $request, $userId)
    {
        return $this->userObj->edit($userId);
    }

    /**
     * Update User
     *
     * @Post("/user/edit/{$id}")
     *
     * @Request("name=test", contentType="application/x-www-form-urlencoded")
     */
    public function update(Request $request, $userId)
    {
        return $this->userObj->update($request, $userId);
    }

    /**
     * Delete User
     *
     * @param  [type] $id User Id
     *
     * @return [type]           [description]
     */
    public function deleteUser($id)
    {
        return $this->userObj->delete($id);
    }
    public function changeUserStatus(Request $request)
    {
      return $this->userObj->changeUserStatus($request->all());
    }


      public function setPassword($key, Request $request)
    {
      $usersPasswords = User::where(['token'=>$key])->get();
      $message = "";
      $error = false;
      if (count($usersPasswords) == 0) {
          $isUserVerified = User::withTrashed()->where(['token'=>$key])->get();
          if(count($isUserVerified) > 0) {
              $error=true;
              $message = "You have already set the password.";
          } else {
              return response()->view('errors.404', [], 404);
          }
      }
      // echo "<pre>";print_r($usersPasswords);echo "</pre>";exit;
      return view('emails.users.setpassword', compact('usersPasswords'));
      // return view('emails.users.setpassword');
    }

    public function passwordActivate(Request $request)
    {
      $key = $request->key;
      $password = $request->password;
      $usersDetail['key'] = $key;
      $usersDetail['password'] = $password;
      $result = $this->userRepoObj->createPassword($usersDetail);
      return redirect('/login');
    }

    public function resendEmail(Request $request)
    {
      $userData = User::where(['email'=>$request->email])->first();
      $email_details =[];
      // dd($userData->name);
      $email_details['name'] = $userData->personDetail->first_name;
      $email_details['token'] =  $userData->token;
      $recipient = $userData->email;
      // dd($email_details,$recipient);

      Common::sendMail($email_details, $recipient, 'Euro-Sportring Tournament Planner - Set Password', 'emails.users.create');
      // return redirect('/login');
    }
}
