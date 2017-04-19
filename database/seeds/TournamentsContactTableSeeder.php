<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

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
        $tournament = DB::table('tournaments')->take(3)->select('id')->get()->toArray();
       
        DB::table('tournament_contact')->insert([
        	['tournament_id' => $tournament[array_rand($tournament)]->id, 'first_name' => 'Selmir', 'last_name' => 'Sabic', 
            'telephone' => '7418529630', 'email' => 'test@aecordigital.com', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

        	['tournament_id' => $tournament[array_rand($tournament)]->id, 'first_name' => 'Ivan', 'last_name' => 'Vanrykel', 
            'telephone' => '7418529630', 'email' => 'test1@aecordigital.com', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

        	['tournament_id' => $tournament[array_rand($tournament)]->id, 'first_name' => 'Smith', 'last_name' => 'Venture', 
            'telephone' => '7418529630', 'email' => 'test2@aecordigital.com', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')]
        ]);
    }
}
