<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PermissionUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        DB::table('permission_user')->truncate();
        $permission = DB::table('permissions')->take(3)->get()->toArray();
        $users = DB::table('users')->take(3)->select('id')->get()->toArray();
        
        DB::table('permission_user')->insert([
        	[ 'permission_id' => array_rand($permission) , 'user_id' => array_rand($users), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
        	[ 'permission_id' => array_rand($permission) , 'user_id' => array_rand($users), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
        	[ 'permission_id' => array_rand($permission) , 'user_id' => array_rand($users), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
        ]);
    }
}
