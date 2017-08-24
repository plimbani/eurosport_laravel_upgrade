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
        	['tournament_id' => 1, 'name' => 'Sportpark Heimstetten', 'address1' => 'Am Sportpark 2',
        	'address2' => 'test', 'address3' => 'test',
            'state' => 'Bayern',
            'county' => 'Bhutan', 'city' => 'Heimstetten',
        	'country' => 'Germany', 'organiser' => '', 'postcode' => '85551',
            'contact_no' => '7418529630',
            'email_address' => 'test@aecordigital.com',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

        	['tournament_id' => 2, 'name' => 'RC Hades', 'address1' => 'Tulpinstraat 81',
        	 'address2' => 'test', 'address3' => 'test',
              'state' => 'Limburg', 'county' => 'Holy See (Vatican City State)',  'city' => 'Hasselt',
        	 'country' => 'Belgium', 'organiser' => '', 'postcode' => '3500', 'contact_no' => '7418529630', 'email_address' => 'test@aecordigital.com',
             'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

        	['tournament_id' => 3, 'name' => 'De Schorre',
             'address1' => 'Sportparklaan  1',
        	 'address2' => 'test', 'address3' => 'test',
             'state' => 'West-Vlaanderen',
             'county' => 'United States of America', 'city' => 'Oostende',
        	 'country' => 'Belgium', 'organiser' => '', 'postcode' => '8400', 'contact_no' => '0498623343', 'email_address' => 'test@aecordigital.com',
             'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

          ['tournament_id' => 4, 'name' => 'Top Ten',
             'address1' => 'Tordera',
           'address2' => 'test', 'address3' => 'test',
             'state' => 'West-Vlaanderen',
             'county' => 'Spain', 'city' => 'Top Ten',
           'country' => 'Spain', 'organiser' => '', 'postcode' => '8400',
           'contact_no' => '0498623343', 'email_address' => 'test@aecordigital.com',
             'created_at' => Carbon::now()->format('Y-m-d H:i:s')
          ],
          ['tournament_id' => 5, 'name' => 'As 80',
             'address1' => 'Klein Brandt Sportpark 7',
           'address2' => 'test', 'address3' => 'test',
             'state' => 'Almere',
             'county' => 'Spain', 'city' => 'Top Ten',
           'country' => 'Netherlands', 'organiser' => '', 'postcode' => '1312',
           'contact_no' => '0498623343', 'email_address' => 'test@aecordigital.com',
             'created_at' => Carbon::now()->format('Y-m-d H:i:s')]
          ]);
    }
}
