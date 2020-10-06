<?php

namespace Laraspace\Api\Controllers;

use Illuminate\Http\Request;
use Dingo\Api\Routing\Helpers;
use Illuminate\Routing\Controller;
use Brotzka\DotenvEditor\DotenvEditor;

// Need to Define Only Contracts
use JWTAuth;
use UrlSigner;
use Carbon\Carbon;
use Laraspace\Models\User;
use Laraspace\Models\Role;
use Laraspace\Custom\Helper\Common;
use Laraspace\Api\Contracts\UserContract;
use Laraspace\Http\Requests\User\EditRequest;
use Laraspace\Http\Requests\User\StoreRequest;
use Laraspace\Api\Repositories\UserRepository;
use Laraspace\Http\Requests\User\DeleteRequest;
use Laraspace\Http\Requests\User\BrowseRequest;
use Laraspace\Http\Requests\User\UpdateRequest;
use Laraspace\Http\Requests\User\UpdateFcmRequest;
use Laraspace\Http\Requests\User\UserStatusRequest;
use Laraspace\Http\Requests\User\ResendEmailRequest;
use Laraspace\Http\Requests\User\GetSettingRequest;
use Laraspace\Http\Requests\User\PostSettingRequest;
use Laraspace\Http\Requests\User\SetFavouriteRequest;
use Laraspace\Http\Requests\User\GetUserWebsitesRequest;
use Laraspace\Http\Requests\User\ChangePermissionRequest;
use Laraspace\Http\Requests\User\GetUserDetailsRequest;
use Laraspace\Http\Requests\User\RemoveFavouriteRequest;
use Laraspace\Http\Requests\User\GetUserTournamentsRequest;
use Laraspace\Http\Requests\User\SetDefaultFavouriteRequest;
use Laraspace\Http\Requests\User\TournamentPermissionRequest;
use Laraspace\Http\Requests\User\GetSignedUrlForUsersTableDataRequest;
use Illuminate\Http\Response;

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
    public function getUserDetails(GetUserDetailsRequest $request)
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
    public function getUsersByRegisterType(BrowseRequest $request)
    {
        return $userData = $this->userObj->getUsersByRegisterType($request->all());
    }

    public function getUserTableData(Request $request)
    {
      return $userData = $this->userObj->getUserTableData($request->all());
    }


    /**
     * Create New User Result.
     *
     * @Post("/user/create")
     *
     * @Versions({"v1"})
     * @Request("name=test", contentType="application/x-www-form-urlencoded")
     */
    public function createUser(StoreRequest $request)
    {
        return $this->userObj->create($request);
    }

    /**
     * Edit User
     *
     * @GET("/user/edit/{$id}")
     *
     */
    public function edit(EditRequest $request, $userId)
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
    public function update(UpdateRequest $request, $userId)
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
    public function deleteUser(DeleteRequest $request, $id)
    {
        return $this->userObj->delete($id);
    }
    public function changeUserStatus(UserStatusRequest $request)
    {
      return $this->userObj->changeUserStatus($request->all());
    }


    public function setPassword($key, Request $request)
    {

      $usersPasswords = User::where(['token'=>$key])->get();

      $message = "";
      $error = false;
      $redirect = '';

      if (count($usersPasswords) == 0) {
          $isUserVerified = User::withTrashed()->where(['token'=>$key])->get();
          if(count($isUserVerified) > 0) {
              $error=true;
              $message = "You have already set the password.";
          } else {
              //return response()->view('errors.404', [], 404);
              $error=true;
              $message = "Link is Expired.";
          }
      }

      // TODO: Here we put Code for Mobile Verification
      if(isset($usersPasswords) && count($usersPasswords) > 0 && $usersPasswords[0]['registered_from'] == 0) {

        //TODO: Need to put code for change Status For User with user Update
        //$usersDetail['key'] = $key;
          $usersPassword = User::where('token', $key)->first();
          //$users = User::where("id", $usersPassword->id)->first();
          $usersPassword->is_verified = 1;
          $usersPassword->is_active = 1;
          $usersPassword->token = '';
          $user =  $usersPassword->save();

          $redirect = '/mlogin';
        // Already set the password
       // $usersDetail['password'] = $usersPasswords[0]['password'];
       // $result = $this->userRepoObj->createPassword($usersDetail);
          //return redirect('/mlogin');
      }

      return response()->json([
                    'success' => true,
                    'status' => Response::HTTP_OK,
                    'error' => $error,
                    'message' => $message,
                    'redirect' => $redirect,
        ]);

      //return view('emails.users.setpassword', compact('usersPasswords'));
      // return view('emails.users.setpassword');
    }

    public function passwordActivate(Request $request)
    {
      $key = $request->key;
      $password = $request->password;
      $usersDetail['key'] = $key;
      $usersDetail['password'] = $password;

      $result = $this->userRepoObj->createPassword($usersDetail);
      return ($result == 'Mobile') ?  '/mlogin' : '/login/verified';
    }

    // for desktop - resend email verification
    public function resendEmail(ResendEmailRequest $request)
    {
      return $this->sendEmailVerification($request->all());
    }

    public function setFavourite(SetFavouriteRequest $request)
    {
      return $this->userObj->setFavourite($request->all());
    }
    public function removeFavourite(RemoveFavouriteRequest $request)
    {
      return$this->userObj->removeFavourite($request->all());
    }
    public function setDefaultFavourite(SetDefaultFavouriteRequest $request)
    {
      return $this->userObj->setDefaultFavourite($request->all());
    }
    public function postSetting(PostSettingRequest $request)
    {
      return $this->userObj->postSetting($request->all());
    }
    public function getSetting(GetSettingRequest $request)
    {
      return $this->userObj->getSetting($request->all());
    }
    public function setUserImage(Request $request)
    {
      return $this->userObj->setUserImage($request->all());
    }
    public function updatefcm(UpdateFcmRequest $request) {
      return $this->userObj->setFCM($request->all());
    }
    public function getAllAppUsers(Request $request) {
      return $this->userObj->getAllAppUsers($request->all());
    }

    public function changeTournamentPermission(TournamentPermissionRequest $request) {
      return $this->userObj->changeTournamentPermission($request->all());
    }

    public function changePermissions(ChangePermissionRequest $request) {
      return $this->userObj->changePermissions($request->all());  
    }

    public function getUserTournaments(GetUserTournamentsRequest $request, $id) {
      return $this->userObj->getUserTournaments($id);
    }

    public function getSignedUrlForUsersTableData(GetSignedUrlForUsersTableDataRequest $request)
    { 

        $reportData = $request->all();
        $token = JWTAuth::getToken();
        $reportData['token'] = strval($token);
        ksort($reportData);
        $reportData  = http_build_query($reportData);
        $signedUrl = UrlSigner::sign(url('api/users/getUserTableData?' . $reportData), Carbon::now()->addMinutes(config('config-variables.signed_url_interval')));
        return $signedUrl;
    }

    public function getAllCountries(Request $request) {
        return $this->userObj->getAllCountries();
    }

    public function getAllLanguages(Request $request) {
        return $this->userObj->getAllLanguages();
    }

    // for app - resend email verification
    public function userResendEmail(Request $request)
    {
      return $this->sendEmailVerification($request->all());
    }

    public function sendEmailVerification($data)
    {
      $userData = User::where(['email' => $data['email']])->first();
      $email_details =[];
      $email_details['name'] = $userData->personDetail->first_name;
      $email_details['token'] =  $userData->token;
      $email_details['is_mobile_user'] = 0;
      $recipient = $userData->email;
      $email_templates = null;
      $email_msg = null;

      if($userData->registered_from === 0)
      {
        $email_templates = 'emails.users.mobile_user';
        $email_msg = 'Euro-Sportring - Email Verification';
      } else {
        $mobileUserRoleId = Role::where('slug', 'mobile.user')->first()->id;
        if($userData->roles[0]->id == $mobileUserRoleId) {
          $email_templates = 'emails.users.mobile_user_registered_from_desktop';
          $email_msg = 'Euro-Sportring - Set password';
        } else {
          $email_templates = 'emails.users.desktop_user';
          $email_msg = 'Euro-Sportring Tournament Planner - Set password';
        }
      }

      Common::sendMail($email_details, $recipient, $email_msg, $email_templates);

      return ['status_code' => '200', 'message' => 'Please check your inbox to verify your email address.'];
    }

    public function updateAppDeviceVersion(Request $request) {
      return $this->userObj->updateAppDeviceVersion($request->all());
    }

    public function validateUserEmail(Request $request) {
      return $this->userObj->validateUserEmail($request->all());
    }

    public function verifyResultAdminUser(Request $request)
    {
      return $this->userObj->verifyResultAdminUser($request->all());
    }
}
