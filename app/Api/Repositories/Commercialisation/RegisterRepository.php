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

    public function index()
    {
        $countries = $this->getAllCountries();
        return view('auth.register', ['countries' => $countries]);
    }

    public function register($data)
    {

        //Inserting Customer Data in People Table
        $newCustomer = [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'display_name' => $data['first_name'] . " " . $data['last_name'],
            'primary_email' => $data['email'],
            'address' => $data['address']
        ];
        $result = (new PeopleRepository())->create($newCustomer);
        unset($newCustomer);

        if (true == $result) {
            //Inserting Customer Data in User Table
            $newUser = [
                'person_id' => $result['id'],
                'username' => $data['email'],
                'name' => $data['first_name'] . " " . $data['last_name'],
                'email' => $data['email'],
                'organisation' => $data['organisation'],
                'password' => Hash::make($data['password']),
                'is_mobile_user' => 0,
                'is_desktop_user' => 1,
                'registered_from' => 1,
                'is_active' => 1,
                'is_verified' => 1,
            ];

            //return (new UserRepository())->create($newUser);
            $user = User::create($newUser);
            $user->roles()->attach(6); //Bind role with customer
            
            return [
                'data' => $user,
                'token' => $token = JWTAuth::attempt(['email' => $newUser['email'], 'password' => $data['password']])
            ];
        } else {
            return false;
        }
    }

    /* Check if a customer is already registered */

    public function isRegisteredCustomer($email)
    {
        $userCount = (new UserRepository())->getUserByEmail($email)->count();
        $personCount = (new PeopleRepository())->getPersonByEmail($email)->count();

        return ($userCount == 0 && $personCount == 0) ? FALSE : TRUE;
    }

    /* Get Countries List */

    public function getAllCountries()
    {
        return Country::all()->pluck('id', 'name');
    }
}
