<?php

namespace Laraspace\Http\Controllers;

use JWTAuth;
use Socialite;
use Validator;
use Laraspace\Models\User;
use Illuminate\Http\Request;
use Laraspace\Models\Settings;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Validation\ValidationException;
use Laraspace\Http\Requests\Auth\TokenCheckRequest;

class AuthController extends Controller
{
    public function authenticate(Request $request)
    {
        // dd($request->all());
        // grab credentials from the request
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

        // all good so return the token
        //return response()->json(compact('token'));
        //$token = response()->json(compact('token'));
       // $token = compact('token');
        return response()->json(compact('token'));

    }

    public function check(Request $request)
    {
        try {
            JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            return response(['authenticated' => false]);
        }
        // Here Add Functionality if use is Active then allowed to login
        $token=JWTAuth::getToken();
        if($token) {
          $userData = JWTAuth::toUser($token);
          // here we put check for Mobile Users
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
                              ->leftJoin('users_favourite','users_favourite.user_id','=','users.id')
                              ->leftJoin('people','people.id','=','users.person_id')
                              ->leftjoin('countries', 'countries.id', '=', 'users.country_id')
                              ->join('role_user', 'users.id', '=', 'role_user.user_id')
                              ->join('roles', 'roles.id', '=', 'role_user.role_id')
                              ->select('users.id',
                                'users.locale',
                                'people.first_name',
                                'people.last_name','users.email',
                                'users.user_image',
                                \DB::raw('IF(users.user_image is not null,CONCAT("'.$path.'", users.user_image),"" ) as userImage'),
                                'users_favourite.tournament_id','users.role as role','countries.id as country_id')
                              ->get();
                              
            $userDetails = array();
            if(isset($userDataQuery)) {
             $userData = $userDataQuery[0];

             $userDetails['first_name'] = $userData->first_name;
             $userDetails['sur_name'] = $userData->last_name;
             $userDetails['email'] = $userData->email;
             $userDetails['profile_image_url'] = $userData->userImage;
             $userDetails['tournament_id'] = $userData->tournament_id;
             $userDetails['user_id'] = $userData->id;
             $userDetails['locale'] = $userData->locale;
             $userSettings = Settings::where('user_id','=',$userData->id)->first();
             $userDetails['settings'] = $userSettings ? $userSettings->toArray() : null;
             $userDetails['role'] = $userData->role;
             $userDetails['locale'] = $userData->locale;
             $userDetails['country_id'] = $userData->country_id;

             $tournament_id = array();
             return response(['authenticated' => true,'userData'=> $userDetails, 'is_score_auto_update' =>config('config-variables.is_score_auto_update')]);
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

    public function socialLogin(TokenCheckRequest $request)
    {
        $token = $request->token;
        $provider = $request->provider;

        try {
            switch ($provider) {
                case "facebook":
                    Socialite::driver($provider)->fields(['name', 'first_name', 'last_name', 'email']);
                    $payload = Socialite::driver($provider)->userFromToken($token);
                    $user = $this->getFacebookUserData($payload);
                    break;
                default:
                    $user = Socialite::driver($provider)->userFromToken($token);
            }
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            throw new \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException('Basic', $e->getMessage());
        }

        $authUser = User::where('provider_id', $user['id'])->first();

        if (!$authUser) {
            if(isset($user['email'])) {
                $validator = Validator::make(['email' => $user['email']], [
                    'email' => 'required|email|unique:users,email',
                ]);

                if ($validator->fails()) {
                    throw new ValidationException($validator);
                }
            }

            $userData = [];
            if(isset($user['first_name']))
                $userData['first_name'] = $user['first_name'];

            if(isset($user['last_name']))
                $userData['last_name'] = $user['last_name'];

            if(isset($user['email']))
                $userData['email'] = $user['email'];

            if(isset($user['id']))
                $userData['provider_id'] = $user['id'];

            if(isset($provider))
                $userData['provider'] = $provider;

            $authUser = $this->storeUserDetail($userData);
        }

        $token = JWTAuth::fromUser($authUser);
        $user = User::with(['personDetail','favouriteTournaments'])->where('id', $authUser->id)->first();

        if (!$token) {
            throw new \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException('Basic', 'Invalid credentials.');
        }

        $userDetailArray = [];
        if(isset($user)) {
            $userDetailArray['first_name'] = $user->personDetail->first_name;
            $userDetailArray['sur_name'] = $user->personDetail->last_name;
            $userDetailArray['email'] = $user->email ? $user->email : null;
            $userDetailArray['tournament_id'] = $user->favouriteTournaments[0]->tournament_id;
            $userDetailArray['user_id'] = $user->id;
            $userDetailArray['locale'] = $user->locale;
            $userSettings = Settings::where('user_id','=',$user->id)->first();
            $userDetailArray['settings'] = $userSettings ? $userSettings->toArray() : null;
            $userDetailArray['role'] = $user->role;
            $userDetailArray['country_id'] = $user->country_id;
        }

        return response(['authenticated' => true, 'userData'=> $userDetailArray, 'is_score_auto_update' =>config('config-variables.is_score_auto_update')]);
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

    public function storeUserDetail($userData)
    {
        $user = new User();
        $user->name = $userData['first_name']. ' ' .$userData['last_name'];
        $user->email = $userData['email'] ? $userData['email'] : null;
        $user->provider = $userData['provider'];
        $user->provider_id = $userData['provider_id'];
        $user->save();

        return $user;
    }
}
