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
          [ 'name' => 'ALBANIA', 'country_code' => 'AL ', 'logo' => '/assets/images/flags/flags/al.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'AUSTRIA', 'country_code' => 'AT', 'logo' => '/assets/images/flags/austr.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'AUSTRALIA', 'country_code' => 'AU', 'logo' => '/assets/images/flags/aust.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'BOSNIA AND HERZEGOVINA', 'country_code' => 'BA', 'logo' => '/assets/images/flags/bosnia.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'BELGIUM', 'country_code' => 'BE', 'logo' => '/assets/images/flags/belgium.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'BULGARIA', 'country_code' => 'BG', 'logo' => '/assets/images/flags/bulgaria.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'BELARUS', 'country_code' => 'BY', 'logo' => '/assets/images/flags/belarus.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'CANADA', 'country_code' => 'CA', 'logo' => '/assets/images/flags/canada.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'CHINA', 'country_code' => 'CN', 'logo' => '/assets/images/flags/china.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'CROATIA', 'country_code' => 'HR', 'logo' => '/assets/images/flags/croatia.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'CYPRUS', 'country_code' => 'CY', 'logo' => '/assets/images/flags/cyprus.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'CZECH REPUBLIC', 'country_code' => 'CZ', 'logo' => '/assets/images/flags/czech.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'DENMARK', 'country_code' => 'DK', 'logo' => '/assets/images/flags/denmark.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'ENGLAND', 'country_code' => 'ENG', 'logo' => '/assets/images/flags/england.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'ESTONIA', 'country_code' => 'EE', 'logo' => '/assets/images/flags/estonia.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'FAROE ISLANDS', 'country_code' => 'FO', 'logo' => '/assets/images/flags/faroe.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'FINLAND', 'country_code' => 'FI', 'logo' => '/assets/images/flags/finland.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'FRANCE', 'country_code' => 'FR', 'logo' => '/assets/images/flags/france.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'GERMANY', 'country_code' => 'DE', 'logo' => '/assets/images/flags/germany.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'GIBRALTAR', 'country_code' => 'GI', 'logo' => '/assets/images/flags/gibraltar.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'GREECE', 'country_code' => 'GR', 'logo' => '/assets/images/flags/greece.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'HUNGARY', 'country_code' => 'HU', 'logo' => '/assets/images/flags/hungary.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'ICELAND', 'country_code' => 'IS', 'logo' => '/assets/images/flags/iceland.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'IRELAND', 'country_code' => 'IE', 'logo' => '/assets/images/flags/ireland.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'ISRAEL', 'country_code' => 'IL', 'logo' => '/assets/images/flags/israel.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'ITALY', 'country_code' => 'IT', 'logo' => '/assets/images/flags/italy.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'KOSOVO', 'country_code' => 'XK', 'logo' => '/assets/images/flags/kosovo.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'LATVIA', 'country_code' => 'LV', 'logo' => '/assets/images/flags/latvia.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'LIECHTENSTEIN', 'country_code' => 'LI', 'logo' => '/assets/images/flags/liechtenstein.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'LITHUANIA', 'country_code' => 'LT', 'logo' => '/assets/images/flags/lithuania.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'LUXEMBOURG', 'country_code' => 'LU', 'logo' => '/assets/images/flags/luxembourg.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'MACEDONIA', 'country_code' => 'MK', 'logo' => '/assets/images/flags/macedonia.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'MALTA', 'country_code' => 'MT', 'logo' => '/assets/images/flags/malta.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'MONACO', 'country_code' => 'MC', 'logo' => '/assets/images/flags/monaco.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'MONTENEGRO', 'country_code' => 'ME', 'logo' => '/assets/images/flags/montenegro.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'MOROCCO', 'country_code' => 'MA', 'logo' => '/assets/images/flags/morocco.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'NETHERLANDS', 'country_code' => 'NL', 'logo' => '/assets/images/flags/netherlands.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'NEW ZEALAND', 'country_code' => 'NZ', 'logo' => '/assets/images/flags/new_zealand.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'NORTHERN IRELAND', 'country_code' => 'GB', 'logo' => '/assets/images/flags/northern_ireland.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'NORWAY', 'country_code' => 'NO', 'logo' => '/assets/images/flags/norway.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'PERU', 'country_code' => 'PE', 'logo' => '/assets/images/flags/peru.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'POLAND', 'country_code' => 'PL', 'logo' => '/assets/images/flags/poland.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'PORTUGAL', 'country_code' => 'PT', 'logo' => '/assets/images/flags/portugal.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'REPUBLIC OF MOLDOVA', 'country_code' => 'RO', 'logo' => '/assets/images/flags/moldova.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'REPUBLIC OF SERBIA', 'country_code' => 'RO', 'logo' => '/assets/images/flags/serbia.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'ROMANIA', 'country_code' => 'RO', 'logo' => '/assets/images/flags/romania.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'RUSSIAN FEDERATION', 'country_code' => 'RO', 'logo' => '/assets/images/flags/russian.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'SCOTLAND', 'country_code' => 'ST', 'logo' => '/assets/images/flags/scotland.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'SLOVAKIA', 'country_code' => 'SK', 'logo' => '/assets/images/flags/slovakia.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'SLOVENIA', 'country_code' => 'SI', 'logo' => '/assets/images/flags/slovenia.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'SOUTH AFRICA', 'country_code' => 'ZA', 'logo' => '/assets/images/flags/sf.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'SPAIN', 'country_code' => 'ES', 'logo' => '/assets/images/flags/spain.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'SWEDEN', 'country_code' => 'SE', 'logo' => '/assets/images/flags/sweden.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'SWITZERLAND', 'country_code' => 'CH', 'logo' => '/assets/images/flags/switzerland.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'TUNISIA', 'country_code' => 'TN', 'logo' => '/assets/images/flags/tunisia.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'TURKEY', 'country_code' => 'TR', 'logo' => '/assets/images/flags/turkey.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'UKRAINE', 'country_code' => 'UA', 'logo' => '/assets/images/flags/ukraine.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'UNITED ARAB EMIRATES', 'country_code' => 'AE ', 'logo' => '/assets/images/flags/united_arab_emirates.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'UNITED STATES OF AMERICA', 'country_code' => 'US', 'logo' => '/assets/images/flags/usa.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'UZBEKISTAN', 'country_code' => 'UZ', 'logo' => '/assets/images/flags/uzbekistan.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'WALES', 'country_code' => 'WLS', 'logo' => '/assets/images/flags/wales.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
        ]);
    }
}
