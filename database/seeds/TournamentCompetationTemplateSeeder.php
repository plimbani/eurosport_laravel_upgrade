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
        	['tournament_id' => $tournament[array_rand($tournament)]->id,
          'total_teams' => '6',  'group_name' => 'U1012345', 'min_matches' => '5', 'tournament_template_id' => 1, 'total_match' => '9',
        	'category_age' => 'U11/7', 'category_age_color' => '#333333', 'disp_format_name' => '6 teams: 2x3 - PM 1-2/3-4/5-6', 'total_time' => '711', 'game_duration_RR' => '20', 'game_duration_FM' => '30', 'halftime_break_RR' => '7',
        	'halftime_break_FM' => '5', 'match_interval_RR' => '10', 'match_interval_FM' => '10'],

        	['tournament_id' => $tournament[array_rand($tournament)]->id, 'total_teams' => '7', 'group_name' => 'U11', 'min_matches' => '5', 'tournament_template_id' => 1, 'total_match' => '18',
        	'category_age' => 'U13/8', 'category_age_color' => '#fff68f', 'disp_format_name' => '6 teams: 2x3 - PM 1-2/3-4/5-6', 'total_time' => '570', 'game_duration_RR' => '20', 'game_duration_FM' => '20', 'halftime_break_RR' => '5',
        	'halftime_break_FM' => '5', 'match_interval_RR' => '5', 'match_interval_FM' => '5'],

            ['tournament_id' => $tournament[array_rand($tournament)]->id, 'total_teams' => '9', 'group_name' => 'U11', 'category_age_color' => '#b0e0e6', 'min_matches' => '5', 'tournament_template_id' => 1, 'total_match' => '18',
            'category_age' => 'U13/8', 'disp_format_name' => '6 teams: 2x3 - PM 1-2/3-4/5-6', 'total_time' => '570', 'game_duration_RR' => '20', 'game_duration_FM' => '20', 'halftime_break_RR' => '5',
            'halftime_break_FM' => '5', 'match_interval_RR' => '5', 'match_interval_FM' => '5'],
           [

           'tournament_id' => 5,
           'total_teams' => '6',
           'group_name' => 'U19',
           'min_matches' => '3',
           'tournament_template_id' => 1, 'total_match' => '9',
            'category_age' => 'U17', 'category_age_color' => '#f5f5dc',
             'disp_format_name' => '6 teams: 2x3 - PM 1-2/3-4/5-6', 'total_time' => '270',
             'game_duration_RR' => '20',
             'game_duration_FM' => '20', 'halftime_break_RR' => '5',
            'halftime_break_FM' => '5', 'match_interval_RR' => '5', 'match_interval_FM' => '5'],
             [

            'tournament_id' => 4,
           'total_teams' => '6',
           'group_name' => 'U15',
           'min_matches' => '3',
           'tournament_template_id' => 1, 'total_match' => '9',
            'category_age' => 'U17', 'category_age_color' => '#f5f5dc',
             'disp_format_name' => '6 teams: 2x3 - PM 1-2/3-4/5-6', 'total_time' => '270',
             'game_duration_RR' => '20',
             'game_duration_FM' => '20', 'halftime_break_RR' => '5',
            'halftime_break_FM' => '5', 'match_interval_RR' => '5', 'match_interval_FM' => '5']

        ]);
    }
}
