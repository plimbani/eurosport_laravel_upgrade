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
        	[ 'name' => 'template001',
          'json_data' => file_get_contents(public_path('templates/template001.json')), 'total_teams' => '6', 'minimum_matches' => '6',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
        	[ 'name' => 'template002',
          'json_data' => file_get_contents(public_path('templates/template002.json')), 'total_teams' => '7', 'minimum_matches' => '5',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'template0015',
          'json_data' => file_get_contents(public_path('templates/template0015.json')), 'total_teams' => '9', 'minimum_matches' => '3',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'template0016',
          'json_data' => file_get_contents(public_path('templates/template0016.json')), 'total_teams' => '9', 'minimum_matches' => '4',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'template0017',
           'json_data' => file_get_contents(public_path('templates/template0017.json')), 'total_teams' => '9', 'minimum_matches' => '6',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'template0018',
          'json_data' => file_get_contents(public_path('templates/template0018.json')), 'total_teams' => '9', 'minimum_matches' => '6',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'template0019',
          'json_data' => file_get_contents(public_path('templates/template0019.json')), 'total_teams' => '9', 'minimum_matches' => '5',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'template0020',
          'json_data' => file_get_contents(public_path('templates/template0020.json')), 'total_teams' => '9', 'minimum_matches' => '6',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

        ]);
    }
}
