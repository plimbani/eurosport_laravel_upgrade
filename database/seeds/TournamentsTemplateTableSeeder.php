<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TournamentsTemplateTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tournament_template')->truncate();
        DB::table('tournament_template')->insert([
        	[ 'name' => 'template001', 'json_data' => file_get_contents(public_path('templates/template001.json')), 'total_teams' => '6', 'minimum_matches' => '6', 
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            
        	[ 'name' => 'template002', 'json_data' => file_get_contents(public_path('templates/template001.json')), 'total_teams' => '7', 'minimum_matches' => '5', 
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')]
        ]);
    }
}
