<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Carbon\Carbon;

class ClubsTableSeeder extends Seeder
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
        DB::table('clubs')->delete();
        foreach (range(1,10) as $index) {
            DB::table('clubs')->insert([
                'user_id' => $faker->randomElement($users),
                'name' => $faker->word(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        }
        
    }
}
