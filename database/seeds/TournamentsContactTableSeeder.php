<?php

use Illuminate\Database\Seeder;

class TournamentsContactTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tournament_contact')->truncate();
        DB::table('tournament_contact')->insert([
        	[ 'tournament_id' => '', 'first_name' => 'Kamal', 'last_name' => 'Nayak', 'telephone' => '1748529632', 'email' => 'kamal@aecordigital.com'],
        	[ 'tournament_id' => '', 'first_name' => 'Krunal', 'last_name' => 'Parikh', 'telephone' => '2515484668', 'email' => 'krunal@aecordigital.com'],
        	[ 'tournament_id' => '', 'first_name' => 'Rishabh', 'last_name' => 'Shah', 'telephone' => '741852963', 'email' => 'rshah@aecordigital.com'],
        ]);
    }
}
