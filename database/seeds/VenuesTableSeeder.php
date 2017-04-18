<?php

use Illuminate\Database\Seeder;
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
        DB::table('venues')->truncate();
        $tournament = DB::table('tournaments')->take(3)->select('id')->get()->toArray();
       
        DB::table('venues')->insert([
        	['tournament_id' => array_rand($tournament), 'name' => 'Sportpark Heimstetten', 'address1' => 'Am Sportpark 2', 
        	'address2' => 'test', 'address3' => 'test', 'state' => 'Bayern', 'county' => 'Bhutan', 'city' => 'Heimstetten', 
        	'country' => 'Faroe Islands', 'postcode' => '85551', 'contact_no' => '7418529630', 'email_address' => 'test@aecordigital.com', 
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

        	['tournament_id' => array_rand($tournament), 'name' => 'RC Hades', 'address1' => 'Tulpinstraat 81',
        	 'address2' => 'test', 'address3' => 'test', 'state' => 'Limburg', 'county' => 'Holy See (Vatican City State)', 'city' => 'Hasselt', 
        	 'country' => 'Trinidad and Tobago', 'postcode' => '85551', 'contact_no' => '7418529630', 'email_address' => 'test@aecordigital.com', 
             'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

        	['tournament_id' => array_rand($tournament), 'name' => 'Atrest', 'address1' => 'test3',
        	 'address2' => 'test', 'address3' => 'test', 'state' => 'Gujarat', 'county' => 'United States of America', 'city' => 'Hmilton', 
        	 'country' => 'Austria', 'postcode' => '85551', 'contact_no' => '7418529630', 'email_address' => 'test@aecordigital.com', 
             'created_at' => Carbon::now()->format('Y-m-d H:i:s')]
        ]);
    }
}
