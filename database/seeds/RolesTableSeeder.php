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
        DB::table('roles')->truncate();
        DB::table('roles')->insert([
        	[ 'name' => 'Kamal', 'slug' => 'kamal', 'description' => 'hi', 'level' => '1'],
        	[ 'name' => 'Krunal', 'slug' => 'krunal', 'description' => 'hi', 'level' => '1'],
        	[ 'name' => 'Rishabh', 'slug' => 'rishabh', 'description' => 'hi', 'level' => '1'],
        ]);
    }
}
