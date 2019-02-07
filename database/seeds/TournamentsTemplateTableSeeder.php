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
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template001.json'))), 'graphic_image' => null,        
          'total_teams' => '6', 'minimum_matches' => '3', 'position_type' => 'final',
          'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],
          [ 'name' => 'T.6.4',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template002.json'))), 'graphic_image' => null, 'total_teams' => '6', 'minimum_matches' => '4', 'position_type' => 'final',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],
          [ 'name' => 'T.6.5',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template003.json'))), 'graphic_image' => null, 'total_teams' => '6', 'minimum_matches' => '5', 'position_type' => 'final_and_group_ranking',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.6.6',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template004.json'))), 'graphic_image' => null, 'total_teams' => '6', 'minimum_matches' => '6', 'position_type' => 'final',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],
          [ 'name' => 'T.7.3',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template005.json'))), 'graphic_image' => null, 'total_teams' => '7', 'minimum_matches' => '3', 'position_type' => 'final_and_group_ranking',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],
          [ 'name' => 'T.7.4',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template006.json'))), 'graphic_image' => null, 'total_teams' => '7', 'minimum_matches' => '4', 'position_type' => 'final_and_group_ranking',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],
          [ 'name' => 'T.7.5',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template007.json'))), 'graphic_image' => null, 'total_teams' => '7', 'minimum_matches' => '5', 'position_type'=> 'group_ranking',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],
          [ 'name' => 'T.7.6',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template008.json'))), 'graphic_image' => null, 'total_teams' => '7', 'minimum_matches' => '6', 'position_type' => 'final_and_group_ranking',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],
          [ 'name' => 'T.8.3 (v1)',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template009.json'))), 'graphic_image' => '/assets/img/template_graphic_image/TT09_v1.jpg', 'total_teams' => '8', 'minimum_matches' => '3', 'position_type' => 'final',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.8.3 (v2)',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0010.json'))), 'graphic_image' => '/assets/img/template_graphic_image/TT10_v2.jpg', 'total_teams' => '8', 'minimum_matches' => '3', 'position_type' => 'final',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],
            [ 'name' => 'T.8.4',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0011.json'))), 'graphic_image' => '/assets/img/template_graphic_image/TT11.jpg'
            , 'total_teams' => '8', 'minimum_matches' => '4', 'position_type' => 'final',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.8.5 (v1)',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0013.json'))), 'graphic_image' => '/assets/img/template_graphic_image/TT13_v1.jpg', 'total_teams' => '8', 'minimum_matches' => '5', 'position_type' => 'final',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],
            [ 'name' => 'T.8.6',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0014.json'))), 'graphic_image' => '/assets/img/template_graphic_image/TT14.jpg', 'total_teams' => '8', 'minimum_matches' => '6', 'position_type'=> 'group_ranking',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],
          [ 'name' => 'T.9.3',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0015.json'))), 'graphic_image' => null, 'total_teams' => '9', 'minimum_matches' => '3', 'position_type' => 'final',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],
          [ 'name' => 'T.9.4 (v1)',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0016.json'))), 'graphic_image' => null, 'total_teams' => '9', 'minimum_matches' => '4', 'position_type'=> 'group_ranking',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],
          [ 'name' => 'T.9.4 (v2)',
           'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0017.json'))), 'graphic_image' => null, 'total_teams' => '9', 'minimum_matches' => '4', 'position_type' => 'final_and_group_ranking',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],
          [ 'name' => 'T.9.5',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0018.json'))), 'graphic_image' => null, 'total_teams' => '9', 'minimum_matches' => '5', 'position_type' => 'final_and_group_ranking',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],
          [ 'name' => 'T.9.6',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0019.json'))), 'graphic_image' => null, 'total_teams' => '9', 'minimum_matches' => '6', 'position_type' => 'final_and_group_ranking',

            'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],
          [ 'name' => 'T.9.7',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0020.json'))), 'graphic_image' => null, 'total_teams' => '9', 'minimum_matches' => '7', 'position_type' => 'final_and_group_ranking',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],


           [ 'name' => 'T.10.4 (v1)',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0022.json'))), 'graphic_image' => null, 'total_teams' => '10', 'minimum_matches' => '4', 'position_type' => 'final', 
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'T.10.4 (v2)',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0023.json'))), 'graphic_image' => null, 'total_teams' => '10', 'minimum_matches' => '4', 'position_type' => 'final',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],
          [ 'name' => 'T.10.4 (v3)',
           'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0024.json'))), 'graphic_image' => null, 'total_teams' => '10', 'minimum_matches' => '4', 'position_type' => 'final',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],
          [ 'name' => 'T.10.5',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0025.json'))), 'graphic_image' => null, 'total_teams' => '10', 'minimum_matches' => '5', 'position_type' => 'final',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.20.4',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0081.json'))), 'graphic_image' => null, 'total_teams' => '20', 'minimum_matches' => '4', 'position_type' => 'final',
          'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.20.5',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0082.json'))), 'graphic_image' => null, 'total_teams' => '20', 'minimum_matches' => '5', 'position_type' => 'final',
          'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.20.6 (v1)',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0083.json'))), 'graphic_image' => null, 'total_teams' => '20', 'minimum_matches' => '6', 'position_type' => 'final',
          'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.20.6 (v2)',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0084.json'))), 'graphic_image' => null, 'total_teams' => '20', 'minimum_matches' => '6', 'position_type' => 'final',
          'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.20.6 (v3)',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0085.json'))), 'graphic_image' => null, 'total_teams' => '20', 'minimum_matches' => '6', 'position_type' => 'final',
          'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.15.3',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0049.json'))), 'graphic_image' => null, 'total_teams' => '15', 'minimum_matches' => '3', 'position_type' => 'final',
           'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.15.4',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0050.json'))), 'graphic_image' => null, 'total_teams' => '15', 'minimum_matches' => '4', 'position_type'=> 'group_ranking',
          'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.15.5 (v1)',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0051.json'))), 'graphic_image' => null, 'total_teams' => '15', 'minimum_matches' => '5',  'position_type' => 'final_and_group_ranking',
          'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.15.5 (v2)',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0052.json'))), 'graphic_image' => null, 'total_teams' => '15', 'minimum_matches' => '5', 'position_type'=> 'group_ranking',
          'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.15.6',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0053.json'))), 'graphic_image' => null, 'total_teams' => '15', 'minimum_matches' => '6', 'position_type'=> 'group_ranking',
          'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.18.3',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0066.json'))), 'graphic_image' => null, 'total_teams' => '18', 'minimum_matches' => '3', 'position_type' => 'final',
          'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.18.4',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0068.json'))), 'graphic_image' => null, 'total_teams' => '18', 'minimum_matches' => '4', 'position_type' => 'final',
          'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.18.6 (v1)',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0072.json'))), 'graphic_image' => null, 'total_teams' => '18', 'minimum_matches' => '6', 'position_type' => 'final',
          'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.18.5',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0070.json'))), 'graphic_image' => null, 'total_teams' => '18', 'minimum_matches' => '5', 'position_type' => 'final',
          'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.18.6 (v2)',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0073.json'))), 'graphic_image' => null, 'total_teams' => '18', 'minimum_matches' => '6', 'position_type'=> 'group_ranking',
          'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.10.6',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0026.json'))), 'graphic_image' => null, 'total_teams' => '10', 'minimum_matches' => '6', 'position_type' => 'final',
          'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.11.4 (v1)',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0027.json'))), 'graphic_image' => null, 'total_teams' => '11', 'minimum_matches' => '4', 'position_type'=> 'group_ranking',
          'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.11.4 (v2)',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0028.json'))), 'graphic_image' => null, 'total_teams' => '11', 'minimum_matches' => '4', 'position_type' => 'final_and_group_ranking',
          'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.21.3',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0086.json'))), 'graphic_image' => null, 'total_teams' => '21', 'minimum_matches' => '3', 'position_type' => 'final',
          'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.21.4',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0088.json'))), 'graphic_image' => null, 'total_teams' => '21', 'minimum_matches' => '4', 'position_type' => 'final',
          'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.21.5',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0089.json'))), 'graphic_image' => null, 'total_teams' => '21', 'minimum_matches' => '5', 'position_type' => 'final',
          'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],


          [ 'name' => 'T.11.5',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0031.json'))), 'graphic_image' => null, 'total_teams' => '11', 'minimum_matches' => '5', 'position_type' => 'final_and_group_ranking',
          'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.11.6',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0032.json'))), 'graphic_image' => null, 'total_teams' => '11', 'minimum_matches' => '6', 'position_type' => 'final_and_group_ranking',
          'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.12.3',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0033.json'))), 'graphic_image' => null, 'total_teams' => '12', 'minimum_matches' => '3', 'position_type' => 'group_ranking','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.12.4',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0034.json'))), 'graphic_image' => null, 'total_teams' => '12', 'minimum_matches' => '4', 'position_type' => 'final','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.12.5 (v1)',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0035.json'))), 'graphic_image' => null, 'total_teams' => '12', 'minimum_matches' => '5','position_type' => 'final_and_group_ranking','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.12.5 (v2)',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0036.json'))), 'graphic_image' => null, 'total_teams' => '12', 'minimum_matches' => '5','position_type' => 'group_ranking','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.12.6 (v1)',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0037.json'))), 'graphic_image' => null, 'total_teams' => '12', 'minimum_matches' => '6','position_type' => 'final','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.13.3',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0038.json'))), 'graphic_image' => null, 'total_teams' => '13', 'minimum_matches' => '3','position_type' => 'group_ranking','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.13.4',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0039.json'))), 'graphic_image' => null, 'total_teams' => '13', 'minimum_matches' => '4','position_type' => 'final_and_group_ranking','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.13.5',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0041.json'))), 'graphic_image' => null, 'total_teams' => '13', 'minimum_matches' => '5','position_type' => 'final_and_group_ranking','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.13.6',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0042.json'))), 'graphic_image' => null, 'total_teams' => '13', 'minimum_matches' => '6','position_type' => 'final_and_group_ranking','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.14.3',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0043.json'))), 'graphic_image' => null, 'total_teams' => '14', 'minimum_matches' => '3','position_type' => 'final','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.14.4',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0044.json'))), 'graphic_image' => null, 'total_teams' => '14', 'minimum_matches' => '4','position_type' => 'final','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.14.5 (v1)',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0045.json'))), 'graphic_image' => null, 'total_teams' => '14', 'minimum_matches' => '5','position_type' => 'final_and_group_ranking','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.19.5',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0077.json'))), 'graphic_image' => null, 'total_teams' => '19', 'minimum_matches' => '5','position_type' => 'final_and_group_ranking','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.19.6',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0078.json'))), 'graphic_image' => null, 'total_teams' => '19', 'minimum_matches' => '6','position_type' => 'final_and_group_ranking','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.21.6',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0092.json'))), 'graphic_image' => null, 'total_teams' => '21', 'minimum_matches' => '6','position_type' => 'final','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.22.3',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0093.json'))), 'graphic_image' => null, 'total_teams' => '22', 'minimum_matches' => '3','position_type' => 'group_ranking','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.22.4 (v1)',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0095.json'))), 'graphic_image' => null, 'total_teams' => '22', 'minimum_matches' => '4','position_type' => 'final','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.22.4 (v2)',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0097.json'))), 'graphic_image' => null, 'total_teams' => '22', 'minimum_matches' => '4','position_type' => 'final','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.22.5 (v1)',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0098.json'))), 'graphic_image' => null, 'total_teams' => '22', 'minimum_matches' => '5','position_type' => 'final','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.22.5 (v2)',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0099.json'))), 'graphic_image' => null, 'total_teams' => '22', 'minimum_matches' => '5','position_type' => 'final','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.22.6',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0100.json'))), 'graphic_image' => null, 'total_teams' => '22', 'minimum_matches' => '6','position_type' => 'final','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.23.3',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0101.json'))), 'graphic_image' => null, 'total_teams' => '23', 'minimum_matches' => '3','position_type' => 'group_ranking','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.23.6',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0106.json'))), 'graphic_image' => null, 'total_teams' => '23', 'minimum_matches' => '6','position_type' => 'final_and_group_ranking','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.24.3',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0108.json'))), 'graphic_image' => null, 'total_teams' => '24', 'minimum_matches' => '3','position_type' => 'final','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],


          [ 'name' => 'T.14.6',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0048.json'))), 'graphic_image' => null, 'total_teams' => '14', 'minimum_matches' => '6','position_type' => 'final','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

           [ 'name' => 'T.14.5 (v2)',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0046.json'))), 'graphic_image' => null, 'total_teams' => '14', 'minimum_matches' => '5','position_type' => 'final_and_group_ranking','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.16.4 (v2)',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0056.json'))), 'graphic_image' => null, 'total_teams' => '16', 'minimum_matches' => '4','position_type' => 'final','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.16.5 (v1)',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0057.json'))), 'graphic_image' => null, 'total_teams' => '16', 'minimum_matches' => '5','position_type' => 'final','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.16.5 (v2)',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0058.json'))), 'graphic_image' => null, 'total_teams' => '16', 'minimum_matches' => '5','position_type' => 'final','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          ['name' => 'T.16.6',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0059.json'))), 'graphic_image' => null, 'total_teams' => '16', 'minimum_matches' => '6','position_type' => 'final','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.17.3',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0060.json'))), 'graphic_image' => null, 'total_teams' => '17', 'minimum_matches' => '3','position_type' => 'final','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          ['name' => 'T.17.5',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0062.json'))), 'graphic_image' => null, 'total_teams' => '17', 'minimum_matches' => '5','position_type' => 'final_and_group_ranking','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          ['name' => 'T.17.6',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0064.json'))), 'graphic_image' => null, 'total_teams' => '17', 'minimum_matches' => '6','position_type' => 'group_ranking','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.23.4 (v1)',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0102.json'))), 'graphic_image' => null, 'total_teams' => '23', 'minimum_matches' => '4','position_type' => 'final','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.23.4 (v2)',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0103.json'))), 'graphic_image' => null, 'total_teams' => '23', 'minimum_matches' => '4','position_type' => 'final','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.23.5 (v1)',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0104.json'))), 'graphic_image' => null, 'total_teams' => '23', 'minimum_matches' => '5','position_type' => 'final','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

           [ 'name' => 'T.23.5 (v2)',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0105.json'))), 'graphic_image' => null, 'total_teams' => '23', 'minimum_matches' => '5','position_type' => 'final','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.24.4',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0109.json'))), 'graphic_image' => null, 'total_teams' => '24', 'minimum_matches' => '4','position_type' => 'final','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],


          [ 'name' => 'T.25.4',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0115.json'))), 'graphic_image' => null, 'total_teams' => '25', 'minimum_matches' => '4','position_type' => 'final','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.26.5',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0122.json'))), 'graphic_image' => null, 'total_teams' => '26', 'minimum_matches' => '5','position_type' => 'final','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.28.5 (v1)',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0134.json'))), 'graphic_image' => null, 'total_teams' => '28', 'minimum_matches' => '5','position_type' => 'final','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.28.5 (v2)',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0135.json'))), 'graphic_image' => null, 'total_teams' => '28', 'minimum_matches' => '5','position_type' => 'final','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

           [ 'name' => 'T.28.6 (v1)',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0136.json'))), 'graphic_image' => null, 'total_teams' => '28', 'minimum_matches' => '6','position_type' => 'final','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

           [ 'name' => 'T.28.6 (v2)',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0137.json'))), 'graphic_image' => null, 'total_teams' => '28', 'minimum_matches' => '6','position_type' => 'final','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

           [ 'name' => 'T.28.6 (v3)',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0138.json'))), 'graphic_image' => null, 'total_teams' => '28', 'minimum_matches' => '6','position_type' => 'final','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

            [ 'name' => 'T.28.4',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0133.json'))), 'graphic_image' => null, 'total_teams' => '28', 'minimum_matches' => '4','position_type' => 'group_ranking','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.28.3',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0132.json'))), 'graphic_image' => null, 'total_teams' => '28', 'minimum_matches' => '3','position_type' => 'group_ranking','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.24.5 (v1)',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0110.json'))), 'graphic_image' => null, 'total_teams' => '24', 'minimum_matches' => '5','position_type' => 'final','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.24.5 (v2)',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0111.json'))), 'graphic_image' => null, 'total_teams' => '24', 'minimum_matches' => '5','position_type' => 'final','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.24.6',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0112.json'))), 'graphic_image' => null, 'total_teams' => '24', 'minimum_matches' => '6','position_type' => 'final','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.25.3',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0113.json'))), 'graphic_image' => null, 'total_teams' => '25', 'minimum_matches' => '3','position_type' => 'final','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.25.5',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0116.json'))), 'graphic_image' => null, 'total_teams' => '25', 'minimum_matches' => '5','position_type' => 'final','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.25.6',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0118.json'))), 'graphic_image' => null, 'total_teams' => '25', 'minimum_matches' => '6','position_type' => 'final_and_group_ranking','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.26.3',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0119.json'))), 'graphic_image' => null, 'total_teams' => '26', 'minimum_matches' => '3','position_type' => 'final','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.26.4',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0121.json'))), 'graphic_image' => null, 'total_teams' => '26', 'minimum_matches' => '4','position_type' => 'final','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.26.6',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0123.json'))), 'graphic_image' => null, 'total_teams' => '26', 'minimum_matches' => '6','position_type' => 'final','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.27.4 (v1)',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0125.json'))), 'graphic_image' => null, 'total_teams' => '27', 'minimum_matches' => '4','position_type' => 'group_ranking','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.27.4 (v2)',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0126.json'))), 'graphic_image' => null, 'total_teams' => '27', 'minimum_matches' => '4','position_type' => 'group_ranking','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

           [ 'name' => 'T.16.3',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0054.json'))), 'graphic_image' => null, 'total_teams' => '16', 'minimum_matches' => '3','position_type' => 'final','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

           [ 'name' => 'T.19.3',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0075.json'))), 'graphic_image' => null, 'total_teams' => '19', 'minimum_matches' => '3','position_type' => 'final','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.19.4',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0076.json'))), 'graphic_image' => null, 'total_teams' => '19', 'minimum_matches' => '4','position_type' => 'final','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

           [ 'name' => 'T.27.5',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0127.json'))), 'graphic_image' => null, 'total_teams' => '27', 'minimum_matches' => '5','position_type' => 'final','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.27.6',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0129.json'))), 'graphic_image' => null, 'total_teams' => '27', 'minimum_matches' => '6','position_type' => 'final','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.20.3',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0079.json'))), 'graphic_image' => null, 'total_teams' => '20', 'minimum_matches' => '3','position_type' => 'group_ranking','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.16.4 (v1)',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0055.json'))), 'graphic_image' => null, 'total_teams' => '16', 'minimum_matches' => '4','position_type' => 'final','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.4.4',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0139.json'))), 'graphic_image' => null, 'total_teams' => '4', 'minimum_matches' => '4','position_type' => 'final','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.4.5',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0140.json'))), 'graphic_image' => null, 'total_teams' => '4', 'minimum_matches' => '5','position_type' => 'final','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.4.6',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0141.json'))), 'graphic_image' => null, 'total_teams' => '4', 'minimum_matches' => '6','position_type' => 'group_ranking','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.5.4',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0142.json'))), 'graphic_image' => null, 'total_teams' => '5', 'minimum_matches' => '4','position_type' => 'final_and_group_ranking','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.5.5',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0143.json'))), 'graphic_image' => null, 'total_teams' => '5', 'minimum_matches' => '5','position_type' => 'final_and_group_ranking','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.5.6',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0144.json'))), 'graphic_image' => null, 'total_teams' => '5', 'minimum_matches' => '6','position_type' => 'final_and_group_ranking','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.12.6 (v2)',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0145.json'))), 'graphic_image' => null, 'total_teams' => '12', 'minimum_matches' => '6','position_type' => 'final','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.8.5 (v2)',
          'json_data' => jsonMin::minify(file_get_contents(base_path('resources/templates/template0146.json'))), 'graphic_image' => '/assets/img/template_graphic_image/TT146_v2.jpg', 'total_teams' => '8', 'minimum_matches' => '5', 'position_type' => 'final_and_group_ranking',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

        ]);
DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}