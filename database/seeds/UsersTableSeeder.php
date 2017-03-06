<?php

use Illuminate\Database\Seeder;
use Laraspace\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'email' => 'admin@laraspace.in',
            'name' => 'Jane Doe',
            'role' => 'admin',
            'password' => bcrypt('admin@123')
        ]);

        User::create([
            'email' => 'knayak@aecordigital.com',
            'name' => 'Kamal Nayak',
            'role' => 'user',
            'password' => bcrypt('admin@123')
        ]);

        User::create([
            'email' => 'kparikh@aecordigital.com',
            'name' => 'Krunal Parikh',
            'role' => 'user',
            'password' => bcrypt('admin@123')
        ]);
    }
}
