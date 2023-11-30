<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->truncate();
        $people = DB::table('people')->take(3)->select('id')->get()->toArray();

        DB::table('users')->insert([
            ['person_id' => '1', 'username' => 'rstenson@aecordigital.com',
                'name' => 'Super Admin', 'email' => 'rstenson@aecordigital.com', 'organisation' => 'Euro-Sportring',
                'is_active' => 1,
                'is_verified' => 1,
                'is_desktop_user' => 1,
                'is_mobile_user' => 1,
                'registered_from' => 1,
                'password' => bcrypt('password'), 'token' => '1', 'timezone' => '', 'settings' => '', 'remember_token' => '', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

            ['person_id' => '2', 'username' => 'a.mens@euro-sportring.org',
                'name' => 'Albert Mens', 'email' => 'a.mens@euro-sportring.org', 'organisation' => 'Euro-Sportring',
                'is_active' => 1,
                'is_verified' => 1,
                'is_desktop_user' => 1,
                'is_mobile_user' => 1,
                'registered_from' => 1,
                'password' => bcrypt('password'), 'token' => '1', 'timezone' => '', 'settings' => '', 'remember_token' => '', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

            ['person_id' => '3', 'username' => 'ndeopura@aecordigital.com',
                'name' => 'Nitin Deopura', 'email' => 'ndeopura@aecordigital.com', 'organisation' => 'Euro-Sportring',
                'is_active' => 1,
                'is_verified' => 1,
                'is_desktop_user' => 1,
                'is_mobile_user' => 1,
                'registered_from' => 1,
                'password' => bcrypt('password'), 'token' => '1', 'timezone' => '', 'settings' => '', 'remember_token' => '', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

            ['person_id' => '4', 'username' => 'richinternal@mailinator.com',
                'name' => 'Rich Internal', 'email' => 'richinternal@mailinator.com', 'organisation' => 'Aecor',
                'is_active' => 1,
                'is_verified' => 1,
                'is_desktop_user' => 1,
                'is_mobile_user' => 1,
                'registered_from' => 1,
                'password' => bcrypt('password'), 'token' => '1', 'timezone' => '', 'settings' => '', 'remember_token' => '', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

            ['person_id' => '5', 'username' => 'testerRS1@mailinator.com',
                'name' => 'Test Email', 'email' => 'testerRS1@mailinator.com', 'organisation' => 'aecor',
                'is_active' => 1,
                'is_verified' => 1,
                'is_desktop_user' => 1,
                'is_mobile_user' => 1,
                'registered_from' => 1,
                'password' => bcrypt('password'), 'token' => '1', 'timezone' => '', 'settings' => '', 'remember_token' => '', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

            ['person_id' => '6', 'username' => 'testadmin@mailinator.com',
                'name' => 'Test Admin', 'email' => 'testadmin@mailinator.com', 'organisation' => 'aecor',
                'is_active' => 1,
                'is_verified' => 1,
                'is_desktop_user' => 1,
                'is_mobile_user' => 1,
                'registered_from' => 1,
                'password' => bcrypt('password'), 'token' => '1', 'timezone' => '', 'settings' => '', 'remember_token' => '', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

            ['person_id' => '7', 'username' => 'richresults@mailinator.com',
                'name' => 'Rich Results', 'email' => 'richresults@mailinator.com', 'organisation' => 'aecor',
                'is_active' => 1,
                'is_verified' => 1,
                'is_desktop_user' => 1,
                'is_mobile_user' => 1,
                'registered_from' => 1,
                'password' => bcrypt('password'), 'token' => '1', 'timezone' => '', 'settings' => '', 'remember_token' => '', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

        ]);
    }
}
