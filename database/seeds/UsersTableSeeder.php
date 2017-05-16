<?php

use Illuminate\Database\Seeder;
use Laraspace\User;
use Carbon\Carbon;

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
            ['person_id' => '3','username' => 'tadministrator@administrator.com',
            'name' => 'TournamentAdministrator', 'email' => 'tadministrator@administrator.com', 'organisation' => 'Euro-Sportring',
            'password' => bcrypt('password'), 'token' => '1','timezone' => '', 'is_mobile_user' => '0', 'settings' => '',
            'is_active'=>1,
            'is_verified'=>1,
            'remember_token' => '', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

            ['person_id' => '4','username' => 'moderator@moderator.com',
            'name' => 'moderator', 'email' => 'moderator@moderator.com', 'organisation' => 'Euro-Sportring',
            'is_active'=>0,
            'is_verified'=>1,
            'password' => bcrypt('password'), 'token' => '1','timezone' => '', 'is_mobile_user' => '0', 'settings' => '', 'remember_token' => '', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

            ['person_id' => '2', 'username' => 'master@master.com',
            'name' => 'master', 'email' => 'master@master.com', 'organisation' => 'Euro-Sportring',
            'is_active'=>0,
            'is_verified'=>1,
            'password' => bcrypt('password'), 'token' => '1', 'timezone' => '', 'is_mobile_user' => '0', 'settings' => '', 'remember_token' => '', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

            ['person_id' => '1', 'username' => 'superadmin@superadmin.com',
            'name' => 'SuperAdmin', 'email' => 'superadmin@superadmin.com', 'organisation' => 'Euro-Sportring',
            'is_active'=>1,
            'is_verified'=>1,
            'password' => bcrypt('password'), 'token' => '1','timezone' => '','is_mobile_user' => '0','settings' => '', 'remember_token' => '', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')]
        ]);
    }
}
