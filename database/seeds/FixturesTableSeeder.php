<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Carbon\Carbon;

class FixturesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $tournaments = App\Models\Tournament::all()->pluck('id')->toArray();
        $competitions = App\Models\Competition::all()->pluck('id')->toArray();
        $venues = App\Models\Venue::all()->pluck('id')->toArray();
        $pitches = App\Models\Pitch::all()->pluck('id')->toArray();
        $teams = App\Models\Team::all()->pluck('id')->toArray();
        $matchresults = App\Models\MatchResult::all()->pluck('id')->toArray();
        $referee = App\Models\Referee::all()->pluck('id')->toArray();

        DB::table('fixtures')->delete();
        foreach (range(1, 10) as $index) {
            DB::table('fixtures')->insert([
                'tournament_id' => $faker->randomElement($tournaments),
                'competition_id' => $faker->randomElement($competitions),
                'venue_id' => $faker->randomElement($venues),
                'pitch_id' => $faker->randomElement($pitches),
                'match_datetime' => $faker->dateTimeBetween($startDate = '-15 days', $endDate = 'now'),
                'match_number' => $faker->numberBetween(0, 10),
                'round' => $faker->word(),
                'home_team' => $faker->randomElement($teams),
                'away_team' => $faker->randomElement($teams),
                'hometeam_score' => $faker->randomFloat(2, 0, 10),
                'awayteam_score' => $faker->randomFloat(2, 0, 10),
                'hometeam_point' => $faker->randomFloat(2, 0, 10),
                'awayteam_point' => $faker->randomFloat(2, 0, 10),
                'match_result_id' => $faker->randomElement($matchresults),
                'bracket_json' => json_encode([]),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
