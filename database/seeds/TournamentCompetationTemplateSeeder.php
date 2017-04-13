<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TournamentCompetationTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        DB::table('tournament_competation_template')->truncate();
        $tournament = DB::table('tournaments')->take(3)->select('id')->get()->toArray();
        $tournaments = DB::table('tournament_template')->take(3)->select('id')->get()->toArray(); 

        DB::table('tournament_competation_template')->insert([
        	['tournament_id' => array_rand($tournament), 'group_name' => 'U1012345', 'tournament_template_id' => array_rand($tournaments), 'total_match' => '19',
        	'disp_format_name' => '7 TEAMS,RR1,RR2,F', 'total_time' => '711', 'game_duration_RR' => '20', 'game_duration_FM' => '30', 'halftime_break_RR' => '7',
        	'halftime_break_FM' => '5', 'match_interval_RR' => '10', 'match_interval_FM' => '10'],

        	['tournament_id' => array_rand($tournament), 'group_name' => 'U11', 'tournament_template_id' => array_rand($tournaments), 'total_match' => '18',
        	'disp_format_name' => '7 TEAMS,RR1,RR2,F', 'total_time' => '570', 'game_duration_RR' => '20', 'game_duration_FM' => '20', 'halftime_break_RR' => '5',
        	'halftime_break_FM' => '5', 'match_interval_RR' => '5', 'match_interval_FM' => '5'],

            ['tournament_id' => array_rand($tournament), 'group_name' => 'U11', 'tournament_template_id' => array_rand($tournaments), 'total_match' => '18',
            'disp_format_name' => '7 TEAMS,RR1,RR2,F', 'total_time' => '570', 'game_duration_RR' => '20', 'game_duration_FM' => '20', 'halftime_break_RR' => '5',
            'halftime_break_FM' => '5', 'match_interval_RR' => '5', 'match_interval_FM' => '5']
        ]);
    }
}
