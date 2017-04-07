<?php

use Illuminate\Database\Seeder;

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
        DB::table('role_user')->insert([
        	[ 'roel_id' => '1', 'user_id' => '1'],
        	[ 'roel_id' => '2', 'user_id' => '2'],
        	[ 'roel_id' => '3', 'user_id' => '3'],
        ]);
    }
}
