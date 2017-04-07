<?php

use Illuminate\Database\Seeder;

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
        	[ 'name' => 'Kamal', 'json_data' => ''],
        	[ 'name' => 'Krunal', 'json_data' => ''],
        	[ 'name' => 'Rishabh', 'json_data' => ''],
        ]);
    }
}
