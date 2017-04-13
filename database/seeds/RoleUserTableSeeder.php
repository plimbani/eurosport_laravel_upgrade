<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class RoleUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        DB::table('role_user')->truncate();
        $role = DB::table('roles')->take(3)->get()->toArray();
        $users = DB::table('users')->take(3)->select('id')->get()->toArray();
        
        DB::table('role_user')->insert([
        	['role_id' => array_rand($role), 'user_id' => array_rand($users), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
        	['role_id' => array_rand($role), 'user_id' => array_rand($users), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
        	['role_id' => array_rand($role), 'user_id' => array_rand($users), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')]
        ]);
    }
}
