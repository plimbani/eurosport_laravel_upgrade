<?php

use Illuminate\Database\Seeder;

class ClubsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    { 
        DB::table('permission_role')->truncate();
        DB::table('permission_role')->insert([
        	[ 'user_id' => '1', 'name' => 'Kamal'],
        	[ 'user_id' => '2', 'name' => 'Krunal'],
        	[ 'user_id' => '3', 'name' => 'Rishabh'],
        ]);
    }
}
