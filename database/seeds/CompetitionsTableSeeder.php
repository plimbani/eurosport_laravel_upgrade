<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Carbon\Carbon;

class CompetitionsTableSeeder extends Seeder
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
        $groupName = ['Group A', 'Group B', 'Group C', 'Group D', 'Challenge Cup', 'Wrocław Trophy'];
        DB::table('competitions')->delete();
        foreach (range(1, 5) as $index) {
            DB::table('competitions')->insert([
                'age_group_id' => $faker->randomElement($agegroups),
                'tournament_id' => $faker->randomElement($tournaments),
                'name' => $faker->randomElement($groupName),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
