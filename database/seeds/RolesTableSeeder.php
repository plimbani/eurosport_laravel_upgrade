<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('roles')->truncate();
        DB::table('roles')->insert([
            ['name' => 'Super administrator', 'guard_name' => 'web', 'slug' => 'Super.administrator', 'description' => 'The magician', 'level' => '1'],
            ['name' => 'Tournament administrator', 'guard_name' => 'web', 'slug' => 'tournament.administrator', 'description' => 'The GOD', 'level' => '1'],
            ['name' => 'Internal administrator', 'guard_name' => 'web', 'slug' => 'Internal.administrator', 'description' => 'The Demigod', 'level' => '1'],
            ['name' => 'Master administrator', 'guard_name' => 'web', 'slug' => 'Master.administrator', 'description' => 'The Man', 'level' => '1'],
            ['name' => 'Mobile user', 'guard_name' => 'web', 'slug' => 'mobile.user', 'description' => 'Mobile User', 'level' => '1'],
            ['name' => 'Results administrator', 'guard_name' => 'web', 'slug' => 'Results.administrator', 'description' => 'Results administrator', 'level' => '1'],
        ]);
        Schema::enableForeignKeyConstraints();
    }
}
