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
    //#4-http://eurosportring.tournamentsoftware.com/sport/matches.aspx?id=A2445DE2-AA01-4557-8FC7-F1980AD2ADC6&d=20170418
    public function run()
    {
        DB::table('tournaments')->truncate();
        DB::table('tournaments')->insert([
        	['id'=>'1','name' => 'BaYern Trophy 2016', 'website' => 'eurosport', 'facebook' =>'fb',
            'twitter' => 'twitter',
            'logo'=>'1.png',
            'competition_type' => 'Group Games', 'status' => 'UnPublished', 'user_id' => '1',
             'start_date' => '2016-03-26 06:21:29',
            'end_date' => '2016-03-27 06:21:29', 'no_of_pitches' => '1',
            'no_of_match_per_day_pitch' => '2',
            'points_per_match_win' => '1', 'points_per_match_tie' => '1', 'points_per_bye' => '2',
             'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

            ['id'=>'2','name' => 'Hasselt Cup 2016', 'website' => '   www.hasseltcup.be', 'facebook' =>'fb',
            'twitter' => 'twitter','logo'=>'2.png',
            'competition_type' => 'Single Elimination',
             'status' => 'UnPublished', 'user_id' => '1',
            'start_date' => '2016-03-26 06:21:29',
            'end_date' => '2016-03-27 06:21:29',
            'no_of_pitches' => '1',
            'no_of_match_per_day_pitch' => '1',
            'points_per_match_win' => '2', 'points_per_match_tie' => '1',
            'points_per_bye' => '1',
             'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

        	['id'=>'3','name' => 'Sirene Cup', 'website' => 'eurosport',
            'facebook' =>'fb',
            'twitter' => 'twitter','logo'=>'3.png',
            'competition_type' => 'Double Elimination',
            'status' => 'UnPublished', 'user_id' => '1',
            'start_date' => '2016-03-26 06:21:29',
            'end_date' => '2016-03-27 06:21:29',
            'no_of_pitches' => '1',
            'no_of_match_per_day_pitch' => '3',
            'points_per_match_win' => '1', 'points_per_match_tie' => '2', 'points_per_bye' => '2',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

          ['id'=>'4','name' => 'Copa Castell',
            'website' => 'Copa Castell',
            'facebook' =>'fb',
            'twitter' => 'twitter','logo'=>'4.png',
            'competition_type' => 'Single Elimination',
            'status' => 'Published', 'user_id' => '1',
            'start_date' => '2017-04-18 10:00:00',
            'end_date' => '2017-04-20 17:30:00',
            'no_of_pitches' => '1',
            'no_of_match_per_day_pitch' => '3',
            'points_per_match_win' => '1', 'points_per_match_tie' => '2', 'points_per_bye' => '2',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          ['id'=>'5','name' => 'Holland Cup',
            'website' => 'www.holland-cup.nl',
            'facebook' =>'fb',
            'twitter' => 'twitter','logo'=>'5.png',
            'competition_type' => 'Single Elimination',
            'status' => 'Published',
            'user_id' => '1',
            'start_date' => '2017-04-15 09:00:00',
            'end_date' => '2017-04-16 18:20:00',
            'no_of_pitches' => '1',
            'no_of_match_per_day_pitch' => '3',
            'points_per_match_win' => '1',
            'points_per_match_tie' => '2', 'points_per_bye' => '2',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
          ],
          ]
        );
    }
}
