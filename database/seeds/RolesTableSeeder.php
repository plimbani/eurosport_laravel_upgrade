<?php

use Illuminate\Database\Seeder;
use Duro85\Roles\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superAdminRole = Role::create([
            'name' => 'Superadmin',
            'slug' => 'superadmin',
            'description' => 'The GOD', // optional
            'level' => 9999, // optional, set to 1 by default
        ]);
        $adminRole = Role::create([
            'name' => 'Admin',
            'slug' => 'admin',
            'description' => 'The Demigod', // optional
            'level' => 9989, // optional, set to 1 by default
        ]);
        $moderatorRole = Role::create([
            'name' => 'Moderator',
            'slug' => 'moderator',
            'description' => 'The magician', // optional
            'level' => 9979, // optional, set to 1 by default
        ]);
        $userRole = Role::create([
            'name' => 'User',
            'slug' => 'user',
            'description' => 'The Man', // optional
            'level' => 1, // optional, set to 1 by default
        ]);
    }
}
