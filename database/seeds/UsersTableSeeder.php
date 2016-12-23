<?php

use Illuminate\Database\Seeder;
use Duro85\Roles\Models\Role;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superAdminUser = factory(App\Models\User::class)->create([
            'username' => 'superadmin',
            'name'=> 'SuperAdmin',
            'email' => 'superadmin@superadmin.com',
            'password' => bcrypt('superadmin'),
            'is_verified' => 1,
            'person_id' => 1,
            'token' => '1',

        ]);

        $superAdminUser->attachRole(Role::where('slug', 'superadmin')->get());

        $adminUser = factory(App\Models\User::class)->create([
            'username' => 'admin',
            'name'=> 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('admin'), 'person_id' => 2, 'token' => '2',
            'is_verified' => 1,
        ]);
        $adminUser->attachRole(Role::where('slug', 'admin')->get());

        $moderatorUser = factory(App\Models\User::class)->create([
            'username' => 'moderator',
            'name'=> 'User',
            'email' => 'moderator@moderator.com',
            'password' => bcrypt('moderator'), 'person_id' => 3, 'token' => '3',
            'is_verified' => 1,
        ]);
        $moderatorUser->attachRole(Role::where('slug', 'moderator')->get());

       /* $users = factory(App\Models\User::class, 10)
            ->create()
            ->each(function ($user) {
                $user->attachRole(Role::where('slug', 'user')->get());
            });
        */
    }
}
