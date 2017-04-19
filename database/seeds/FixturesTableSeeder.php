<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class FixturesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('fixtures')->truncate();
        $tournament = DB::table('tournaments')->take(3)->select('id')->get()->toArray();
        $competition = DB::table('competitions')->take(3)->select('id')->get()->toArray();
        $venue = DB::table('venues')->take(3)->select('id')->get()->toArray();
        $pitch = DB::table('pitches')->take(3)->select('id')->get()->toArray();
       
        DB::table('fixtures')->insert([
        	['tournament_id' => $tournament[array_rand($tournament)]->id, 'competition_id' => $competition[array_rand($competition)]->id, 'venue_id' => $venue[array_rand($venue)]->id, 'pitch_id' => $pitch[array_rand($pitch)]->id, 
        	'match_datetime' => Carbon::now()->format('Y-m-d H:i:s'), 'match_number' => '1', 'round' => 'Round robin', 
        	'home_team' =>'5', 'away_team' => '6', 'hometeam_score' => '1', 'awayteam_score' => '4',
        	'hometeam_point' => '4.00', 'awayteam_point' => '4.00', 'match_result_id' => '1', 'bracket_json' => '', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            
        	['tournament_id' => $tournament[array_rand($tournament)]->id, 'competition_id' => $competition[array_rand($competition)]->id, 'venue_id' => $venue[array_rand($venue)]->id, 'pitch_id' => $pitch[array_rand($pitch)]->id,
        	'match_datetime' => Carbon::now()->format('Y-m-d H:i:s'), 'match_number' => '8', 'round' => 'Round robin', 
        	'home_team' =>'8', 'away_team' => '7', 'hometeam_score' => '3', 'awayteam_score' => '2',
        	'hometeam_point' => '5.00', 'awayteam_point' => '3.00', 'match_result_id' => '1', 'bracket_json' => '', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
        	
        	['tournament_id' => $tournament[array_rand($tournament)]->id, 'competition_id' => $competition[array_rand($competition)]->id, 'venue_id' => $venue[array_rand($venue)]->id, 'pitch_id' => $pitch[array_rand($pitch)]->id, 
        	'match_datetime' => Carbon::now()->format('Y-m-d H:i:s'), 'match_number' => '9', 'round' => 'Round robin', 
        	'home_team' =>'7', 'away_team' => '5', 'hometeam_score' => '1', 'awayteam_score' => '1', 'hometeam_point' => '4.00', 
        	'awayteam_point' => '3.00', 'match_result_id' => '1', 'bracket_json' => '', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')]
        ]);
    }
}
