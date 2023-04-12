<?php

namespace Laraspace\Http\Controllers;

use JWTAuth;
use Socialite;
use Validator;
use Laraspace\Models\Role;
use Laraspace\Models\User;
use Laraspace\Models\Person;
use Illuminate\Http\Request;
use Laraspace\Models\Settings;
use Laraspace\Models\UserFavourites;
use Laraspace\Models\TournamentUser;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Validation\ValidationException;
use Laraspace\Http\Requests\Auth\TokenCheckRequest;

class AuthController extends Controller
{
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');
        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'The login details entered are invalid. If you have forgotten your password please follow the forgot password process.'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        $authUser = JWTAuth::authenticate($token);
        $person = $authUser->profile()->first();
        $country = $authUser->country_id;
        $role = [$authUser->roles()->first()];

        $userTournamentCount = TournamentUser::with('tournaments')->has('tournaments')->where('user_id', $authUser->id)->count();
        
        // all good so return the token

        //return response()->json(compact('token'));
        //$token = response()->json(compact('token'));
       // $token = compact('token');
        return response()->json(compact('token', 'role', 'country', 'userTournamentCount'));

    }

    public function check(Request $request)
    {
        try {
            JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            return response(['authenticated' => false]);
        }

        $token=JWTAuth::getToken();
        if($token) {
          $userData = JWTAuth::toUser($token);
          $isMobileUsers = \Request::header('IsMobileUser');
          $userTournament = $userData->tournaments()->pluck('id')->toArray();
          if ($userData->isRole('tournament.administrator') && $request->has('tournamentId') && !in_array($request->tournamentId,$userTournament)) {
            return response(['authenticated' => true, "hasAccess" => false, "message"=>"You don't have an access to this tournament." ]);
          }

          if( $userData->is_verified == 0 ) {
            return response(['authenticated' => false, 'message'=>'This email account still requires verification.']);
          }

          if( ($userData->is_mobile_user == 0 && $isMobileUsers == true) || ($userData->is_desktop_user == 0 && $isMobileUsers != true) ) {
            return response(['authenticated' => false, 'message'=>'Account is de-activated. Please contact your administrator.']);
          }

          if($userData->is_active == 0) {
            return response(['authenticated' => false,'message'=>'Account de-activated please contact your administrator.']);
          }
            $path = getenv('S3_URL').'/assets/img/users/';
            $userDataQuery = \Laraspace\Models\User::where('users.id',$userData->id)
                              ->leftJoin('people','people.id','=','users.person_id')
                              ->leftjoin('countries', 'countries.id', '=', 'users.country_id')
                              ->join('role_user', 'users.id', '=', 'role_user.user_id')
                              ->join('roles', 'roles.id', '=', 'role_user.role_id')
                              ->select('users.id',
                                'users.locale',
                                'people.first_name',
                                'people.last_name','users.email',
                                'users.user_image',
                                \DB::raw('IF(users.user_image is not null,CONCAT("'.$path.'", users.user_image),"" ) as userImage'),'users.role as role','countries.id as country_id')
                              ->get();

            $userFavourite = UserFavourites::where('user_id', $userData->id)->where('is_default', '=', 1)->first();

            $userDetails = array();
            if(isset($userDataQuery)) {
             $userData = $userDataQuery[0];
             $userDetails['first_name'] = $userData->first_name;
             $userDetails['sur_name'] = $userData->last_name;
             $userDetails['email'] = $userData->email;
             $userDetails['profile_image_url'] = $userData->userImage;
             $userDetails['tournament_id'] = $userFavourite ? $userFavourite->tournament_id : null;
             $userDetails['user_id'] = $userData->id;
             $userDetails['locale'] = $userData->locale;
             $userSettings = Settings::where('user_id','=',$userData->id)->first();
             $userDetails['settings'] = $userSettings ? $userSettings->toArray() : null;
             $userDetails['role_id'] = $userData->roles()->first()->id;
             $userDetails['role'] = $userData->role;
             $userDetails['role_name'] = $userData->roles()->first()->slug;
             $userDetails['locale'] = $userData->locale;
             $userDetails['country_id'] = $userData->country_id;
             
             $tournament_id = array();
             return response(['authenticated' => true,'userData'=> $userDetails, 'is_score_auto_update' =>config('config-variables.is_score_auto_update'), 'enable_logs_ios' =>config('config-variables.enable_logs_ios'), 'enable_logs_android' =>config('config-variables.enable_logs_android'), 'currentLayout' => config('config-variables.current_layout')]);
            }
        }
    }

    public function logout()
    {
        try {
            $token = JWTAuth::getToken();

            if ($token) {

                JWTAuth::invalidate($token);
            }

        } catch (JWTException $e) {
            return response()->json($e->getMessage(), 401);
        }

        return response()->json(['message' => 'Log out success'], 200);
    }

    /**
     * Social login
     */
    public function socialLogin(TokenCheckRequest $request)
    {
        $token = $request->token;
        $provider = $request->provider;

        if($provider == 'facebook') {
          Socialite::driver($provider)->fields(['name', 'first_name', 'last_name', 'email']);
          $payload = Socialite::driver($provider)->userFromToken($token);
          $user = $this->getFacebookUserData($payload);
        }

        if($provider == 'apple') {
          $user = [];
          $user['id'] = $request->user_identifier;
          $user['email'] = $request->email;
          $user['first_name'] = $request->first_name;
          $user['last_name'] = $request->last_name;
        }

        $authUser = User::where('provider_id', $user['id'])->first();
        if (!$authUser) {
          $userData = [];
          if(isset($user['first_name']))
              $userData['first_name'] = $user['first_name'];

          if(isset($user['last_name']))
              $userData['last_name'] = $user['last_name'];

          if(isset($user['email']))
              $userData['email'] = $user['email'];

          if(isset($user['id']))
              $userData['provider_id'] = $user['id'];

          $userData['provider'] = $provider;

          $isUserDeleted = User::onlyTrashed()->where('email', $user['email'])->first();
          if($isUserDeleted){
            $authUser = $this->restoreDeletedUser($isUserDeleted, $userData);
          } else {
            if(isset($user['email'])) {
              $validator = Validator::make(['email' => $user['email']], [
                'email' => 'required|email|unique:users,email',
              ]);

              if ($validator->fails()) {
                return response()->json(['message' => 'User already exists.'], 422);
              }
            }              
            $authUser = $this->storeFacebookUserDetail($userData);
          }
        }

        $token = JWTAuth::fromUser($authUser);
        if (!$token) {
            throw new \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException('Basic', 'Invalid credentials.');
        }

        return response()->json(compact('token'));
    }

    /**
     * Get user data from Facebook provider
     */
    public function getFacebookUserData($user)
    {
        $facebookUserDetail = [
            'id' => $user->id,
            'email' => isset($user->user['email']) ? $user->user['email'] : null,
        ];
        $userName = isset($user->user['name']) ? explode(' ', $user->user['name'], 2) : null;
        $facebookUserDetail['first_name'] = isset($user['first_name']) ? $user['first_name'] : (isset($userName[0]) ? $userName[0] : null);
        $facebookUserDetail['last_name'] = isset($user['last_name']) ? $user['last_name'] : (isset($userName[1]) ? $userName[1] : null);

        return $facebookUserDetail;
    }

    /**
     * Store facebook user detail
     */
    public function storeFacebookUserDetail($userData)
    {
        $mobileUserRoleId = Role::where('slug', 'mobile.user')->first()->id;

        //saving people table data
        $person = new Person();
        $person->first_name = isset($userData['first_name']) ? $userData['first_name'] : '';
        $person->last_name = isset($userData['last_name']) ? $userData['last_name'] : '';
        $person->save();

        //saving users table data
        $user = new User();
        $user->person_id = $person->id;
        $user->name = $userData['first_name']. ' ' .$userData['last_name'];
        $user->email = isset($userData['email']) ? $userData['email'] : null;
        $user->username = isset($userData['email']) ? $userData['email'] : null;
        $user->provider = $userData['provider'];
        $user->provider_id = $userData['provider_id'];
        $user->is_mobile_user = 1;
        $user->is_verified = 1;
        $user->is_active = 1;
        $user->save();
        $user->attachRole($mobileUserRoleId);

        //saving user settings data
        $setting = new Settings();
        $setting->user_id = $user->id;
        $setting->value = '{"is_sound":"true","is_vibration":"true","is_notification":"true"}';
        $setting->save();

        return $user;
    }

    /**
     * Re-store deleted user detail
     */
    public function restoreDeletedUser($deletedUser, $userData)
    {
      $mobileUserRoleId = Role::where('slug', 'mobile.user')->first()->id;
      $deletedUser->restore();

      // updating value in people table
      $person = Person::find($deletedUser->id);
      $person->first_name = $userData['first_name'];
      $person->last_name = $userData['last_name'];
      $person->save();

      // updating values in users table
      $deletedUserData = User::find($deletedUser->id);
      $deletedUserData->person_id = $person->id;
      $deletedUserData->name = $userData['first_name']. ' ' .$userData['last_name'];
      $deletedUserData->provider = $userData['provider'];
      $deletedUserData->provider_id = $userData['provider_id'];
      $deletedUserData->is_verified = 1;
      $deletedUserData->is_active = 1;
      $deletedUserData->is_mobile_user = 1;
      $deletedUserData->save();
      $deletedUserData->roles()->sync($mobileUserRoleId);

      // updating values in settings table if there is no any data
      $setting = Settings::where('user_id', $deletedUser->id)->first();
      if(!$setting) {
        $setting->user_id = $deletedUser->id;
        $setting->value = '{"is_sound":"true","is_vibration":"true","is_notification":"true"}';
        $setting->save();
      }

      return $deletedUserData;
    }

    public function token_validate()
    {
      if(!JWTAuth::getToken()) {
        return response(['authenticated' => false]);
      }
      
      try {
          JWTAuth::parseToken()->authenticate();
          return response(['authenticated' => true]);
      } catch (JWTException $e) {
          return response(['authenticated' => false]);
      }
    }
}
