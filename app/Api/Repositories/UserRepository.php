<?php

namespace Laraspace\Api\Repositories;

use Laraspace\Models\User;
use Laraspace\Models\Role;
use Laraspace\Models\UserFavourites;
use Laraspace\Models\Settings;
use Laraspace\Models\Country;
use Laraspace\Models\Tournament;
use Laraspace\Models\TournamentUser;
use Laraspace\Custom\Helper\Common;
use DB;
use Hash;
use JWTAuth;
use Laraspace\Mail\SendMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Pagination\Paginator;
use Laraspace\Traits\AuthUserDetail;
use Laraspace\Models\RoleUser;

class UserRepository {

    use AuthUserDetail;

    public function __construct()
    {
        $this->userImagePath = getenv('S3_URL') . '/assets/img/users/';
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
        $email = $data['userData']['email'];
        $user = User:: join('role_user', 'users.id', '=', 'role_user.user_id')
                ->join('roles', 'roles.id', '=', 'role_user.role_id')
                ->where('users.email', trim($email))
                ->select("users.*","users.country_id as country","roles.name as role_name","roles.slug as role_slug", DB::raw('CONCAT("' . $this->userImagePath . '", users.user_image) AS user_image')
                )
                ->get();

        return $user;
    }

    public function getUsersByRegisterType($data)
    {
        ini_set('memory_limit','256M');
        $loggedInUser = $this->getCurrentLoggedInUserDetail();
        $currentLayout = config('config-variables.current_layout');

        if($loggedInUser == null){
          if($data['token']){
            $loggedInUser = JWTAuth::authenticate($data['token']);
          }
        }

        $tournamentIds = $loggedInUser->tournaments->pluck('id')->toArray();
        
        $user = User::join('role_user', 'users.id', '=', 'role_user.user_id')
                ->leftjoin('roles', 'roles.id', '=', 'role_user.role_id')
                ->leftjoin('people', 'people.id', '=', 'users.person_id')
                ->leftjoin('countries', 'countries.id', '=', 'users.country_id');


        if($loggedInUser->hasRole('tournament.administrator')) { 
          $tournamentUserIds = TournamentUser::leftjoin('role_user', 'tournament_user.user_id', '=', 'role_user.user_id')
                                              ->leftjoin('roles', 'roles.id', '=', 'role_user.role_id')
                                              ->whereIn('tournament_id', $tournamentIds)
                                              ->where('tournament_user.user_id', '!=', $loggedInUser->id)
                                              ->where('slug', 'Results.administrator')
                                              ->pluck('tournament_user.user_id')
                                              ->toArray();

          $finalTournamentUnique = array_unique($tournamentUserIds);
          $user = $user->whereIn('users.id', $finalTournamentUnique);
        }

        if(isset($data['userData']) && $data['userData'] !== '') {
            $user = $user->where(function($query) use($data) {
                $query->where('users.email', 'like', "%" . $data['userData'] . "%")
                        ->orWhere('people.first_name', 'like', "%" . $data['userData'] . "%")
                        ->orWhere('people.last_name', 'like', "%" . $data['userData'] . "%");
            });
        }

        if (isset($data['userType']) && $data['userType'] !== '') {
            $user = $user->where('roles.slug', '=', $data['userType']);
        }

        if($loggedInUser->hasRole('Master.administrator')) {
          $user = $user->where('roles.slug', '!=', 'mobile.user')->where('roles.slug', '!=', 'Super.administrator');
        }

        if($currentLayout === 'commercialisation') {
          $user = $user->whereNotIn('roles.slug', ['tournament.administrator', 'Master.administrator', 'Results.administrator']);
        }

        $languages = config('wot.languages');
        $user = $user->select('users.id as id', 'people.first_name as first_name', 'people.last_name as last_name', 'users.email as email', 'roles.id as role_id', 'roles.name as role_name', 'roles.slug as role_slug', 'users.is_verified as is_verified', 'users.is_mobile_user as is_mobile_user', 'users.is_desktop_user as is_desktop_user', 'users.organisation as organisation', 'users.locale as locale', 'users.role as role','countries.name as country', 'users.device as device', 'users.app_version as app_version', 'users.provider as provider');

        $user->orderBy('people.last_name','asc');
        $userData = $user->withCount('tournaments')->get(); 

        $dataArray = array();

        if(isset($data['report_download']) &&  $data['report_download'] == 'yes') {
            foreach ($userData as $user) {

                $status = ($user->is_verified == 1) ? 'Verified': 'Resend';
                $isDesktopUser = ($user->is_desktop_user == 1) ? 'Yes': 'No';
                $isMobileUser = ($user->is_mobile_user == 1) ? 'Yes': 'No';
                $userListLanguages = $user->locale != '' ? $languages[$user->locale] : '';
                
                $ddata = [
                        $user->first_name,
                        $user->last_name,
                        $user->email,
                        $user->provider,
                        $user->role_name,
                        $user->role,
                        $user->country,
                        $userListLanguages,
                        $status,
                        $user->device,
                        $user->app_version,
                        $isDesktopUser,
                        $isMobileUser,
                    ];

                array_push($dataArray, $ddata);
            }

            $otherParams = [
                'sheetTitle' => "UserReport",
                'sheetName' => "UserReport",
                'boldLastRow' => false
            ];

            $lableArray = [
                'Name', 'Surname' ,'Email address', 'Source', 'User type', 'Role', 'Country', 'Language', 'Status', 'Device', 'App version', 'Desktop', 'Mobile'
            ];
            //Total Stakes, Total Revenue, Amount & Balance fields are set as Number statically.
            \Laraspace\Custom\Helper\Common::toExcel($lableArray,$dataArray,$otherParams,'xlsx','yes');
        }

        $currentPage = $data['currentPage']; // You can set this to any page you want to paginate to
        // before querying users
        Paginator::currentPageResolver(function () use ($currentPage) {
          return $currentPage;
        });

        return  $user->paginate($data['noOfRecords']);
    }

    public function create($data)
    {
        $token = JWTAuth::getToken();
        if($token) {
            $authUser = JWTAuth::parseToken()->toUser();
            $loggedInUser = User::with('roles', 'tournaments')->where('id', $authUser->id)->first();
            $resultAdministratorRoleId = Role::where('slug', 'Results.administrator')->first()->id;
            if($loggedInUser->hasRole('tournament.administrator')) {
                $data['userType'] = $resultAdministratorRoleId;
            }
        }

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
        'is_mobile_user' => $data['is_mobile_user'] ? 1 : 0,
        'is_desktop_user' => $data['is_desktop_user'] ? 1 : 0,
        'registered_from' => $data['registered_from'] ? 1 : 0,
        'user_image'=>(isset($data['user_image']) && $data['user_image']!='') ?  $data['user_image'] : '',
        'role' => (isset($data['role']) && $data['role']!='') ?  $data['role'] : '',
        'country_id' => (isset($data['country_id'])) ?  $data['country_id'] : null,
        'provider' => 'email',
        'provider_id' => null
        ];

        $deletedUser = User::onlyTrashed()->where('email', $data['email'])->first();
        try {
            if ($deletedUser) {
                $deletedUser->restore();
                $userData = User::find($deletedUser['id'])->update($userData);

                // $userData->roles()->detatch();
                $user = User::find($deletedUser['id']);
                $user->roles()->sync($data['userType']);
                return ['status' => 'updated', 'user' => $user];
            }else{
                    $user = User::create($userData);
                    $user->attachRole($data['userType']); 
                    return ['status'=>'created','user'=>$user];
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
        return User::where('id', $id)->update(['is_active' => $status]);
    }

    public function edit($userId)
    {
        $user = DB::table('users')
                ->join('people', 'users.person_id', '=', 'people.id')
                ->join('role_user', 'users.id', '=', 'role_user.user_id')
                ->join('roles', 'roles.id', '=', 'role_user.role_id')
                ->select("users.id as id", "users.email as emailAddress", DB::raw('IF(users.user_image is not null,CONCAT("' . $this->userImagePath . '", users.user_image),"" ) as image'), "users.organisation as organisation", "people.first_name as name", "people.last_name as surname", "role_user.role_id as userType", "users.role as role", "users.country_id as country_id", "users.country_id as country", "users.locale as locale",
                        'users.is_active', 'roles.slug as role_slug', 'people.address', 'people.address_2', 'people.city', 'people.job_title', 'people.zipcode', "users.provider as provider")
                ->where("users.id", "=", $userId)
                ->first();

        $defaultFavouriteTournament = DB::table('users_favourite')->where('user_id', $user->id)->where('is_default', 1)->first();

        $user->tournament_id = $defaultFavouriteTournament ? $defaultFavouriteTournament->tournament_id : null;

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
        $mobileUserRoleId = Role::where('slug', 'mobile.user')->first()->id;
        $key = $usersDetail['key'];
        $password = (isset($usersDetail['password']) && $usersDetail['password'] != '') ? $usersDetail['password'] : '';
        $usersPassword = User::where('token', $key)->first();
        $users = User::where("id", $usersPassword->id)->first();
        $users->is_verified = 1;
        $users->is_active = 1;
        $users->token = '';
        if ($password != '')
            $users->password = Hash::make($password);
        // $users->password = $password;
        $user = $users->save();
        return ($users->roles[0]->id == $mobileUserRoleId) ? 'Mobile' : 'Desktop';
    }

    public function createUserSettings($userData)
    {
        return Settings::create($userData);
    }

    public function getSetting($userData)
    {
        $userId = $userData['user_id'];
        return Settings::where('user_id', '=', $userId)->get();
        //return Settings::with(['user'])->where('user_id','=',$userId)->get();
    }

    public function postSetting($userData)
    {

        \Log::info($userData);
        $userId = $userData['userId'];
        $updatedValue = ['value' => json_encode($userData['userSettings'])];

        //$updatedValue = array('value'=>$userData['userSettings']);
        return Settings::where('user_id', $userId)->update($updatedValue);
    }

    public function setFCM($data)
    {
        $email = $data['email'];
        $fcmId = $data['fcm_id'];
        $updatedValue = ['fcm_id' => $fcmId];
        return User::where('email', $email)->update($updatedValue);
    }

    public function changeTournamentPermission($data)
    {
        $user = User::find($data['user']['id']);
        $user->tournaments()->sync([]);
        $user->tournaments()->attach($data['tournaments']);
        return true;
    }

    public function getUserWebsites($id)
    {
        $user = User::find($id);
        return $user->websites()->pluck('id');
    }

    public function getUserByEmail($email, $userId = "")
    {
        $user = User::where("email", $email);
        if (!empty($userId)) {
            $user->where('id', '<>', $userId);
        }
        return $user->get();
    }

    public function changePermissions($data) {
      $loggedInUser = $this->getCurrentLoggedInUserDetail();
      $user = User::find($data['user']['id']);

      $userTournamentsIds = $user->tournaments->pluck('id')->toArray();
      $userSelectedTournamentsIds = $data['tournaments'];
      $newlySelectedTournamentsIds = array_diff($userSelectedTournamentsIds, $userTournamentsIds);

      $user->tournaments()->sync([]);
      $user->tournaments()->attach($data['tournaments']);
      $user->websites()->sync([]);
      $user->websites()->attach($data['websites']);

      if($user->hasRole('Results.administrator') && $user->tournaments()->count() == 0) {
        $mobileUserRole = Role::where('slug', 'mobile.user')->first();
        $roleMobileUser = RoleUser::where('user_id', $data['user']['id'])->update(['role_id' => $mobileUserRole->id]);
      }
      if($loggedInUser->hasRole('tournament.administrator') && $user->hasRole('Results.administrator')) {
        $tournamentsArray = Tournament::whereIn('id', $newlySelectedTournamentsIds)->get()->toArray();
        foreach ($tournamentsArray as $key => $tournament) {
          $email_details['userName'] = $data['user']['first_name'];
          $email_details['tournamentId'] = $tournament['id'];
          $email_details['tournamentName'] = $tournament['name'];
          $userEmail = $data['user']['email'];
          $subject = 'Euro-Sportring Tournament Planner - New tournament access';
          $email_templates = 'emails.users.result_administrator_tournament_access';
          Mail::to($userEmail)->send(new SendMail($email_details, $subject, $email_templates, NULL, NULL, NULL));
        }      
      }

      return true;
    } 

    public function getUserTournaments($id) {
      $user = User::find($id);
      return $user->tournaments()->pluck('id');
    }

    /**
     * Update front customer
     * @param array $data
     * @param int $userId
     * @return array
     */
    public function updateUser($data, $userId)
    {
        $user = User::findOrFail($userId);
        $userEmailCount = $this->getUserByEmail($data['email'], $userId)->count();
        $personEmailCount = (new PeopleRepository())->getPersonByEmail($data['email'], $user->person_id)->count();
        if ($userEmailCount == 0 && $personEmailCount == 0) {
            $userData = [
                'username' => $data['email'],
                'name' => $data['first_name'] . " " . $data['last_name'],
                'email' => $data['email'],
                'organisation' => !empty($data['organisation']) ? $data['organisation'] : '',
                'is_active' => !empty($data['user_status']) ? $data['user_status'] : $user->is_active,
                'country_id' => $data['country'],
            ];
            $user->update($userData);

            $personData = [
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'display_name' => $data['first_name'] . " " . $data['last_name'],
                'primary_email' => $data['email'],
                'address' => $data['address'],
                'address_2' => $data['address_2'],
                'job_title' => $data['job_title'],
                'city' => $data['city'],
                'zipcode' => $data['zip'],
            ];
            $user->profile->update($personData);

            if (!empty($data['user_type'])) {
                $user->detachAllRoles();
                $user->attachRole($data['user_type']);
            }

            return true;
        } else {
            return false;
        }
    }

    public function getAllCountries()
    {
        return $contries = Country::orderBy('name')->get();
    }

    public function getAllLanguages()
    {
        return $languages = config('wot.languages');
    }

    public function updateAppDeviceVersion($data) {
        $usersAppDevice = User::where('id', $data['user_id'])
                                ->update(['device' => $data['device'], 'app_version' => $data['app_version'], 
                                  'os_version' => $data['os_version']]);

        return ['status_code' => 200, 'message' => 'User data has been updated.'];
    }

    public function validateUserEmail($data) {
      $user = User::where('email', $data['email']);
      if(isset($data['id'])) {
        $user->where('id', '!=', $data['id']);
      }
      if($user->first()) {
        return ['status_code' => 200, 'emailexists' => true];
      }
      return ['status_code' => 200, 'emailexists' => false];
    }

    public function verifyResultAdminUser($data)
    {
      $user = User::join('role_user', 'users.id', '=', 'role_user.user_id')
                ->leftjoin('roles', 'roles.id', '=', 'role_user.role_id')
                ->leftjoin('people', 'people.id', '=', 'users.person_id')
                ->leftjoin('countries', 'countries.id', '=', 'users.country_id')
                ->select('users.id as id', 'people.first_name as first_name', 'people.last_name as last_name', 'users.email as email', 'roles.id as role_id', 'roles.name as role_name', 'roles.slug as role_slug', 'users.is_verified as is_verified', 'users.is_mobile_user as is_mobile_user', 'users.is_desktop_user as is_desktop_user', 'users.organisation as organisation', 'users.locale as locale', 'users.role as role','countries.name as country', 'users.device as device', 'users.app_version as app_version', 'users.provider as provider')
                ->where('email', $data['email'])->first();
      $loggedInUser = $this->getCurrentLoggedInUserDetail();

      if($user) {
        $tournamentIds = $loggedInUser->tournaments->pluck('id')->toArray();

        $tournamentUserIds = TournamentUser::leftjoin('role_user', 'tournament_user.user_id', '=', 'role_user.user_id')
                              ->leftjoin('roles', 'roles.id', '=', 'role_user.role_id')
                              ->whereIn('tournament_id', $tournamentIds)
                              ->where('tournament_user.user_id', '!=', $loggedInUser->id)
                              ->where('slug', 'Results.administrator')
                              ->pluck('tournament_user.user_id')
                              ->toArray();

        if(in_array($user->id, $tournamentUserIds)) {
          return ['status_code'=> 200, 'isAlreadyAdded' => true];
        }

        if($user->roles()->first()->slug != 'Results.administrator') {
          return ['status_code'=> 200, 'emailExists' => true];
        } else {
          return ['status_code'=> 200, 'isResultAdmin' => true, 'user' => $user];
        }
      } else {
        return ['status_code'=> 200, 'emailExists' => false];
      }
    }
}
