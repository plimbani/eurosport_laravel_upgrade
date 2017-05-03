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
            ['person_id' => '3','username' => 'TournamentAdministrator', 
            'name' => 'TournamentAdministrator', 'email' => 'tadministrator@administrator.com', 'organisation' => 'Euro-Sportring',
            'password' => bcrypt('password'), 'token' => '1','timezone' => '', 'is_mobile_user' => '0', 'settings' => '', 'remember_token' => '', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            
            ['person_id' => '4','username' => 'moderator', 
            'name' => 'moderator', 'email' => 'moderator@moderator.com', 'organisation' => 'Euro-Sportring',
            'password' => bcrypt('password'), 'token' => '1','timezone' => '', 'is_mobile_user' => '0', 'settings' => '', 'remember_token' => '', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            
            ['person_id' => '2', 'username' => 'master', 
            'name' => 'master', 'email' => 'master@master.com', 'organisation' => 'Euro-Sportring',
            'password' => bcrypt('password'), 'token' => '1', 'timezone' => '', 'is_mobile_user' => '0', 'settings' => '', 'remember_token' => '', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],


            ['person_id' => '1', 'username' => 'superadmin', 
            'name' => 'SuperAdmin', 'email' => 'superadmin@superadmin.com', 'organisation' => 'Euro-Sportring',
            'password' => bcrypt('password'), 'token' => '1','timezone' => '','is_mobile_user' => '0','settings' => '', 'remember_token' => '', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')]
        ]);
    }
}
