<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class AgeGroupTournamentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $agegroups = App\Models\AgeGroup::all()->pluck('id')->toArray();
        $tournaments = App\Models\Tournament::all()->pluck('id')->toArray();
        DB::table('age_group_tournament')->delete();
        foreach(range(1,10) as $index){
            DB::table('age_group_tournament')->insert([
                'age_group_id' => $faker->randomElement($agegroups),
                'tournament_id' => $faker->randomElement($tournaments)
            ]);
        }
    }
}
