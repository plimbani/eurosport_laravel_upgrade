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
        	[ 'name' => 'ALBANIA', 'country_code' => 'AL ', 'logo' => 'http://static.tournamentsoftware.com/images/flags/16/POL.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'AUSTRIA', 'country_code' => 'AT', 'logo' => 'http://static.tournamentsoftware.com/images/flags/16/POL.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'AUSTRALIA', 'country_code' => 'AU', 'logo' => 'http://static.tournamentsoftware.com/images/flags/16/POL.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'BOSNIA AND HERZEGOVINA', 'country_code' => 'BA', 'logo' => 'http://static.tournamentsoftware.com/images/flags/16/POL.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'BELGIUM', 'country_code' => 'BE', 'logo' => 'http://static.tournamentsoftware.com/images/flags/16/POL.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'BULGARIA', 'country_code' => 'BG', 'logo' => 'http://static.tournamentsoftware.com/images/flags/16/POL.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'BELARUS', 'country_code' => 'BY', 'logo' => 'http://static.tournamentsoftware.com/images/flags/16/POL.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],     
          [ 'name' => 'CANADA', 'country_code' => 'CA', 'logo' => 'http://static.tournamentsoftware.com/images/flags/16/POL.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'CHINA', 'country_code' => 'CN', 'logo' => 'http://static.tournamentsoftware.com/images/flags/16/POL.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'CROATIA', 'country_code' => 'HR', 'logo' => 'http://static.tournamentsoftware.com/images/flags/16/POL.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'CYPRUS', 'country_code' => 'CY', 'logo' => 'http://static.tournamentsoftware.com/images/flags/16/POL.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'CZECH REPUBLIC', 'country_code' => 'CZ', 'logo' => 'http://static.tournamentsoftware.com/images/flags/16/POL.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'DENMARK', 'country_code' => 'DK', 'logo' => 'http://static.tournamentsoftware.com/images/flags/16/POL.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'ENGLAND', 'country_code' => 'ENG', 'logo' => 'http://static.tournamentsoftware.com/images/flags/16/POL.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'ESTONIA', 'country_code' => 'EE', 'logo' => 'http://static.tournamentsoftware.com/images/flags/16/POL.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'FAROE ISLANDS', 'country_code' => 'FO', 'logo' => 'http://static.tournamentsoftware.com/images/flags/16/POL.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'FINLAND', 'country_code' => 'FI', 'logo' => 'http://static.tournamentsoftware.com/images/flags/16/POL.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],          
          [ 'name' => 'FRANCE', 'country_code' => 'FR', 'logo' => 'http://static.tournamentsoftware.com/images/flags/16/POL.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],          
          [ 'name' => 'GERMANY', 'country_code' => 'DE', 'logo' => 'http://static.tournamentsoftware.com/images/flags/16/POL.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'GIBRALTAR', 'country_code' => 'GI', 'logo' => 'http://static.tournamentsoftware.com/images/flags/16/POL.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'GREECE', 'country_code' => 'GR', 'logo' => 'http://static.tournamentsoftware.com/images/flags/16/POL.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'HUNGARY', 'country_code' => 'HU', 'logo' => 'http://static.tournamentsoftware.com/images/flags/16/POL.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'ICELAND', 'country_code' => 'IS', 'logo' => 'http://static.tournamentsoftware.com/images/flags/16/POL.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'IRELAND', 'country_code' => 'IE', 'logo' => 'http://static.tournamentsoftware.com/images/flags/16/POL.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'ISRAEL', 'country_code' => 'IL', 'logo' => 'http://static.tournamentsoftware.com/images/flags/16/POL.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'ITALY', 'country_code' => 'IT', 'logo' => 'http://static.tournamentsoftware.com/images/flags/16/POL.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'KOSOVO', 'country_code' => 'XK', 'logo' => 'http://static.tournamentsoftware.com/images/flags/16/POL.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],          
          [ 'name' => 'LATVIA', 'country_code' => 'LV', 'logo' => 'http://static.tournamentsoftware.com/images/flags/16/POL.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'LIECHTENSTEIN', 'country_code' => 'LI', 'logo' => 'http://static.tournamentsoftware.com/images/flags/16/POL.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'LITHUANIA', 'country_code' => 'LT', 'logo' => 'http://static.tournamentsoftware.com/images/flags/16/POL.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'LUXEMBOURG', 'country_code' => 'LU', 'logo' => 'http://static.tournamentsoftware.com/images/flags/16/POL.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'MACEDONIA', 'country_code' => 'MK', 'logo' => 'http://static.tournamentsoftware.com/images/flags/16/POL.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'MALTA', 'country_code' => 'MT', 'logo' => 'http://static.tournamentsoftware.com/images/flags/16/POL.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],          
          [ 'name' => 'MONACO', 'country_code' => 'MC', 'logo' => 'http://static.tournamentsoftware.com/images/flags/16/POL.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')], 
          [ 'name' => 'MONTENEGRO', 'country_code' => 'ME', 'logo' => 'http://static.tournamentsoftware.com/images/flags/16/POL.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],  
          [ 'name' => 'MOROCCO', 'country_code' => 'MA', 'logo' => 'http://static.tournamentsoftware.com/images/flags/16/POL.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],  
          [ 'name' => 'NETHERLANDS', 'country_code' => 'NL', 'logo' => 'http://static.tournamentsoftware.com/images/flags/16/POL.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],  
          [ 'name' => 'NEW ZEALAND', 'country_code' => 'NZ', 'logo' => 'http://static.tournamentsoftware.com/images/flags/16/POL.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],  
          [ 'name' => 'NORTHERN IRELAND', 'country_code' => 'GB', 'logo' => 'http://static.tournamentsoftware.com/images/flags/16/POL.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],   
          [ 'name' => 'NORWAY', 'country_code' => 'NO', 'logo' => 'http://static.tournamentsoftware.com/images/flags/16/POL.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],   
          [ 'name' => 'PERU', 'country_code' => 'PE', 'logo' => 'http://static.tournamentsoftware.com/images/flags/16/POL.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],    
          [ 'name' => 'POLAND', 'country_code' => 'PL', 'logo' => 'http://static.tournamentsoftware.com/images/flags/16/POL.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],    
          [ 'name' => 'PORTUGAL', 'country_code' => 'PT', 'logo' => 'http://static.tournamentsoftware.com/images/flags/16/POL.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],     
          [ 'name' => 'REPUBLIC OF MOLDOVA', 'country_code' => 'RO', 'logo' => 'http://static.tournamentsoftware.com/images/flags/16/POL.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],     
          [ 'name' => 'REPUBLIC OF SERBIA', 'country_code' => 'RO', 'logo' => 'http://static.tournamentsoftware.com/images/flags/16/POL.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],     
          [ 'name' => 'ROMANIA', 'country_code' => 'RO', 'logo' => 'http://static.tournamentsoftware.com/images/flags/16/POL.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],     
          [ 'name' => 'RUSSIAN FEDERATION', 'country_code' => 'RO', 'logo' => 'http://static.tournamentsoftware.com/images/flags/16/POL.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],     
          [ 'name' => 'SCOTLAND', 'country_code' => 'ST', 'logo' => 'http://static.tournamentsoftware.com/images/flags/16/POL.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],          
          [ 'name' => 'SLOVAKIA', 'country_code' => 'SK', 'logo' => 'http://static.tournamentsoftware.com/images/flags/16/POL.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],     
          [ 'name' => 'SLOVENIA', 'country_code' => 'SI', 'logo' => 'http://static.tournamentsoftware.com/images/flags/16/POL.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],     
          [ 'name' => 'SOUTH AFRICA', 'country_code' => 'ZA', 'logo' => 'http://static.tournamentsoftware.com/images/flags/16/POL.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],     
          [ 'name' => 'SPAIN', 'country_code' => 'ES', 'logo' => 'http://static.tournamentsoftware.com/images/flags/16/POL.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],     
          [ 'name' => 'SWEDEN', 'country_code' => 'SE', 'logo' => 'http://static.tournamentsoftware.com/images/flags/16/POL.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],     
          [ 'name' => 'SWITZERLAND', 'country_code' => 'CH', 'logo' => 'http://static.tournamentsoftware.com/images/flags/16/POL.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],     
          [ 'name' => 'TUNISIA', 'country_code' => 'TN', 'logo' => 'http://static.tournamentsoftware.com/images/flags/16/POL.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],     
          [ 'name' => 'TURKEY', 'country_code' => 'TR', 'logo' => 'http://static.tournamentsoftware.com/images/flags/16/POL.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],     
          [ 'name' => 'UKRAINE', 'country_code' => 'UA', 'logo' => 'http://static.tournamentsoftware.com/images/flags/16/POL.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],     
          [ 'name' => 'UNITED ARAB EMIRATES', 'country_code' => 'AE ', 'logo' => 'http://static.tournamentsoftware.com/images/flags/16/POL.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'UNITED STATES OF AMERICA', 'country_code' => 'US', 'logo' => 'http://static.tournamentsoftware.com/images/flags/16/POL.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],     
          [ 'name' => 'UZBEKISTAN', 'country_code' => 'UZ', 'logo' => 'http://static.tournamentsoftware.com/images/flags/16/POL.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],     
          [ 'name' => 'WALES', 'country_code' => 'WLS', 'logo' => 'http://static.tournamentsoftware.com/images/flags/16/POL.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],             
        ]);
    }
}
