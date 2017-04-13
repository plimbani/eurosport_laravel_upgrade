<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class MatchStandingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        $tournament = DB::table('tournaments')->take(3)->select('id')->get()->toArray();
        $competition = DB::table('competitions')->take(3)->select('id')->get()->toArray();
        $teams = DB::table('teams')->take(3)->select('id')->get()->toArray();
       
        DB::table('match_standing')->insert([
        	['tournament_id' => array_rand($tournament), 'competition_id' => array_rand($competition), 'team_id' =>  array_rand($teams), 'points' => '4', 
        	'played' => '3', 'won' => '1', 'draws' => '1', 'lost' => '1', 'goal_for' => '5',
        	'goal_against' => '7', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

        	['tournament_id' => array_rand($tournament), 'competition_id' => array_rand($competition), 'team_id' =>  array_rand($teams), 'points' => '3', 
        	'played' => '3', 'won' => '1', 'draws' => '0', 'lost' => '2', 'goal_for' => '7',
        	'goal_against' => '5', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

        	['tournament_id' => array_rand($tournament), 'competition_id' => array_rand($competition), 'team_id' =>  array_rand($teams), 'points' => '5', 
        	'played' => '3', 'won' => '1', 'draws' => '1', 'lost' => '1','goal_for' => '4', 
        	'goal_against' => '7', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')]
        ]);
    }
}
