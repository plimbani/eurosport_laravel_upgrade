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
          [ 'name' => 'T.6.3',
          'json_data' => file_get_contents(public_path('templates/template001.json')),
          'total_teams' => '6', 'minimum_matches' => '3',
          'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'T.6.4',
          'json_data' => file_get_contents(public_path('templates/template002.json')), 'total_teams' => '6', 'minimum_matches' => '4',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'T.6.5',
          'json_data' => file_get_contents(public_path('templates/template003.json')), 'total_teams' => '6', 'minimum_matches' => '5',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

          [ 'name' => 'T.6.6',
          'json_data' => file_get_contents(public_path('templates/template004.json')), 'total_teams' => '6', 'minimum_matches' => '6',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'T.7.3',
          'json_data' => file_get_contents(public_path('templates/template005.json')), 'total_teams' => '7', 'minimum_matches' => '3',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'T.7.4',
          'json_data' => file_get_contents(public_path('templates/template006.json')), 'total_teams' => '7', 'minimum_matches' => '4',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'T.7.5',
          'json_data' => file_get_contents(public_path('templates/template007.json')), 'total_teams' => '7', 'minimum_matches' => '5',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'T.7.6',
          'json_data' => file_get_contents(public_path('templates/template008.json')), 'total_teams' => '7', 'minimum_matches' => '6',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'T.8.3 (v1)',
          'json_data' => file_get_contents(public_path('templates/template009.json')), 'total_teams' => '8', 'minimum_matches' => '3',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'T.8.3 (v2)',
          'json_data' => file_get_contents(public_path('templates/template0010.json')), 'total_teams' => '8', 'minimum_matches' => '3',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'name' => 'T.8.4',
          'json_data' => file_get_contents(public_path('templates/template0011.json')), 'total_teams' => '8', 'minimum_matches' => '4',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

          [ 'name' => 'T.8.5',
          'json_data' => file_get_contents(public_path('templates/template0013.json')), 'total_teams' => '8', 'minimum_matches' => '5',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'name' => 'T.8.6',
          'json_data' => file_get_contents(public_path('templates/template0014.json')), 'total_teams' => '8', 'minimum_matches' => '6',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'T.9.3',
          'json_data' => file_get_contents(public_path('templates/template0015.json')), 'total_teams' => '9', 'minimum_matches' => '3',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'T.9.4 (v1)',
          'json_data' => file_get_contents(public_path('templates/template0016.json')), 'total_teams' => '9', 'minimum_matches' => '4',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'T.9.4 (v2)',
           'json_data' => file_get_contents(public_path('templates/template0017.json')), 'total_teams' => '9', 'minimum_matches' => '4',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'T.9.5 (v1)',
          'json_data' => file_get_contents(public_path('templates/template0018.json')), 'total_teams' => '9', 'minimum_matches' => '5',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'T.9.5 (v2)',
          'json_data' => file_get_contents(public_path('templates/template0019.json')), 'total_teams' => '9', 'minimum_matches' => '5',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'T.9.6',
          'json_data' => file_get_contents(public_path('templates/template0020.json')), 'total_teams' => '9', 'minimum_matches' => '6',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')],


           [ 'name' => 'T.10.4 (v1)',
          'json_data' => file_get_contents(public_path('templates/template0022.json')), 'total_teams' => '10', 'minimum_matches' => '4',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'T.10.4 (v2)',
          'json_data' => file_get_contents(public_path('templates/template0023.json')), 'total_teams' => '10', 'minimum_matches' => '4',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'T.10.4 (v3)',
           'json_data' => file_get_contents(public_path('templates/template0024.json')), 'total_teams' => '10', 'minimum_matches' => '4',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'T.10.5',
          'json_data' => file_get_contents(public_path('templates/template0025.json')), 'total_teams' => '10', 'minimum_matches' => '5',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

             [ 'name' => 'T.20.5',
          'json_data' => file_get_contents(public_path('templates/template0082.json')), 'total_teams' => '20', 'minimum_matches' => '5','created_at' => Carbon::now()->format('Y-m-d H:i:s')],

               [ 'name' => 'T.20.6 (v1)',
          'json_data' => file_get_contents(public_path('templates/template0083.json')), 'total_teams' => '20', 'minimum_matches' => '6','created_at' => Carbon::now()->format('Y-m-d H:i:s')],

                 [ 'name' => 'T.20.6 (v2)',
          'json_data' => file_get_contents(public_path('templates/template0084.json')), 'total_teams' => '20', 'minimum_matches' => '6','created_at' => Carbon::now()->format('Y-m-d H:i:s')],

           [ 'name' => 'T.20.6 (v3)',
          'json_data' => file_get_contents(public_path('templates/template0085.json')), 'total_teams' => '20', 'minimum_matches' => '6','created_at' => Carbon::now()->format('Y-m-d H:i:s')]

        ]);
    }
}
