<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TempFixturesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('temp_fixtures')->truncate();
        $tournament = DB::table('tournaments')->take(3)->select('id')->get()->toArray();
        $competition = DB::table('competitions')->take(3)->select('id')->get()->toArray();
        $venue = DB::table('venues')->take(3)->select('id')->get()->toArray();
        $pitch = DB::table('pitches')->take(3)->select('id')->get()->toArray();
        $referee = DB::table('referee')->take(3)->select('id')->get()->toArray();
        $teams = DB::table('teams')->take(3)->select('id')->get()->toArray();

        DB::table('temp_fixtures')->insert([
        	['tournament_id' =>  $tournament[array_rand($tournament)]->id, 'competition_id' => $competition[array_rand($competition)]->id,
            'venue_id' => $venue[array_rand($venue)]->id, 'referee_id' =>  $referee[array_rand($referee)]->id , 'pitch_id' => $pitch[array_rand($pitch)]->id, 'is_scheduled' => '0',
            'match_datetime' => Carbon::now()->format('Y-m-d H:i:s'), 'match_endtime' =>  Carbon::now()->format('Y-m-d H:i:s'), 'match_number' => '1', 'round' => 'Round robin',
        	'home_team' =>$teams[array_rand($teams)]->id, 'home_team_id' => '1', 'away_team' => $teams[array_rand($teams)]->id,
            'comments' => '', 'match_winner' => '', 'match_status' => 'Full-time', 'away_team_id' => '3',   'hometeam_score' => '1', 'awayteam_score' => '4', 'hometeam_point' => '4.00', 'awayteam_point' => '4.00', 'match_result_id' => '1', 'bracket_json' => '', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

        	['tournament_id' =>  $tournament[array_rand($tournament)]->id, 'competition_id' => $competition[array_rand($competition)]->id,
            'venue_id' => $venue[array_rand($venue)]->id, 'referee_id' => $referee[array_rand($referee)]->id, 'pitch_id' => $pitch[array_rand($pitch)]->id, 'is_scheduled' => '0',
            'match_datetime' => Carbon::now()->format('Y-m-d H:i:s'), 'match_endtime' => Carbon::now()->format('Y-m-d H:i:s'), 'match_number' => '8', 'round' => 'Round robin',
        	'home_team' =>$teams[array_rand($teams)]->id, 'home_team_id' => '3', 'away_team' => $teams[array_rand($teams)]->id,
            'comments' => '', 'match_winner' => '', 'match_status' => 'Full-time', 'away_team_id' => '2',   'hometeam_score' => '3', 'awayteam_score' => '2', 'hometeam_point' => '5.00', 'awayteam_point' => '3.00', 'match_result_id' => '1', 'bracket_json' => '', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

        	['tournament_id' =>  $tournament[array_rand($tournament)]->id, 'competition_id' => $competition[array_rand($competition)]->id,
            'venue_id' => $venue[array_rand($venue)]->id, 'referee_id' => $referee[array_rand($referee)]->id, 'pitch_id' => $pitch[array_rand($pitch)]->id, 'is_scheduled' => '0',
            'match_datetime' => Carbon::now()->format('Y-m-d H:i:s'), 'match_endtime' => Carbon::now()->format('Y-m-d H:i:s'), 'match_number' => '9', 'round' => 'Round robin',
        	'home_team' =>$teams[array_rand($teams)]->id, 'home_team_id' => '2', 'away_team' => $teams[array_rand($teams)]->id,
            'comments' => '', 'match_winner' => '', 'match_status' => 'Full-time', 'away_team_id' => '1',   'hometeam_score' => '1', 'awayteam_score' => '1', 'hometeam_point' => '4.00', 'awayteam_point' => '3.00', 'match_result_id' => '1', 'bracket_json' => '', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')
          ],

          ['tournament_id' =>  4,
          'competition_id' => 5,
            'venue_id' => 4,
            'referee_id' => $referee[array_rand($referee)]->id,
            'pitch_id' => 1,
            'is_scheduled' => '0',
            'match_datetime' => '2017-04-18 10:50:00',
            'match_endtime' => '2017-04-18 11:20:00',
            'match_number' => '9',
            'round' => 'Round robin',
          'home_team' =>4,
          'home_team_id' => '4',
          'away_team' => 5,
            'comments' => '',
            'match_winner' => '', 'match_status' => 'Full-time',
            'away_team_id' => '5',
             'hometeam_score' => '12',
             'awayteam_score' => '0',
             'hometeam_point' => '4.00', 'awayteam_point' => '3.00', 'match_result_id' => '1', 'bracket_json' => '', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')
          ],
          ['tournament_id' =>  4,
          'competition_id' => 5,
            'venue_id' => 4,
            'referee_id' => $referee[array_rand($referee)]->id,
            'pitch_id' => 2,
            'is_scheduled' => '0',
            'match_datetime' => '2017-04-18 10:50:00',
            'match_endtime' => '2017-04-18 11:20:00',
            'match_number' => '9',
            'round' => 'Round robin',
          'home_team' =>6,
          'home_team_id' => '6',
          'away_team' => 7,
            'comments' => '',
            'match_winner' => '', 'match_status' => 'Full-time',
            'away_team_id' => '7',
             'hometeam_score' => '0',
             'awayteam_score' => '1',
             'hometeam_point' => '4.00', 'awayteam_point' => '3.00', 'match_result_id' => '1', 'bracket_json' => '', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')
          ],
          ['tournament_id' =>  4,
          'competition_id' => 5,
            'venue_id' => 4,
            'referee_id' => $referee[array_rand($referee)]->id,
            'pitch_id' => 3,
            'is_scheduled' => '0',
            'match_datetime' => '2017-04-18 12:30:00',
            'match_endtime' => '2017-04-18 13:10:00',
            'match_number' => '9',
            'round' => 'Round robin',
          'home_team' =>4,
          'home_team_id' => '4',
          'away_team' => 7,
            'comments' => '',
            'match_winner' => '', 'match_status' => 'Full-time',
            'away_team_id' => '7',
             'hometeam_score' => '1',
             'awayteam_score' => '1',
             'hometeam_point' => '4.00', 'awayteam_point' => '3.00', 'match_result_id' => '1', 'bracket_json' => '', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')
          ],
          ['tournament_id' =>  4,
          'competition_id' => 5,
            'venue_id' => 4,
            'referee_id' => $referee[array_rand($referee)]->id,
            'pitch_id' => 4,
            'is_scheduled' => '0',
            'match_datetime' => '2017-04-18 12:30:00',
            'match_endtime' => '2017-04-18 13:10:00',
            'match_number' => '9',
            'round' => 'Round robin',
          'home_team' =>5,
          'home_team_id' => '5',
          'away_team' => 6,
            'comments' => '',
            'match_winner' => '', 'match_status' => 'Full-time',
            'away_team_id' => '6',
             'hometeam_score' => '0',
             'awayteam_score' => '3',
             'hometeam_point' => '4.00', 'awayteam_point' => '3.00', 'match_result_id' => '1', 'bracket_json' => '', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')
          ],
          ['tournament_id' =>  4,
          'competition_id' => 5,
            'venue_id' => 4,
            'referee_id' => $referee[array_rand($referee)]->id,
            'pitch_id' => 1,
            'is_scheduled' => '0',
            'match_datetime' => '2017-04-19 11:40:00',
            'match_endtime' => '2017-04-19 12:20:00',
            'match_number' => '9',
            'round' => 'Round robin',
          'home_team' =>4,
          'home_team_id' => '4',
          'away_team' => 6,
            'comments' => '',
            'match_winner' => '', 'match_status' => 'Full-time',
            'away_team_id' => '6',
             'hometeam_score' => '0',
             'awayteam_score' => '0',
             'hometeam_point' => '4.00', 'awayteam_point' => '3.00', 'match_result_id' => '1', 'bracket_json' => '', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')
          ],
          ['tournament_id' =>  4,
          'competition_id' => 5,
            'venue_id' => 4,
            'referee_id' => $referee[array_rand($referee)]->id,
            'pitch_id' => 2,
            'is_scheduled' => '0',
            'match_datetime' => '2017-04-19 11:40:00',
            'match_endtime' => '2017-04-19 12:20:00',
            'match_number' => '9',
            'round' => 'Round robin',
          'home_team' =>5,
          'home_team_id' => '5',
          'away_team' => 7,
            'comments' => '',
            'match_winner' => '', 'match_status' => 'Full-time',
            'away_team_id' => '7',
             'hometeam_score' => '1',
             'awayteam_score' => '5',
             'hometeam_point' => '4.00', 'awayteam_point' => '3.00', 'match_result_id' => '1', 'bracket_json' => '', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')
          ],


          ['tournament_id' =>  5,
          'competition_id' => 4,
            'venue_id' => 5,
            'referee_id' => $referee[array_rand($referee)]->id,
            'pitch_id' => 5,
            'is_scheduled' => '0',
            'match_datetime' => '2017-04-15 13:40:00',
            'match_endtime' => '2017-04-15 14:00:00',
            'match_number' => '9',
            'round' => 'Round robin',
          'home_team' =>9,
          'home_team_id' => '9',
          'away_team' => 10,
            'comments' => '',
            'match_winner' => '', 'match_status' => 'Full-time',
            'away_team_id' => '10',
             'hometeam_score' => '3',
             'awayteam_score' => '1',
             'hometeam_point' => '4.00', 'awayteam_point' => '3.00', 'match_result_id' => '1', 'bracket_json' => '', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')
          ],
          ['tournament_id' =>  5,
          'competition_id' => 4,
            'venue_id' => 5,
            'referee_id' => $referee[array_rand($referee)]->id,
            'pitch_id' => 6,
            'is_scheduled' => '0',
            'match_datetime' => '2017-04-15 13:40:00',
            'match_endtime' => '2017-04-15 14:00:00',
            'match_number' => '9',
            'round' => 'Round robin',
          'home_team' =>8,
          'home_team_id' => '8',
          'away_team' => 11,
            'comments' => '',
            'match_winner' => '', 'match_status' => 'Full-time',
            'away_team_id' => '11',
             'hometeam_score' => '1',
             'awayteam_score' => '0',
             'hometeam_point' => '4.00', 'awayteam_point' => '3.00', 'match_result_id' => '1', 'bracket_json' => '', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')
          ],
          ['tournament_id' =>  5,
          'competition_id' => 4,
            'venue_id' => 5,
            'referee_id' => $referee[array_rand($referee)]->id,
            'pitch_id' => 5,
            'is_scheduled' => '0',
            'match_datetime' => '2017-04-15 15:25:00',
            'match_endtime' => '2017-04-15 15:50:00',
            'match_number' => '9',
            'round' => 'Round robin',
          'home_team' =>8,
          'home_team_id' => '8',
          'away_team' => 9,
            'comments' => '',
            'match_winner' => '', 'match_status' => 'Full-time',
            'away_team_id' => '9',
             'hometeam_score' => '0',
             'awayteam_score' => '1',
             'hometeam_point' => '4.00', 'awayteam_point' => '3.00', 'match_result_id' => '1', 'bracket_json' => '', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')
          ],
          ['tournament_id' =>  5,
          'competition_id' => 4,
            'venue_id' => 5,
            'referee_id' => $referee[array_rand($referee)]->id,
            'pitch_id' => 6,
            'is_scheduled' => '0',
            'match_datetime' => '2017-04-15 15:25:00',
            'match_endtime' => '2017-04-15 15:50:00',
            'match_number' => '9',
            'round' => 'Round robin',
          'home_team' =>11,
          'home_team_id' => '11',
          'away_team' => 10,
            'comments' => '',
            'match_winner' => '', 'match_status' => 'Full-time',
            'away_team_id' => '10',
             'hometeam_score' => '0',
             'awayteam_score' => '1',
             'hometeam_point' => '4.00', 'awayteam_point' => '3.00', 'match_result_id' => '1', 'bracket_json' => '', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')
          ],
          ['tournament_id' =>  5,
          'competition_id' => 4,
            'venue_id' => 5,
            'referee_id' => $referee[array_rand($referee)]->id,
            'pitch_id' => 5,
            'is_scheduled' => '0',
            'match_datetime' => '2017-04-15 17:10:00',
            'match_endtime' => '2017-04-15 17:30:00',
            'match_number' => '9',
            'round' => 'Round robin',
          'home_team' =>10,
          'home_team_id' => '10',
          'away_team' => 8,
            'comments' => '',
            'match_winner' => '', 'match_status' => 'Full-time',
            'away_team_id' => '8',
             'hometeam_score' => '0',
             'awayteam_score' => '2',
             'hometeam_point' => '4.00', 'awayteam_point' => '3.00', 'match_result_id' => '1', 'bracket_json' => '', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')
          ],
          ['tournament_id' =>  5,
          'competition_id' => 4,
            'venue_id' => 5,
            'referee_id' => $referee[array_rand($referee)]->id,
            'pitch_id' => 6,
            'is_scheduled' => '0',
            'match_datetime' => '2017-04-15 17:10:00',
            'match_endtime' => '2017-04-15 17:20:00',
            'match_number' => '9',
            'round' => 'Round robin',
          'home_team' =>9,
          'home_team_id' => '9',
          'away_team' => 11,
            'comments' => '',
            'match_winner' => '', 'match_status' => 'Full-time',
            'away_team_id' => '11',
             'hometeam_score' => '0',
             'awayteam_score' => '1',
             'hometeam_point' => '4.00', 'awayteam_point' => '3.00', 'match_result_id' => '1', 'bracket_json' => '', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')
          ],


        ]);
    }
}
