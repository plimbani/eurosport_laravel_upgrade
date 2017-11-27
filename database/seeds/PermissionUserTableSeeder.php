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
        DB::table('permission_user')->delete();
        $permission = DB::table('permissions')->take(3)->get()->toArray();
        $users = DB::table('users')->take(1)->select('id')->get()->toArray();
        
        DB::table('permission_user')->insert([
        	[ 'permission_id' => $permission[array_rand($permission)]->id, 'user_id' => $users[array_rand($users)]->id, 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
        	[ 'permission_id' => $permission[array_rand($permission)]->id, 'user_id' => $users[array_rand($users)]->id, 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
        	[ 'permission_id' => $permission[array_rand($permission)]->id, 'user_id' => $users[array_rand($users)]->id, 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
        ]);
    }
}
