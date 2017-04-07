<?php

use Illuminate\Database\Seeder;

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
        	[ 'name' => 'Kamal', 'website' => 'eurosport', 'facebook' =>'fb', 'twitter' => 'twitter',  'logo' => '1491370667.png', 'competition_type' => 'Group Games', 'status' => 'Published', 'user_id' => '1', 'start_date' => Carbon::now()->format('Y-m-d H:i:s'), 'end_date' => Carbon::now()->format('Y-m-d H:i:s'), 'no_of_pitches' => '1', 'no_of_match_per_day_pitch' => '2', 'points_per_match_win' => '1', 'points_per_match_tie' => '1', 'points_per_bye' => '2'],
        	[ 'name' => 'Krunal', 'website' => 'eurosport', 'facebook' =>'fb', 'twitter' => 'twitter', 'logo' => '1491370667.png', 'competition_type' => 'Single Elimination', 'status' => 'UnPublished', 'user_id' => '1', 'start_date' => Carbon::now()->format('Y-m-d H:i:s'), 'end_date' => Carbon::now()->format('Y-m-d H:i:s'), 'no_of_pitches' => '1', 'no_of_match_per_day_pitch' => '1', 'points_per_match_win' => '2', 'points_per_match_tie' => '1', 'points_per_bye' => '1'],
        	[ 'name' => 'Rishabh', 'website' => 'eurosport', 'facebook' =>'fb', 'twitter' => 'twitter',  'logo' => '1491370667.png', 'competition_type' => 'Double Elimination', 'status' => 'Published', 'user_id' => '1','start_date'  => Carbon::now()->format('Y-m-d H:i:s'), 'end_date' => Carbon::now()->format('Y-m-d H:i:s'), 'no_of_pitches' => '1', 'no_of_match_per_day_pitch' => '3', 'points_per_match_win' => '1', 'points_per_match_tie' => '2', 'points_per_bye' => '2'],       	
        ]);
    }
}
