<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use t1st3\JSONMin\JSONMin as jsonMin;

class TournamentsTemplateTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('tournament_template')->truncate();
        DB::table('tournament_template')->insert([
          [ 'name' => 'T.6.3',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template001.json'))),         
          'total_teams' => '6', 'minimum_matches' => '3',
          'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],
          [ 'name' => 'T.6.4',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template002.json'))), 'total_teams' => '6', 'minimum_matches' => '4',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],
          [ 'name' => 'T.6.5',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template003.json'))), 'total_teams' => '6', 'minimum_matches' => '5',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.6.6',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template004.json'))), 'total_teams' => '6', 'minimum_matches' => '6',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],
          [ 'name' => 'T.7.3',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template005.json'))), 'total_teams' => '7', 'minimum_matches' => '3',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],
          [ 'name' => 'T.7.4',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template006.json'))), 'total_teams' => '7', 'minimum_matches' => '4',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],
          [ 'name' => 'T.7.5',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template007.json'))), 'total_teams' => '7', 'minimum_matches' => '5',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],
          [ 'name' => 'T.7.6',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template008.json'))), 'total_teams' => '7', 'minimum_matches' => '6',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],
          [ 'name' => 'T.8.3 (v1)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template009.json'))), 'total_teams' => '8', 'minimum_matches' => '3',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.8.3 (v2)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0010.json'))), 'total_teams' => '8', 'minimum_matches' => '3',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],
            [ 'name' => 'T.8.4',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0011.json'))), 'total_teams' => '8', 'minimum_matches' => '4',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.8.5',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0013.json'))), 'total_teams' => '8', 'minimum_matches' => '5',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],
            [ 'name' => 'T.8.6',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0014.json'))), 'total_teams' => '8', 'minimum_matches' => '6',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],
          [ 'name' => 'T.9.3',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0015.json'))), 'total_teams' => '9', 'minimum_matches' => '3',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],
          [ 'name' => 'T.9.4 (v1)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0016.json'))), 'total_teams' => '9', 'minimum_matches' => '4',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],
          [ 'name' => 'T.9.4 (v2)',
           'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0017.json'))), 'total_teams' => '9', 'minimum_matches' => '4',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],
          [ 'name' => 'T.9.5',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0018.json'))), 'total_teams' => '9', 'minimum_matches' => '5',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],
          [ 'name' => 'T.9.6',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0019.json'))), 'total_teams' => '9', 'minimum_matches' => '6',

            'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],
          [ 'name' => 'T.9.7',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0020.json'))), 'total_teams' => '9', 'minimum_matches' => '7',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],


           [ 'name' => 'T.10.4 (v1)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0022.json'))), 'total_teams' => '10', 'minimum_matches' => '4',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'T.10.4 (v2)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0023.json'))), 'total_teams' => '10', 'minimum_matches' => '4',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],
          [ 'name' => 'T.10.4 (v3)',
           'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0024.json'))), 'total_teams' => '10', 'minimum_matches' => '4',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],
          [ 'name' => 'T.10.5',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0025.json'))), 'total_teams' => '10', 'minimum_matches' => '5',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.20.4',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0081.json'))), 'total_teams' => '20', 'minimum_matches' => '4','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.20.5',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0082.json'))), 'total_teams' => '20', 'minimum_matches' => '5','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.20.6 (v1)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0083.json'))), 'total_teams' => '20', 'minimum_matches' => '6','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.20.6 (v2)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0084.json'))), 'total_teams' => '20', 'minimum_matches' => '6','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.20.6 (v3)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0085.json'))), 'total_teams' => '20', 'minimum_matches' => '6','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.15.3',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0049.json'))), 'total_teams' => '15', 'minimum_matches' => '3','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.15.4',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0050.json'))), 'total_teams' => '15', 'minimum_matches' => '4','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.15.5 (v1)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0051.json'))), 'total_teams' => '15', 'minimum_matches' => '5','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.15.5 (v2)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0052.json'))), 'total_teams' => '15', 'minimum_matches' => '5','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.15.6',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0053.json'))), 'total_teams' => '15', 'minimum_matches' => '6','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.18.3',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0066.json'))), 'total_teams' => '18', 'minimum_matches' => '3','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.18.4',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0068.json'))), 'total_teams' => '18', 'minimum_matches' => '4','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.18.6 (v1)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0072.json'))), 'total_teams' => '18', 'minimum_matches' => '6','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.18.5',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0070.json'))), 'total_teams' => '18', 'minimum_matches' => '5','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.18.6 (v2)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0073.json'))), 'total_teams' => '18', 'minimum_matches' => '6','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.10.6',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0026.json'))), 'total_teams' => '10', 'minimum_matches' => '6','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.11.4 (v1)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0027.json'))), 'total_teams' => '11', 'minimum_matches' => '4','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.11.4 (v2)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0028.json'))), 'total_teams' => '11', 'minimum_matches' => '4','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.21.3',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0086.json'))), 'total_teams' => '21', 'minimum_matches' => '3','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.21.4',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0088.json'))), 'total_teams' => '21', 'minimum_matches' => '4','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.21.5',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0089.json'))), 'total_teams' => '21', 'minimum_matches' => '5','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],


          [ 'name' => 'T.11.5',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0031.json'))), 'total_teams' => '11', 'minimum_matches' => '5','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.11.6',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0032.json'))), 'total_teams' => '11', 'minimum_matches' => '6','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.12.3',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0033.json'))), 'total_teams' => '12', 'minimum_matches' => '3','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.12.4',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0034.json'))), 'total_teams' => '12', 'minimum_matches' => '4','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.12.5 (v1)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0035.json'))), 'total_teams' => '12', 'minimum_matches' => '5','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.12.5 (v2)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0036.json'))), 'total_teams' => '12', 'minimum_matches' => '5','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.12.6',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0037.json'))), 'total_teams' => '12', 'minimum_matches' => '6','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.13.3',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0038.json'))), 'total_teams' => '13', 'minimum_matches' => '3','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.13.4',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0039.json'))), 'total_teams' => '13', 'minimum_matches' => '4','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.13.5',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0041.json'))), 'total_teams' => '13', 'minimum_matches' => '5','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.13.6',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0042.json'))), 'total_teams' => '13', 'minimum_matches' => '6','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.14.3',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0043.json'))), 'total_teams' => '14', 'minimum_matches' => '3','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.14.4',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0044.json'))), 'total_teams' => '14', 'minimum_matches' => '4','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.14.5 (v1)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0045.json'))), 'total_teams' => '14', 'minimum_matches' => '5','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.19.5',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0077.json'))), 'total_teams' => '19', 'minimum_matches' => '5','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.19.6',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0078.json'))), 'total_teams' => '19', 'minimum_matches' => '6','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.21.6',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0092.json'))), 'total_teams' => '21', 'minimum_matches' => '6','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.22.3',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0093.json'))), 'total_teams' => '22', 'minimum_matches' => '3','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.22.4 (v1)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0095.json'))), 'total_teams' => '22', 'minimum_matches' => '4','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.22.4 (v2)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0097.json'))), 'total_teams' => '22', 'minimum_matches' => '4','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.22.5 (v1)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0098.json'))), 'total_teams' => '22', 'minimum_matches' => '5','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.22.5 (v2)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0099.json'))), 'total_teams' => '22', 'minimum_matches' => '5','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.22.6',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0100.json'))), 'total_teams' => '22', 'minimum_matches' => '6','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.23.3',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0101.json'))), 'total_teams' => '23', 'minimum_matches' => '3','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.23.6',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0106.json'))), 'total_teams' => '23', 'minimum_matches' => '6','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.24.3',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0108.json'))), 'total_teams' => '24', 'minimum_matches' => '3','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],


          [ 'name' => 'T.14.6',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0048.json'))), 'total_teams' => '14', 'minimum_matches' => '6','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

           [ 'name' => 'T.14.5 (v2)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0046.json'))), 'total_teams' => '14', 'minimum_matches' => '5','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.16.4 (v2)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0056.json'))), 'total_teams' => '16', 'minimum_matches' => '4','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.16.5 (v1)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0057.json'))), 'total_teams' => '16', 'minimum_matches' => '5','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.16.5 (v2)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0058.json'))), 'total_teams' => '16', 'minimum_matches' => '5','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          ['name' => 'T.16.6',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0059.json'))), 'total_teams' => '16', 'minimum_matches' => '6','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.17.3',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0060.json'))), 'total_teams' => '17', 'minimum_matches' => '3','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          ['name' => 'T.17.5',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0062.json'))), 'total_teams' => '17', 'minimum_matches' => '5','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          ['name' => 'T.17.6',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0064.json'))), 'total_teams' => '17', 'minimum_matches' => '6','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.23.4 (v1)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0102.json'))), 'total_teams' => '23', 'minimum_matches' => '4','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.23.4 (v2)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0103.json'))), 'total_teams' => '23', 'minimum_matches' => '4','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.23.5 (v1)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0104.json'))), 'total_teams' => '23', 'minimum_matches' => '5','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

           [ 'name' => 'T.23.5 (v2)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0105.json'))), 'total_teams' => '23', 'minimum_matches' => '5','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.24.4',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0109.json'))), 'total_teams' => '24', 'minimum_matches' => '4','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],


          [ 'name' => 'T.25.4',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0115.json'))), 'total_teams' => '25', 'minimum_matches' => '4','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.26.5',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0122.json'))), 'total_teams' => '26', 'minimum_matches' => '5','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.28.5 (v1)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0134.json'))), 'total_teams' => '28', 'minimum_matches' => '5','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.28.5 (v2)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0135.json'))), 'total_teams' => '28', 'minimum_matches' => '5','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

           [ 'name' => 'T.28.6 (v1)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0136.json'))), 'total_teams' => '28', 'minimum_matches' => '6','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

           [ 'name' => 'T.28.6 (v2)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0137.json'))), 'total_teams' => '28', 'minimum_matches' => '6','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

           [ 'name' => 'T.28.6 (v3)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0138.json'))), 'total_teams' => '28', 'minimum_matches' => '6','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

            [ 'name' => 'T.28.4',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0133.json'))), 'total_teams' => '28', 'minimum_matches' => '4','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.28.3',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0132.json'))), 'total_teams' => '28', 'minimum_matches' => '3','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.24.5 (v1)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0110.json'))), 'total_teams' => '24', 'minimum_matches' => '5','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.24.5 (v2)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0111.json'))), 'total_teams' => '24', 'minimum_matches' => '5','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.24.6',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0112.json'))), 'total_teams' => '24', 'minimum_matches' => '6','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.25.3',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0113.json'))), 'total_teams' => '25', 'minimum_matches' => '3','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.25.5',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0116.json'))), 'total_teams' => '25', 'minimum_matches' => '5','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.25.6',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0118.json'))), 'total_teams' => '25', 'minimum_matches' => '6','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.26.3',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0119.json'))), 'total_teams' => '26', 'minimum_matches' => '3','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.26.4',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0121.json'))), 'total_teams' => '26', 'minimum_matches' => '4','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.26.6',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0123.json'))), 'total_teams' => '26', 'minimum_matches' => '6','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.27.4 (v1)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0125.json'))), 'total_teams' => '27', 'minimum_matches' => '4','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.27.4 (v2)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0126.json'))), 'total_teams' => '27', 'minimum_matches' => '4','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

           [ 'name' => 'T.16.3',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0054.json'))), 'total_teams' => '16', 'minimum_matches' => '3','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

           [ 'name' => 'T.19.3',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0075.json'))), 'total_teams' => '19', 'minimum_matches' => '3','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.19.4',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0076.json'))), 'total_teams' => '19', 'minimum_matches' => '4','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

           [ 'name' => 'T.27.5',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0127.json'))), 'total_teams' => '27', 'minimum_matches' => '5','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.27.6',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0129.json'))), 'total_teams' => '27', 'minimum_matches' => '6','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.20.3',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0079.json'))), 'total_teams' => '20', 'minimum_matches' => '3','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.16.4 (v1)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0055.json'))), 'total_teams' => '16', 'minimum_matches' => '4','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.4.4',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0139.json'))), 'total_teams' => '4', 'minimum_matches' => '4','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.4.5',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0140.json'))), 'total_teams' => '4', 'minimum_matches' => '5','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.4.6',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0141.json'))), 'total_teams' => '4', 'minimum_matches' => '6','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.5.4',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0142.json'))), 'total_teams' => '5', 'minimum_matches' => '4','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.5.5',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0143.json'))), 'total_teams' => '5', 'minimum_matches' => '5','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

           [ 'name' => 'T.5.6',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0144.json'))), 'total_teams' => '5', 'minimum_matches' => '6','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],


        ]);
DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}