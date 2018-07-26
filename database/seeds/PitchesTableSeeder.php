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

          [
          'id'=>1,
          'tournament_id' => 4,
          'pitch_number' => 'Top Ten 8',
          'type' => 'grass',
          'size' => '5-a-side',
          'venue_id' => 4, 'time_slot' => '30',
          'availability' => '100',
          'comment' => 'euro',
          'pitch_capacity' => '1380',

          'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

          ['id'=>2,'tournament_id' => 4, 'pitch_number' => 'Top Ten 9', 'type' => 'grass',
          'size' => '5-a-side', 'venue_id' =>4, 'time_slot' => '30', 'availability' => '100', 'comment' => 'euro',
          'pitch_capacity' => '1380', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

          ['id'=>3,'tournament_id' => 4,
          'pitch_number' => 'Top Ten 10',
          'type' => 'grass',
          'size' => '5-a-side',
          'venue_id' => 4, 'time_slot' => '30', 'availability' => '100', 'comment' => 'euro',
          'pitch_capacity' => '1380', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

          ['id'=>4,'tournament_id' => 4, 'pitch_number' => 'Top Ten 11', 'type' => 'grass',
          'size' => '5-a-side', 'venue_id' => 4, 'time_slot' => '30', 'availability' => '100', 'comment' => 'euro',
          'pitch_capacity' => '1380', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

          ['id'=>5,'tournament_id' => 5,
          'pitch_number' => 'A1',
          'type' => 'grass',
          'size' => '5-a-side',
          'venue_id' => 5, 'time_slot' => '30', 'availability' => '100', 'comment' => 'euro',
          'pitch_capacity' => '1380', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

          ['id'=>6,'tournament_id' => 5, 'pitch_number' => 'A2', 'type' => 'grass',
          'size' => '5-a-side', 'venue_id' => 5, 'time_slot' => '30', 'availability' => '100', 'comment' => 'euro',
          'pitch_capacity' => '1380', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
        ]);
    }
}
