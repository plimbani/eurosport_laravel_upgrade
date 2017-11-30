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
          'image' => 'template_images/template001.jpg',
          'total_teams' => '6', 'minimum_matches' => '3',
          'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],
          [ 'name' => 'T.6.4',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template002.json'))), 'image' => 'template_images/template002.jpg', 'total_teams' => '6', 'minimum_matches' => '4',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],
          [ 'name' => 'T.6.5',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template003.json'))), 'image' => 'template_images/template003.jpg', 'total_teams' => '6', 'minimum_matches' => '5',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.6.6',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template004.json'))), 'image' => 'template_images/template004.jpg', 'total_teams' => '6', 'minimum_matches' => '6',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],
          [ 'name' => 'T.7.3',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template005.json'))), 'image' => 'template_images/template005.jpg', 'total_teams' => '7', 'minimum_matches' => '3',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],
          [ 'name' => 'T.7.4',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template006.json'))), 'image' => 'template_images/template006.jpg', 'total_teams' => '7', 'minimum_matches' => '4',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],
          [ 'name' => 'T.7.5',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template007.json'))), 'image' => 'template_images/template007.jpg', 'total_teams' => '7', 'minimum_matches' => '5',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],
          [ 'name' => 'T.7.6',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template008.json'))), 'image' => 'template_images/template008.jpg', 'total_teams' => '7', 'minimum_matches' => '6',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],
          [ 'name' => 'T.8.3 (v1)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template009.json'))), 'image' => 'template_images/template009.jpg', 'total_teams' => '8', 'minimum_matches' => '3',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.8.3 (v2)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0010.json'))), 'image' => 'template_images/template0010.jpg', 'total_teams' => '8', 'minimum_matches' => '3',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],
            [ 'name' => 'T.8.4',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0011.json'))), 'image' => 'template_images/template0011.jpg', 'total_teams' => '8', 'minimum_matches' => '4',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.8.5',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0013.json'))), 'image' => 'template_images/template0013.jpg', 'total_teams' => '8', 'minimum_matches' => '5',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],
            [ 'name' => 'T.8.6',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0014.json'))), 'image' => 'template_images/template0014.jpg', 'total_teams' => '8', 'minimum_matches' => '6',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],
          [ 'name' => 'T.9.3',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0015.json'))), 'image' => 'template_images/template0015.jpg', 'total_teams' => '9', 'minimum_matches' => '3',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],
          [ 'name' => 'T.9.4 (v1)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0016.json'))), 'image' => 'template_images/template0016.jpg', 'total_teams' => '9', 'minimum_matches' => '4',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],
          [ 'name' => 'T.9.4 (v2)',
           'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0017.json'))), 'image' => 'template_images/template0017.jpg', 'total_teams' => '9', 'minimum_matches' => '4',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],
          [ 'name' => 'T.9.5',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0018.json'))), 'image' => 'template_images/template0018.jpg', 'total_teams' => '9', 'minimum_matches' => '5',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],
          [ 'name' => 'T.9.6',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0019.json'))), 'image' => 'template_images/template0019.jpg', 'total_teams' => '9', 'minimum_matches' => '6',

            'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],
          [ 'name' => 'T.9.7',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0020.json'))), 'image' => 'template_images/template0020.jpg', 'total_teams' => '9', 'minimum_matches' => '7',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],


           [ 'name' => 'T.10.4 (v1)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0022.json'))), 'image' => 'template_images/template0022.jpg', 'total_teams' => '10', 'minimum_matches' => '4',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'T.10.4 (v2)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0023.json'))), 'image' => 'template_images/template0023.jpg', 'total_teams' => '10', 'minimum_matches' => '4',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],
          [ 'name' => 'T.10.4 (v3)',
           'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0024.json'))), 'image' => 'template_images/template0024.jpg', 'total_teams' => '10', 'minimum_matches' => '4',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],
          [ 'name' => 'T.10.5',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0025.json'))), 'image' => 'template_images/template0025.jpg', 'total_teams' => '10', 'minimum_matches' => '5',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.20.4',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0081.json'))), 'image' => 'template_images/template0081.jpg', 'total_teams' => '20', 'minimum_matches' => '4','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.20.5',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0082.json'))), 'image' => 'template_images/template0082.jpg', 'total_teams' => '20', 'minimum_matches' => '5','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.20.6 (v1)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0083.json'))), 'image' => 'template_images/template0083.jpg', 'total_teams' => '20', 'minimum_matches' => '6','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.20.6 (v2)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0084.json'))), 'image' => 'template_images/template0084.jpg', 'total_teams' => '20', 'minimum_matches' => '6','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.20.6 (v3)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0085.json'))), 'image' => 'template_images/template0085.jpg', 'total_teams' => '20', 'minimum_matches' => '6','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.15.3',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0049.json'))), 'image' => 'template_images/template0049.jpg', 'total_teams' => '15', 'minimum_matches' => '3','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.15.4',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0050.json'))), 'image' => 'template_images/template0050.jpg', 'total_teams' => '15', 'minimum_matches' => '4','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.15.5 (v1)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0051.json'))), 'image' => 'template_images/template0051.jpg', 'total_teams' => '15', 'minimum_matches' => '5','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.15.5 (v2)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0052.json'))), 'image' => 'template_images/template0052.jpg', 'total_teams' => '15', 'minimum_matches' => '5','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.15.6',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0053.json'))), 'image' => 'template_images/template0053.jpg', 'total_teams' => '15', 'minimum_matches' => '6','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.18.3',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0066.json'))), 'image' => 'template_images/template0066.jpg', 'total_teams' => '18', 'minimum_matches' => '3','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.18.4',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0068.json'))), 'image' => 'template_images/template0068.jpg', 'total_teams' => '18', 'minimum_matches' => '4','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.18.6 (v1)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0072.json'))), 'image' => 'template_images/template0072.jpg', 'total_teams' => '18', 'minimum_matches' => '6','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.18.5',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0070.json'))), 'image' => 'template_images/template0070.jpg', 'total_teams' => '18', 'minimum_matches' => '5','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.18.6 (v2)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0073.json'))), 'image' => 'template_images/template0073.jpg', 'total_teams' => '18', 'minimum_matches' => '6','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.10.6',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0026.json'))), 'image' => 'template_images/template0026.jpg', 'total_teams' => '10', 'minimum_matches' => '6','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.11.4 (v1)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0027.json'))), 'image' => 'template_images/template0027.jpg', 'total_teams' => '11', 'minimum_matches' => '4','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.11.4 (v2)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0028.json'))), 'image' => 'template_images/template0028.jpg', 'total_teams' => '11', 'minimum_matches' => '4','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.21.3',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0086.json'))), 'image' => 'template_images/template0086.jpg', 'total_teams' => '21', 'minimum_matches' => '3','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.21.4',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0088.json'))), 'image' => 'template_images/template0088.jpg', 'total_teams' => '21', 'minimum_matches' => '4','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.21.5',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0089.json'))), 'image' => 'template_images/template0089.jpg', 'total_teams' => '21', 'minimum_matches' => '5','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],


          [ 'name' => 'T.11.5',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0031.json'))), 'image' => 'template_images/template0031.jpg', 'total_teams' => '11', 'minimum_matches' => '5','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.11.6',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0032.json'))), 'image' => 'template_images/template0032.jpg', 'total_teams' => '11', 'minimum_matches' => '6','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.12.3',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0033.json'))), 'image' => 'template_images/template0033.jpg', 'total_teams' => '12', 'minimum_matches' => '3','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.12.4',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0034.json'))), 'image' => 'template_images/template0034.jpg', 'total_teams' => '12', 'minimum_matches' => '4','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.12.5 (v1)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0035.json'))), 'image' => 'template_images/template0035.jpg', 'total_teams' => '12', 'minimum_matches' => '5','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.12.5 (v2)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0036.json'))), 'image' => 'template_images/template0036.jpg', 'total_teams' => '12', 'minimum_matches' => '5','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.12.6',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0037.json'))), 'image' => 'template_images/template0037.jpg', 'total_teams' => '12', 'minimum_matches' => '6','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.13.3',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0038.json'))), 'image' => 'template_images/template0038.jpg', 'total_teams' => '13', 'minimum_matches' => '3','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.13.4',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0039.json'))), 'image' => 'template_images/template0039.jpg', 'total_teams' => '13', 'minimum_matches' => '4','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.13.5',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0041.json'))), 'image' => 'template_images/template0041.jpg', 'total_teams' => '13', 'minimum_matches' => '5','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.13.6',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0042.json'))), 'image' => 'template_images/template0042.jpg', 'total_teams' => '13', 'minimum_matches' => '6','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.14.3',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0043.json'))), 'image' => 'template_images/template0043.jpg', 'total_teams' => '14', 'minimum_matches' => '3','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.14.4',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0044.json'))), 'image' => 'template_images/template0044.jpg', 'total_teams' => '14', 'minimum_matches' => '4','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.14.5 (v1)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0045.json'))), 'image' => 'template_images/template0045.jpg', 'total_teams' => '14', 'minimum_matches' => '5','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.19.5',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0077.json'))), 'image' => 'template_images/template0077.jpg', 'total_teams' => '19', 'minimum_matches' => '5','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.19.6',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0078.json'))), 'image' => 'template_images/template0078.jpg', 'total_teams' => '19', 'minimum_matches' => '6','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.21.6',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0092.json'))), 'image' => 'template_images/template0092.jpg', 'total_teams' => '21', 'minimum_matches' => '6','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.22.3',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0093.json'))), 'image' => 'template_images/template0093.jpg', 'total_teams' => '22', 'minimum_matches' => '3','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.22.4 (v1)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0095.json'))), 'image' => 'template_images/template0095.jpg', 'total_teams' => '22', 'minimum_matches' => '4','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.22.4 (v2)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0097.json'))), 'image' => 'template_images/template0097.jpg', 'total_teams' => '22', 'minimum_matches' => '4','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.22.5 (v1)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0098.json'))), 'image' => 'template_images/template0098.jpg', 'total_teams' => '22', 'minimum_matches' => '5','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.22.5 (v2)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0099.json'))), 'image' => 'template_images/template0099.jpg', 'total_teams' => '22', 'minimum_matches' => '5','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.22.6',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0100.json'))), 'image' => 'template_images/template0100.jpg', 'total_teams' => '22', 'minimum_matches' => '6','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.23.3',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0101.json'))), 'image' => 'template_images/template0101.jpg', 'total_teams' => '23', 'minimum_matches' => '3','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.23.6',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0106.json'))), 'image' => 'template_images/template0106.jpg', 'total_teams' => '23', 'minimum_matches' => '6','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.24.3',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0108.json'))), 'image' => 'template_images/template0108.jpg', 'total_teams' => '24', 'minimum_matches' => '3','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],


          [ 'name' => 'T.14.6',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0048.json'))), 'image' => 'template_images/template0048.jpg', 'total_teams' => '14', 'minimum_matches' => '6','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

           [ 'name' => 'T.14.5 (v2)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0046.json'))), 'image' => 'template_images/template0046.jpg', 'total_teams' => '14', 'minimum_matches' => '5','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.16.4 (v2)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0056.json'))), 'image' => 'template_images/template0056.jpg', 'total_teams' => '16', 'minimum_matches' => '4','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.16.5 (v1)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0057.json'))), 'image' => 'template_images/template0057.jpg', 'total_teams' => '16', 'minimum_matches' => '5','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.16.5 (v2)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0058.json'))), 'image' => 'template_images/template0058.jpg', 'total_teams' => '16', 'minimum_matches' => '5','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          ['name' => 'T.16.6',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0059.json'))), 'image' => 'template_images/template0059.jpg', 'total_teams' => '16', 'minimum_matches' => '6','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.17.3',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0060.json'))), 'image' => 'template_images/template0060.jpg', 'total_teams' => '17', 'minimum_matches' => '3','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          ['name' => 'T.17.5',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0062.json'))), 'image' => 'template_images/template0062.jpg', 'total_teams' => '17', 'minimum_matches' => '5','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          ['name' => 'T.17.6',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0064.json'))), 'image' => 'template_images/template0064.jpg', 'total_teams' => '17', 'minimum_matches' => '6','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.23.4 (v1)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0102.json'))), 'image' => 'template_images/template0102.jpg', 'total_teams' => '23', 'minimum_matches' => '4','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.23.4 (v2)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0103.json'))), 'image' => 'template_images/template0103.jpg', 'total_teams' => '23', 'minimum_matches' => '4','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.23.5 (v1)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0104.json'))), 'image' => 'template_images/template0104.jpg', 'total_teams' => '23', 'minimum_matches' => '5','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

           [ 'name' => 'T.23.5 (v2)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0105.json'))), 'image' => 'template_images/template0105.jpg', 'total_teams' => '23', 'minimum_matches' => '5','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.24.4',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0109.json'))), 'image' => 'template_images/template0109.jpg', 'total_teams' => '24', 'minimum_matches' => '4','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],


          [ 'name' => 'T.25.4',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0115.json'))), 'image' => 'template_images/template0115.jpg', 'total_teams' => '25', 'minimum_matches' => '4','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.26.5',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0122.json'))), 'image' => 'template_images/template0122.jpg', 'total_teams' => '26', 'minimum_matches' => '5','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.28.5 (v1)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0134.json'))), 'image' => 'template_images/template0134.jpg', 'total_teams' => '28', 'minimum_matches' => '5','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.28.5 (v2)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0135.json'))), 'image' => 'template_images/template0135.jpg', 'total_teams' => '28', 'minimum_matches' => '5','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

           [ 'name' => 'T.28.6 (v1)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0136.json'))), 'image' => 'template_images/template0136.jpg', 'total_teams' => '28', 'minimum_matches' => '6','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

           [ 'name' => 'T.28.6 (v2)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0137.json'))), 'image' => 'template_images/template0137.jpg', 'total_teams' => '28', 'minimum_matches' => '6','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

           [ 'name' => 'T.28.6 (v3)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0138.json'))), 'image' => 'template_images/template0138.jpg', 'total_teams' => '28', 'minimum_matches' => '6','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

            [ 'name' => 'T.28.4',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0133.json'))), 'image' => 'template_images/template0133.jpg', 'total_teams' => '28', 'minimum_matches' => '4','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.28.3',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0132.json'))), 'image' => 'template_images/template0132.jpg', 'total_teams' => '28', 'minimum_matches' => '3','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.24.5 (v1)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0110.json'))), 'image' => 'template_images/template0110.jpg', 'total_teams' => '24', 'minimum_matches' => '5','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.24.5 (v2)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0111.json'))), 'image' => 'template_images/template0111.jpg', 'total_teams' => '24', 'minimum_matches' => '5','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.24.6',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0112.json'))), 'image' => 'template_images/template0112.jpg', 'total_teams' => '24', 'minimum_matches' => '6','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.25.3',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0113.json'))), 'image' => 'template_images/template0113.jpg', 'total_teams' => '25', 'minimum_matches' => '3','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.25.5',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0116.json'))), 'image' => 'template_images/template0116.jpg', 'total_teams' => '25', 'minimum_matches' => '5','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.25.6',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0118.json'))), 'image' => 'template_images/template0118.jpg', 'total_teams' => '25', 'minimum_matches' => '6','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.26.3',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0119.json'))), 'image' => 'template_images/template0119.jpg', 'total_teams' => '26', 'minimum_matches' => '3','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.26.4',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0121.json'))), 'image' => 'template_images/template0121.jpg', 'total_teams' => '26', 'minimum_matches' => '4','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.26.6',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0123.json'))), 'image' => 'template_images/template0123.jpg', 'total_teams' => '26', 'minimum_matches' => '6','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.27.4 (v1)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0125.json'))), 'image' => 'template_images/template0125.jpg', 'total_teams' => '27', 'minimum_matches' => '4','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.27.4 (v2)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0126.json'))), 'image' => 'template_images/template0126.jpg', 'total_teams' => '27', 'minimum_matches' => '4','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

           [ 'name' => 'T.16.3',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0054.json'))), 'image' => 'template_images/template0054.jpg', 'total_teams' => '16', 'minimum_matches' => '3','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

           [ 'name' => 'T.19.3',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0075.json'))), 'image' => 'template_images/template0075.jpg', 'total_teams' => '19', 'minimum_matches' => '3','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.19.4',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0076.json'))), 'image' => 'template_images/template0076.jpg', 'total_teams' => '19', 'minimum_matches' => '4','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

           [ 'name' => 'T.27.5',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0127.json'))), 'image' => 'template_images/template0127.jpg', 'total_teams' => '27', 'minimum_matches' => '5','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.27.6',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0129.json'))), 'image' => 'template_images/template0129.jpg', 'total_teams' => '27', 'minimum_matches' => '6','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.20.3',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0079.json'))), 'image' => 'template_images/template0079.jpg', 'total_teams' => '20', 'minimum_matches' => '3','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],

          [ 'name' => 'T.16.4 (v1)',
          'json_data' => jsonMin::minify(file_get_contents(public_path('templates/template0055.json'))), 'image' => 'template_images/template0055.jpg', 'total_teams' => '16', 'minimum_matches' => '4','created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'deleted_at' => null],


        ]);
DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}