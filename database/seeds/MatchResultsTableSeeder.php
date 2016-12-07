<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Faker\Factory as Faker;

class MatchResultsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        DB::table('match_results')->delete();
        $location = App\Models\Venue::All()->pluck('id')->toArray();
        $matchName = ['Awarded Away Win', 'Awarded Draw', 'Awarded Home Win', 'Home Win on Penalties', 'Away Win on Penalties', 'Postponed', 'Abandoned', 'Void', 'Temporary'];
        $matchStatus = ['Walk over', 'abandoned', 'full-time', 'penalties'];
        $referee = App\Models\Referee::all()->pluck('id')->toArray();

        foreach (range(1, 10) as $index) {
            DB::table('match_results')->insert([
                'goal_score1' => $faker->numberBetween(1, 10),
                'goal_score2' => $faker->numberBetween(1, 10),
                'match_status' => $faker->randomElement($matchStatus),
                'winner' => $faker->name,
                'location_id' => $faker->randomElement($location),
                'referee_id' => $faker->randomElement($referee),
                'notes' => $faker->text,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
