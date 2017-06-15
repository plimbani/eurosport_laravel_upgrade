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
        	[ 'name' => 'ALBANIA', 'country_code' => 'AL ', 'logo' => 'images/al.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'AUSTRIA', 'country_code' => 'AT', 'logo' => 'images/austr.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'AUSTRALIA', 'country_code' => 'AU', 'logo' => 'images/aust.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'BOSNIA AND HERZEGOVINA', 'country_code' => 'BA', 'logo' => 'images/bosnia.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'BELGIUM', 'country_code' => 'BE', 'logo' => 'images/belgium.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'BULGARIA', 'country_code' => 'BG', 'logo' => 'images/bulgaria.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'BELARUS', 'country_code' => 'BY', 'logo' => 'images/belarus.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],     
          [ 'name' => 'CANADA', 'country_code' => 'CA', 'logo' => 'images/canada.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'CHINA', 'country_code' => 'CN', 'logo' => 'images/china.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'CROATIA', 'country_code' => 'HR', 'logo' => 'images/croatia.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'CYPRUS', 'country_code' => 'CY', 'logo' => 'images/cyprus.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'CZECH REPUBLIC', 'country_code' => 'CZ', 'logo' => 'images/czech.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'DENMARK', 'country_code' => 'DK', 'logo' => 'images/denmark.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'ENGLAND', 'country_code' => 'ENG', 'logo' => 'images/england.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'ESTONIA', 'country_code' => 'EE', 'logo' => 'images/estonia.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'FAROE ISLANDS', 'country_code' => 'FO', 'logo' => 'images/faroe.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'FINLAND', 'country_code' => 'FI', 'logo' => 'images/finland.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],          
          [ 'name' => 'FRANCE', 'country_code' => 'FR', 'logo' => 'images/france.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],          
          [ 'name' => 'GERMANY', 'country_code' => 'DE', 'logo' => 'images/germany.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'GIBRALTAR', 'country_code' => 'GI', 'logo' => 'images/gibraltar.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'GREECE', 'country_code' => 'GR', 'logo' => 'images/greece.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'HUNGARY', 'country_code' => 'HU', 'logo' => 'images/hungary.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'ICELAND', 'country_code' => 'IS', 'logo' => 'images/iceland.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'IRELAND', 'country_code' => 'IE', 'logo' => 'images/ireland.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'ISRAEL', 'country_code' => 'IL', 'logo' => 'images/israel.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'ITALY', 'country_code' => 'IT', 'logo' => 'images/italy.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'KOSOVO', 'country_code' => 'XK', 'logo' => 'images/kosovo.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],          
          [ 'name' => 'LATVIA', 'country_code' => 'LV', 'logo' => 'images/latvia.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'LIECHTENSTEIN', 'country_code' => 'LI', 'logo' => 'images/liechtenstein.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'LITHUANIA', 'country_code' => 'LT', 'logo' => 'images/lithuania.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'LUXEMBOURG', 'country_code' => 'LU', 'logo' => 'images/luxembourg.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'MACEDONIA', 'country_code' => 'MK', 'logo' => 'images/macedonia.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'MALTA', 'country_code' => 'MT', 'logo' => 'images/malta.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],          
          [ 'name' => 'MONACO', 'country_code' => 'MC', 'logo' => 'images/monaco.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')], 
          [ 'name' => 'MONTENEGRO', 'country_code' => 'ME', 'logo' => 'images/montenegro.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],  
          [ 'name' => 'MOROCCO', 'country_code' => 'MA', 'logo' => 'images/morocco.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],  
          [ 'name' => 'NETHERLANDS', 'country_code' => 'NL', 'logo' => 'images/netherlands.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],  
          [ 'name' => 'NEW ZEALAND', 'country_code' => 'NZ', 'logo' => 'images/new_zealand.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],  
          [ 'name' => 'NORTHERN IRELAND', 'country_code' => 'GB', 'logo' => 'images/northern_ireland.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],   
          [ 'name' => 'NORWAY', 'country_code' => 'NO', 'logo' => 'images/norway.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],   
          [ 'name' => 'PERU', 'country_code' => 'PE', 'logo' => 'images/peru.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],    
          [ 'name' => 'POLAND', 'country_code' => 'PL', 'logo' => 'images/poland.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],    
          [ 'name' => 'PORTUGAL', 'country_code' => 'PT', 'logo' => 'images/portugal.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],     
          [ 'name' => 'REPUBLIC OF MOLDOVA', 'country_code' => 'RO', 'logo' => 'images/moldova.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],     
          [ 'name' => 'REPUBLIC OF SERBIA', 'country_code' => 'RO', 'logo' => 'images/serbia.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],     
          [ 'name' => 'ROMANIA', 'country_code' => 'RO', 'logo' => 'images/romania.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],     
          [ 'name' => 'RUSSIAN FEDERATION', 'country_code' => 'RO', 'logo' => 'images/russian.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],     
          [ 'name' => 'SCOTLAND', 'country_code' => 'ST', 'logo' => 'images/scotland.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],          
          [ 'name' => 'SLOVAKIA', 'country_code' => 'SK', 'logo' => 'images/slovakia.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],     
          [ 'name' => 'SLOVENIA', 'country_code' => 'SI', 'logo' => 'images/slovenia.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],     
          [ 'name' => 'SOUTH AFRICA', 'country_code' => 'ZA', 'logo' => 'images/sf.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],     
          [ 'name' => 'SPAIN', 'country_code' => 'ES', 'logo' => 'images/spain.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],     
          [ 'name' => 'SWEDEN', 'country_code' => 'SE', 'logo' => 'images/sweden.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],     
          [ 'name' => 'SWITZERLAND', 'country_code' => 'CH', 'logo' => 'images/switzerland.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],     
          [ 'name' => 'TUNISIA', 'country_code' => 'TN', 'logo' => 'images/tunisia.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],     
          [ 'name' => 'TURKEY', 'country_code' => 'TR', 'logo' => 'images/turkey.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],     
          [ 'name' => 'UKRAINE', 'country_code' => 'UA', 'logo' => 'images/ukraine.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],     
          [ 'name' => 'UNITED ARAB EMIRATES', 'country_code' => 'AE ', 'logo' => 'images/united_arab_emirates.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'UNITED STATES OF AMERICA', 'country_code' => 'US', 'logo' => 'images/usa.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],     
          [ 'name' => 'UZBEKISTAN', 'country_code' => 'UZ', 'logo' => 'images/uzbekistan.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],     
          [ 'name' => 'WALES', 'country_code' => 'WLS', 'logo' => 'images/wales.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],             
        ]);
    }
}
