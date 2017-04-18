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
        	['tournament_competation_template_id' => array_rand($tournament), 'tournament_id' =>  array_rand($tournaments), 'name' => 'U10-K.O. 1-4', 'team_size' => '4', 'competation_type' => 'Round Robin'],
        	['tournament_competation_template_id' => array_rand($tournament), 'tournament_id' =>  array_rand($tournaments), 'name' => 'U10-K.O. 5-8', 'team_size' => '4', 'competation_type' => 'Round Robin'],
        	['tournament_competation_template_id' => array_rand($tournament), 'tournament_id' =>  array_rand($tournaments), 'name' => 'U10-Group A', 'team_size' => '4', 'competation_type' => 'Round Robin']
        ]);
    }
}
