<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Carbon\Carbon;

class VenuesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        DB::table('venues')->delete();
        foreach (range(1, 10) as $index) {
            DB::table('venues')->insert([
                'name' => $faker->word(),
                'address1' => $faker->address(),
                'address2' => $faker->address(),
                'address3' => $faker->address(),
                'state' => $faker->city(),
                'county' => $faker->city(),
                'city' => $faker->city(),
                'county' => $faker->country(),
                'country' => $faker->country(),
                'postcode' => $faker->postcode(),
                'contact_no' => $faker->phoneNumber(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
