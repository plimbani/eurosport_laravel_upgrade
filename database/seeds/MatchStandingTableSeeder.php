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
        	['tournament_id' => $tournament[array_rand($tournament)]->id, 'competition_id' => $competition[array_rand($competition)]->id, 'team_id' =>  $teams[array_rand($teams)]->id, 'points' => '4',
        	'played' => '3', 'won' => '1', 'draws' => '1', 'lost' => '1', 'goal_for' => '5',
        	'goal_against' => '7', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

        	['tournament_id' => $tournament[array_rand($tournament)]->id, 'competition_id' => $competition[array_rand($competition)]->id, 'team_id' =>  $teams[array_rand($teams)]->id, 'points' => '3',
        	'played' => '3', 'won' => '1', 'draws' => '0', 'lost' => '2', 'goal_for' => '7',
        	'goal_against' => '5', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

        	['tournament_id' => $tournament[array_rand($tournament)]->id, 'competition_id' => $competition[array_rand($competition)]->id, 'team_id' =>  $teams[array_rand($teams)]->id, 'points' => '5',
        	'played' => '3', 'won' => '1', 'draws' => '1', 'lost' => '1','goal_for' => '4',
        	'goal_against' => '7', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')
          ],

          ['tournament_id' => 4, 'competition_id' => 5,
           'team_id' =>  7, 'points' => '7',
          'played' => '3', 'won' => '2', 'draws' => '1', 'lost' => '0','goal_for' => '7',
          'goal_against' => '2', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')
          ],
          ['tournament_id' => 4, 'competition_id' => 5, 'team_id' =>  4, 'points' => '5',
          'played' => '3', 'won' => '1', 'draws' => '2', 'lost' => '0','goal_for' => '13',
          'goal_against' => '1', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')
          ],
          ['tournament_id' =>4, 'competition_id' => 5, 'team_id' => 6, 'points' => '4',
          'played' => '3', 'won' => '1', 'draws' => '1', 'lost' => '1','goal_for' => '3',
          'goal_against' => '1', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')
          ],
          ['tournament_id' => 4, 'competition_id' => 5, 'team_id' =>  5, 'points' => '0',
          'played' => '3', 'won' => '0', 'draws' => '0', 'lost' => '3','goal_for' => '1',
          'goal_against' => '20', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')
          ],


          ['tournament_id' => 5, 'competition_id' => 4,
           'team_id' =>  7, 'points' => '6',
          'played' => '3', 'won' => '2', 'draws' => '0', 'lost' => '1','goal_for' => '4',
          'goal_against' => '2', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')
          ],
          ['tournament_id' => 5, 'competition_id' => 4, 'team_id' =>  4, 'points' => '6',
          'played' => '3', 'won' => '2', 'draws' => '0', 'lost' => '1','goal_for' => '3',
          'goal_against' => '1', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')
          ],
          ['tournament_id' =>5, 'competition_id' => 4, 'team_id' => 6, 'points' => '3',
          'played' => '3', 'won' => '1', 'draws' => '0', 'lost' => '2','goal_for' => '1',
          'goal_against' => '2', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')
          ],
          ['tournament_id' => 5, 'competition_id' => 4, 'team_id' =>  5, 'points' => '3',
          'played' => '3', 'won' => '1', 'draws' => '0', 'lost' => '2','goal_for' => '2',
          'goal_against' => '5', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')
          ],



        ]);
    }
}
