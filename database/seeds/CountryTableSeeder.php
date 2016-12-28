<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CountryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('countries')->delete();
        $countries = [
            ['name' => 'Poland', 'code' => 'PL', 'logo' => 'http://static.tournamentsoftware.com/images/flags/16/UKR.png'],
            ['name' => 'Netherlands', 'code' => 'NL', 'logo' => 'http://static.tournamentsoftware.com/images/flags/16/FRA.png'],
        ];
        foreach ($countries as $country) {
            DB::table('countries')->insert([
                'name' => $country['name'],
                'country_code' => $country['code'],
                'logo' => $country['logo'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
