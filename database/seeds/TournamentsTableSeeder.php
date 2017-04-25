<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TournamentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tournaments')->truncate();
        DB::table('tournaments')->insert([
        	['id'=>'1','name' => 'BaYern Trophy 2016', 'website' => 'eurosport', 'facebook' =>'fb', 
            'twitter' => 'twitter',  'logo' => '1491370667.png', 
            'competition_type' => 'Group Games', 'status' => 'Published', 'user_id' => '1', 
             'start_date' => '2016-03-26 06:21:29', 
            'end_date' => '2016-03-27 06:21:29', 'no_of_pitches' => '1', 
            'no_of_match_per_day_pitch' => '2', 
            'points_per_match_win' => '1', 'points_per_match_tie' => '1', 'points_per_bye' => '2',
             'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
        	
            ['id'=>'2','name' => 'Hasselt Cup 2016', 'website' => '   www.hasseltcup.be', 'facebook' =>'fb', 
            'twitter' => 'twitter', 'logo' => '1491370667.png',
            'competition_type' => 'Single Elimination',
             'status' => 'Published', 'user_id' => '1',
            'start_date' => '2016-03-26 06:21:29', 
            'end_date' => '2016-03-27 06:21:29', 
            'no_of_pitches' => '1', 
            'no_of_match_per_day_pitch' => '1', 
            'points_per_match_win' => '2', 'points_per_match_tie' => '1', 
            'points_per_bye' => '1',
             'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
        
        	['id'=>'3','name' => 'Sirene Cup', 'website' => 'eurosport', 
            'facebook' =>'fb',
            'twitter' => 'twitter',  'logo' => '1491370667.png',
            'competition_type' => 'Double Elimination',
            'status' => 'Published', 'user_id' => '1',
            'start_date' => '2016-03-26 06:21:29', 
            'end_date' => '2016-03-27 06:21:29',
            'no_of_pitches' => '1', 
            'no_of_match_per_day_pitch' => '3', 
            'points_per_match_win' => '1', 'points_per_match_tie' => '2', 'points_per_bye' => '2',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')]       	
        ]);
    }
}
