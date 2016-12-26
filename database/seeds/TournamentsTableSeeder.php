<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Carbon\Carbon;

class TournamentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $users = App\Models\User::all()->pluck('id')->toArray();
        $competitionType = ['Group Games', 'Single Elimination', 'Double Elimination'];

        $tournamentArray = [
            ['name' => 'U14 Fc Schadewijk', 'website' => 'http://toposscup.nl/', 'facebook' => 'fb',
                'twitter' => 'tw', 'logo' => '',
                ],
            ];
        DB::table('tournaments')->delete();
        foreach ($tournamentArray as $tournament) {
            DB::table('tournaments')->insert([
                'name' => $tournament['name'],
                'website' => $tournament['website'],
                'facebook' => $tournament['facebook'],
                'twitter' => $tournament['twitter'],
                'logo' => $tournament['logo'],

                'competition_type' => $faker->randomElement($competitionType),
                'user_id' => $faker->randomElement($users),
                'start_date' => $faker->dateTimeBetween($startDate = '-15 days', $endDate = 'now'),
                'end_date' => $faker->dateTimeBetween($startDate = '-15 days', $endDate = 'now'),
                'no_of_pitches' => $faker->numberBetween(0, 10),
                'no_of_match_per_day_pitch' => $faker->numberBetween(0, 10),
                'points_per_match_win' => $faker->randomFloat(2, 0, 10),
                'points_per_match_tie' => $faker->randomFloat(2, 0, 10),
                'points_per_bye' => $faker->randomFloat(2, 0, 10),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
