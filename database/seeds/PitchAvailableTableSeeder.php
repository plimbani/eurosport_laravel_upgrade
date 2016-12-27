<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PitchAvailableTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $faker = Faker::create();
        //$users = App\Models\User::all()->pluck('id')->toArray();
        //$competitionType = ['Group Games', 'Single Elimination', 'Double Elimination'];
        DB::table('pitch_availibility')->delete();
        //foreach (range(1, 10) as $index) {

            DB::table('pitch_availibility')->insert([
                'tournament_id' => '1',
                'pitch_id' => '1',
                'start_time' => Carbon::now()->format('Y-m-d 9:00:00'),
                'end_time' => Carbon::now()->format('Y-m-d 9:30:00'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

        DB::table('pitch_availibility')->insert([
                'tournament_id' => '1',
                'pitch_id' => '1',
                'start_time' => Carbon::now()->format('Y-m-d 9:30:00'),
                'end_time' => Carbon::now()->format('Y-m-d 10:00:00'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
       // }
    }
}
