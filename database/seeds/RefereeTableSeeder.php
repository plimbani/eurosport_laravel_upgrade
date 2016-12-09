<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Faker\Factory as Faker;

class RefereeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        DB::table('referee')->delete();
        $users = App\Models\User::All()->pluck('id')->toArray();
        $ageGroups = App\Models\AgeGroup::All()->pluck('id')->toArray();

        foreach (range(1, 10) as $index) {
            DB::table('referee')->insert([
                'user_id' => $faker->randomElement($users),
                'availability' => '100',
                'comments' => $faker->text,
                'age_group_id' => $faker->randomElement($ageGroups),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),

            ]);
        }
    }
}
