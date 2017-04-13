<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PitchesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        DB::table('pitches')->truncate();
        $tournament = DB::table('tournaments')->take(3)->select('id')->get()->toArray();
        $venue = DB::table('venues')->take(3)->select('id')->get()->toArray();
        DB::table('pitches')->insert([
        	['tournament_id' => array_rand($tournament), 'pitch_number' => 'Pitch-3', 'type' => 'grass', 
        	'size' => '5-a-side', 'venue_id' => array_rand($venue), 'time_slot' => '30', 'availabiblity' => '100', 'comment' => 'euro',
        	'pitch_capacity' => '240', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

        	['tournament_id' => array_rand($tournament), 'pitch_number' => 'Field E', 'type' => 'artificial', 
        	'size' => '5-a-side', 'venue_id' => array_rand($venue), 'time_slot' => '30', 'availabiblity' => '100', 'comment' => 'euro',
        	'pitch_capacity' => '90', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

        	['tournament_id' => array_rand($tournament), 'pitch_number' => 'Field F', 'type' => 'grass',
        	'size' => '5-a-side', 'venue_id' => array_rand($venue), 'time_slot' => '30', 'availabiblity' => '100', 'comment' => 'euro',
        	'pitch_capacity' => '1380', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')]
        ]);
    }
}
