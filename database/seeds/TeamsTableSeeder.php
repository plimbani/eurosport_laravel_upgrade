<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Carbon\Carbon;

class TeamsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $clubs = App\Models\Club::all()->pluck('id')->toArray();
        $users = App\Models\User::all()->pluck('id')->toArray();
        $agegroups = App\Models\AgeGroup::all()->pluck('id')->toArray();
        DB::table('teams')->delete();
        foreach (range(1, 10) as $index) {
            DB::table('teams')->insert([
                'club_id' => $faker->randomElement($clubs),
                'user_id' => $faker->randomElement($users),
                'age_group_id' => $faker->randomElement($agegroups),
                'name' => $faker->word(),
                'website' => $faker->word(),
                'facebook' => $faker->word(),
                'twitter' => $faker->word(),
                'shirt_colour' => $faker->hexcolor,
                'esr_reference' => $faker->numberBetween(100000, 999999),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
