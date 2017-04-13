<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CountriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        DB::table('countries')->truncate();   
        DB::table('countries')->insert([
        	[ 'name' => 'Poland', 'country_code' => 'PL', 'logo' => 'http://static.tournamentsoftware.com/images/flags/16/POL.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
        	[ 'name' => 'Netherlands', 'country_code' => 'NL', 'logo' => 'http://static.tournamentsoftware.com/images/flags/16/FRA.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
        	[ 'name' => 'Germany', 'country_code' => 'GE', 'logo' => 'https://static.tournamentsoftware.com/images/flags/16/GER.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
        ]);
    }
}
