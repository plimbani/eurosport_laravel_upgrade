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
        	['role_id' => 1, 'user_id' => 1, 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
        	['role_id' => 2, 'user_id' => 2, 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
        	['role_id' => 3, 'user_id' => 3, 'created_at' => Carbon::now()->format('Y-m-d H:i:s')]
        ]);
    }
}
