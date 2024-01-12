<?php

use Illuminate\Database\Seeder;

class PermissionRoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('role_has_permissions')->truncate();
        $permission = DB::table('permissions')->take(3)->get()->toArray();
        $role = DB::table('roles')->take(3)->get()->toArray();

        DB::table('role_has_permissions')->insert([
            ['permission_id' => $permission[array_rand($permission)]->id, 'role_id' => $role[array_rand($role)]->id],

            ['permission_id' => $permission[array_rand($permission)]->id, 'role_id' => $role[array_rand($role)]->id],

            ['permission_id' => $permission[array_rand($permission)]->id, 'role_id' => $role[array_rand($role)]->id],
        ]);

    }
}
