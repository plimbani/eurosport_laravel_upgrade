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
        /*$tournament = DB::table('tournaments')->take(3)->select('id')->get()->toArray();
        $competition = DB::table('competitions')->take(3)->select('id')->get()->toArray();
        $venue = DB::table('venues')->take(3)->select('id')->get()->toArray();
        $pitch = DB::table('pitches')->take(3)->select('id')->get()->toArray();
        $referee = DB::table('referee')->take(3)->select('id')->get()->toArray();
        $teams = DB::table('teams')->take(3)->select('id')->get()->toArray();
        $teamsName = DB::table('teams')->pluck('name','id')->toArray(); */

      /*  DB::table('temp_fixtures')->insert(
          [
          ['tournament_id' =>  4,
          'competition_id' => 5, 'venue_id' => 4,
           'referee_id' => 4,
            'pitch_id' => 1,
            'is_scheduled' => '1',
            'match_datetime' => '2017-04-18 10:50:00',
            'match_endtime' => '2017-04-18 11:20:00',
            'match_number' => 'U19-U17-RR1.01.A1-A3',
            'round' => 'Round robin',
          'home_team_name' =>'Heider SV',
          'home_team' => '4',
          'away_team_name' => 'F.C. Saint Henri',
            'comments' => '',
            'match_winner' => '', 'match_status' => 'Full-time',
            'away_team' => '6',
             'hometeam_score' => '12',
             'awayteam_score' => '0',
             'hometeam_point' => '4.00', 'awayteam_point' => '3.00', 'match_result_id' => '1', 'bracket_json' => '', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')
          ],
          ['tournament_id' =>  4,
          'competition_id' => 5,
            'venue_id' => 4,
            'referee_id' => 4,
            'pitch_id' => 2,
            'is_scheduled' => '1',
            'match_datetime' => '2017-04-18 10:50:00',
            'match_endtime' => '2017-04-18 11:20:00',
            'match_number' => '1',
            'round' => 'Round robin',
          'home_team_name' =>$teamsName[6],
          'home_team' => '6',
          'away_team_name' => $teamsName[7],
            'comments' => '',
            'match_winner' => '', 'match_status' => 'Full-time',
            'away_team' => '7',
             'hometeam_score' => '0',
             'awayteam_score' => '1',
             'hometeam_point' => '4.00', 'awayteam_point' => '3.00', 'match_result_id' => '1', 'bracket_json' => '', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')
          ],
          ['tournament_id' =>  4,
          'competition_id' => 5,
            'venue_id' => 4,
            'referee_id' => 4,
            'pitch_id' => 3,
            'is_scheduled' => '1',
            'match_datetime' => '2017-04-18 12:30:00',
            'match_endtime' => '2017-04-18 13:10:00',
            'match_number' => '1',
            'round' => 'Round robin',
          'home_team_name' =>$teamsName[4],
          'home_team' => '4',
          'away_team_name' => $teamsName[7],
            'comments' => '',
            'match_winner' => '', 'match_status' => 'Full-time',
            'away_team' => '7',
             'hometeam_score' => '1',
             'awayteam_score' => '1',
             'hometeam_point' => '4.00', 'awayteam_point' => '3.00', 'match_result_id' => '1', 'bracket_json' => '', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')
          ],
          ['tournament_id' =>  4,
          'competition_id' => 5,
            'venue_id' => 4,
            'referee_id' => 4,
            'pitch_id' => 4,
            'is_scheduled' => '1',
            'match_datetime' => '2017-04-18 12:30:00',
            'match_endtime' => '2017-04-18 13:10:00',
            'match_number' => '9',
            'round' => 'Round robin',
          'home_team_name' =>$teamsName[5],
          'home_team' => '5',
          'away_team_name' => $teamsName[6],
            'comments' => '',
            'match_winner' => '', 'match_status' => 'Full-time',
            'away_team' => '6',
             'hometeam_score' => '0',
             'awayteam_score' => '3',
             'hometeam_point' => '4.00', 'awayteam_point' => '3.00', 'match_result_id' => '1', 'bracket_json' => '', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')
          ],
          ['tournament_id' =>  4,
          'competition_id' => 5,
            'venue_id' => 4,
            'referee_id' => 4,
            'pitch_id' => 1,
            'is_scheduled' => '1',
            'match_datetime' => '2017-04-19 11:40:00',
            'match_endtime' => '2017-04-19 12:20:00',
            'match_number' => '9',
            'round' => 'Round robin',
          'home_team_name' =>$teamsName[4],
          'home_team' => '4',
          'away_team_name' => $teamsName[6],
            'comments' => '',
            'match_winner' => '', 'match_status' => 'Full-time',
            'away_team' => '6',
             'hometeam_score' => '0',
             'awayteam_score' => '0',
             'hometeam_point' => '4.00', 'awayteam_point' => '3.00', 'match_result_id' => '1', 'bracket_json' => '', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')
          ],
          ['tournament_id' =>  4,
          'competition_id' => 5,
            'venue_id' => 4,
            'referee_id' => 4,
            'pitch_id' => 2,
            'is_scheduled' => '1',
            'match_datetime' => '2017-04-19 11:40:00',
            'match_endtime' => '2017-04-19 12:20:00',
            'match_number' => '9',
            'round' => 'Round robin',
          'home_team_name' =>$teamsName[5],
          'home_team' => '5',
          'away_team_name' => $teamsName[7],
            'comments' => '',
            'match_winner' => '', 'match_status' => 'Full-time',
            'away_team' => '7',
             'hometeam_score' => '1',
             'awayteam_score' => '5',
             'hometeam_point' => '4.00', 'awayteam_point' => '3.00', 'match_result_id' => '1', 'bracket_json' => '', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')
          ]
        ]); */
    }
}
