<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PermissionRoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permission_role')->truncate();
        $permission = DB::table('permissions')->take(3)->get()->toArray();
        $role = DB::table('roles')->take(3)->get()->toArray();        

        DB::table('permission_role')->insert([
        	[ 'permission_id' => $permission[array_rand($permission)]->id, 'role_id' => $role[array_rand($role)]->id, 
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

        	[ 'permission_id' => $permission[array_rand($permission)]->id, 'role_id' => $role[array_rand($role)]->id, 
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

        	[ 'permission_id' => $permission[array_rand($permission)]->id, 'role_id' => $role[array_rand($role)]->id, 
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')]
        ]);
    }
}
