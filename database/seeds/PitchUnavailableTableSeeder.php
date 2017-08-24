<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PitchUnavailableTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tournament = DB::table('tournaments')->take(3)->select('id')->get()->toArray();
        $pitch = DB::table('pitches')->take(3)->select('id')->get()->toArray();

        DB::table('pitch_unavailable')->insert([
    	['pitch_id' =>  $pitch[array_rand($pitch)]->id,
    	'tournament_id' =>  $tournament[array_rand($tournament)]->id,	
        'match_start_datetime' => Carbon::now()->format('Y-m-d H:i:s'), 
        'match_end_datetime' =>  Carbon::now()->format('Y-m-d H:i:s')],    	
    	
    	['pitch_id' =>  $pitch[array_rand($pitch)]->id,
    	'tournament_id' =>  $tournament[array_rand($tournament)]->id,	
        'match_start_datetime' => Carbon::now()->format('Y-m-d H:i:s'), 
        'match_end_datetime' =>  Carbon::now()->format('Y-m-d H:i:s')],    	
 
        ['pitch_id' =>  $pitch[array_rand($pitch)]->id,
        'tournament_id' =>  $tournament[array_rand($tournament)]->id,   
        'match_start_datetime' => Carbon::now()->format('Y-m-d H:i:s'), 
        'match_end_datetime' =>  Carbon::now()->format('Y-m-d H:i:s')],
        ]);
    }
}
