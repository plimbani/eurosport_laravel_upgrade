<?php
namespace Laraspace\Api\Repositories\Commercialisation;

use Carbon\Carbon;
use Illuminate\Support\Str;
use JWTAuth;
use Hash;
use Laraspace\Models\Country;
use Laraspace\Models\User;
use Laraspace\Api\Repositories\PeopleRepository;
use Laraspace\Api\Repositories\UserRepository;
use Laraspace\Api\Repositories\CountryRepository;

class RegisterRepository
{   
    public function index() {
        $countries = $this->getAllCountries();
        return view('auth.register', ['countries'=>$countries]);
    }
    
    public function register( $data ) {
        
        //Inserting Customer Data in People Table
        $newCustomer                    = [];
        $newCustomer['first_name']      = $data['first_name'];
        $newCustomer['last_name']       = $data['last_name'];
        $newCustomer['display_name']    = $data['first_name'] . " " . $data['last_name'];
        $newCustomer['primary_email']   = $data['email'];
        $newCustomer['address']        = $data['email'];
        
        $peopleRepository = new PeopleRepository();
        $result = $peopleRepository->create($newCustomer);
        
        unset($newCustomer);
        unset($peopleRepository);
        
        if(true == $result) {
             //Inserting Customer Data in User Table with person_id
            $newUser                    = [];
            $newUser['person_id']       = $result['id'];
            $newUser['username']        = $data['email'];
            $newUser['name']            = $data['first_name'] . " " . $data['last_name'];
            $newUser['email']           = $data['email'];
            $newUser['organisation']    = $data['organisation'];
            $newUser['password']        = Hash::make($data['password']);
            $newUser['is_mobile_user']  = 0;
            $newUser['is_desktop_user'] = 1;
            $newUser['registered_from'] = 1;
            $newUser['is_active']       = 1;
            $newUser['is_verified']     = 1;
            
            //return (new UserRepository())->create($newUser);
            return User::create($newUser);
        }
        else {
            return false;
        }
    }
    
    /* Check if a customer is already registered */
    public function isRegisteredCustomer( $email ){
      
      $userRepository   = new UserRepository();
      $peopleRepository = new PeopleRepository();
      $userCount        = $userRepository->getUserByEmail( $email )->count();
      $personCount      = $peopleRepository->getPersonByEmail( $email )->count();
      unset($userRepository);
      unset($peopleRepository);
      
      return ($userCount == 0 && $personCount == 0) ? FALSE : TRUE;
    }
    
    /* Get Countries List */
    public function getAllCountries() {
        return Country::all()->pluck('id','name');
    }
}
