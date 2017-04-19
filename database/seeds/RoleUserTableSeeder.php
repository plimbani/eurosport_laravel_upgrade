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
        	['role_id' => $role[array_rand($role)]->id, 'user_id' => $users[array_rand($users)]->id, 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
        	['role_id' => $role[array_rand($role)]->id, 'user_id' => $users[array_rand($users)]->id, 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
        	['role_id' => $role[array_rand($role)]->id, 'user_id' => $users[array_rand($users)]->id, 'created_at' => Carbon::now()->format('Y-m-d H:i:s')]
        ]);
    }
}
