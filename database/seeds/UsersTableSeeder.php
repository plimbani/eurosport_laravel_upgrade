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
            ['person_id' => '1', 'username' => 'superadmin', 'user_image' => '1491572922.png',
            'name' => 'SuperAdmin', 'email' => 'superadmin@superadmin.com', 'organisation' => 'Euro-Sportring',
            'password' => bcrypt('password'), 'token' => '1','timezone' => '','is_mobile_user' => '0','settings' => '', 'remember_token' => '', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            
            ['person_id' => '2', 'username' => 'superadmin1', 'user_image' => '1491572922.png',
            'name' => 'SuperAdmin', 'email' => 'admin@admin.com', 'organisation' => 'Euro-Sportring',
            'password' => bcrypt('password'), 'token' => '1', 'timezone' => '', 'is_mobile_user' => '0', 'settings' => '', 'remember_token' => '', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

            ['person_id' => '3','username' => 'superadmin2', 'user_image' => '1491572922.png',
            'name' => 'SuperAdmin', 'email' => 'moderator@moderator.com', 'organisation' => 'Euro-Sportring',
            'password' => bcrypt('password'), 'token' => '1','timezone' => '', 'is_mobile_user' => '0', 'settings' => '', 'remember_token' => '', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')]
        ]);
    }
}
