<?php

namespace Laraspace\Api\Repositories\Commercialisation;

use Carbon\Carbon;
use Illuminate\Support\Str;
use JWTAuth;
use Hash;
use Laraspace\Models\Country;
use Laraspace\Models\User;
use Laraspace\Models\Role;
use Laraspace\Models\Settings;
use Laraspace\Api\Repositories\PeopleRepository;
use Laraspace\Api\Repositories\UserRepository;
use Laraspace\Api\Repositories\CountryRepository;
use Illuminate\Support\Facades\Mail;
use Laraspace\Mail\SendMail;

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
            'address' => $data['address'],
            'address_2' => $data['address_2'],
            'city' => $data['city'],
            'zipcode' => $data['zip'],
        ];        
        $result = (new PeopleRepository())->create($newCustomer);

        unset($newCustomer);

        if (true == $result) {
            //Inserting Customer Data in User Table

            $token = str_random(30);

            $newUser = [
                'person_id' => $result['id'],
                'username' => $data['email'],
                'name' => $data['first_name'] . " " . $data['last_name'],
                'email' => $data['email'],
                'is_mobile_user' => 1,
                'is_desktop_user' => 1,
                'registered_from' => 1,
                'is_active' => 0,
                'is_verified' => 0,
                'country_id' => $data['country'],
                'token' => $token,
            ];

            $user = User::create($newUser);

            $userSettings['user_id'] = $user->id;
            $userSettings['value'] = '{"is_sound":"true","is_vibration":"true","is_notification":"true"}';
            Settings::create($userSettings);

            $subject = 'Easy Match Manager - Set password';
            $email_templates = 'emails.users.registration';
            $emailData = ['name' => $data['first_name'],'token'=> $token,'currentLayout' => config('config-variables.current_layout')];
            Mail::to($data['email'])
                    ->send(new SendMail($emailData, $subject, $email_templates, NULL, NULL, NULL));
            
            //Find role and attach with user
            $role = Role::where('slug', '=', 'customer')->first();
            $user->roles()->attach($role->id); //Bind role with customer
            
            return [
                'user' => $user,
                'role' => [$role]
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
