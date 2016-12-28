<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
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
        $faker = Faker::create();
        $location = App\Models\Venue::All()->pluck('id')->toArray();
        $pitchName = ['AWF - Field 4', 'AWF - Field 1', 'AWF - Field 2'];
        DB::table('pitches')->delete();
        foreach (range(1, 10) as $index) {
            DB::table('pitches')->insert([
                'name' => $faker->randomElement($pitchName),
                'pitch_number' => $faker->numberBetween(100000, 999999),
                'type' => $faker->randomElement(['grass', 'artificial']),
                'venue_id' => $faker->randomElement($location),
                'availability' => '100',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
