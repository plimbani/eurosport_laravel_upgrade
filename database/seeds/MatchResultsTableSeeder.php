<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class MatchResultsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {  
        $venues = DB::table('venues')->take(3)->select('id')->get()->toArray();
        $referee = DB::table('referee')->take(3)->select('id')->get()->toArray();
       
        DB::table('match_results')->insert([
        	['goal_score1' => '9', 'goal_score2' => '3', 'match_status' => 'penalties', 'winner' => 'Atest', 
        	'location_id' => $venues[array_rand($venues)]->id, 'referee_id' => $referee[array_rand($referee)]->id, 'notes' => 'euro'],

        	['goal_score1' => '8', 'goal_score2' => '2', 'match_status' => 'full-time', 'winner' => 'Atest', 
        	'location_id' => $venues[array_rand($venues)]->id, 'referee_id' => $referee[array_rand($referee)]->id, 'notes' => 'euro'],

        	['goal_score1' => '7', 'goal_score2' => '1', 'match_status' => 'full-time', 'winner' => 'Atest', 
        	'location_id' => $venues[array_rand($venues)]->id, 'referee_id' => $referee[array_rand($referee)]->id, 'notes' => 'euro']
        ]);
    }
}
