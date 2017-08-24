<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CompetitionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('competitions')->truncate();
        $tournament = DB::table('tournament_competation_template')->select('id')->take(3)->get()->toArray();
        $tournaments = DB::table('tournaments')->take(3)->select('id')->get()->toArray();

        DB::table('competitions')->insert([
        	['tournament_competation_template_id' => $tournament[array_rand($tournament)]->id,
            'tournament_id' =>  $tournaments[array_rand($tournaments)]->id, 'name' => 'U10-K.O. 1-4',
            'team_size' => '4', 'competation_type' => 'Round Robin'],
        	['tournament_competation_template_id' => $tournament[array_rand($tournament)]->id,
            'tournament_id' =>  $tournaments[array_rand($tournaments)]->id, 'name' => 'U10-K.O. 5-8',
            'team_size' => '4', 'competation_type' => 'Round Robin'],
        	['tournament_competation_template_id' => $tournament[array_rand($tournament)]->id,
            'tournament_id' =>  $tournaments[array_rand($tournaments)]->id, 'name' => 'U10-Group A',
            'team_size' => '4', 'competation_type' => 'Round Robin'
          ],

          ['tournament_competation_template_id' => 4,
            'tournament_id' =>  5, 'name' => 'U19-Group A',
            'team_size' => '4', 'competation_type' => 'Round Robin'
          ],
          ['tournament_competation_template_id' => 5,
            'tournament_id' =>  4, 'name' => 'U15-Group A',
            'team_size' => '4', 'competation_type' => 'Round Robin'
          ]


        ]);
    }
}
