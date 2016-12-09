<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class TeamCompetitionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $teams = App\Models\Team::all()->pluck('id')->toArray();
        $competitions = App\Models\Competition::all()->pluck('id')->toArray();
        DB::table('team_competition')->delete();
        foreach (range(1, 10) as $index) {
            DB::table('team_competition')->insert([
                'team_id' => $faker->randomElement($teams),
                'competition_id' => $faker->randomElement($competitions),
            ]);
        }
    }
}
