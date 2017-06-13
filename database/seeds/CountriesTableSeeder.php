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
          [ 'name' => 'ALBANIA', 'country_code' => 'AL ', 'logo' => '/assets/img/flags/al.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'AUSTRIA', 'country_code' => 'AT', 'logo' => '/assets/img/flags/austr.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'AUSTRALIA', 'country_code' => 'AU', 'logo' => '/assets/img/flags/aust.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'BOSNIA AND HERZEGOVINA', 'country_code' => 'BA', 'logo' => '/assets/img/flags/bosnia.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'BELGIUM', 'country_code' => 'BE', 'logo' => '/assets/img/flags/belgium.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'BULGARIA', 'country_code' => 'BG', 'logo' => '/assets/img/flags/bulgaria.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'BELARUS', 'country_code' => 'BY', 'logo' => '/assets/img/flags/belarus.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'CANADA', 'country_code' => 'CA', 'logo' => '/assets/img/flags/canada.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'CHINA', 'country_code' => 'CN', 'logo' => '/assets/img/flags/china.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'CROATIA', 'country_code' => 'HR', 'logo' => '/assets/img/flags/croatia.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'CYPRUS', 'country_code' => 'CY', 'logo' => '/assets/img/flags/cyprus.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'CZECH REPUBLIC', 'country_code' => 'CZ', 'logo' => '/assets/img/flags/czech.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'DENMARK', 'country_code' => 'DK', 'logo' => '/assets/img/flags/denmark.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'ENGLAND', 'country_code' => 'ENG', 'logo' => '/assets/img/flags/england.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'ESTONIA', 'country_code' => 'EE', 'logo' => '/assets/img/flags/estonia.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'FAROE ISLANDS', 'country_code' => 'FO', 'logo' => '/assets/img/flags/faroe.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'FINLAND', 'country_code' => 'FI', 'logo' => '/assets/img/flags/finland.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'FRANCE', 'country_code' => 'FR', 'logo' => '/assets/img/flags/france.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'GERMANY', 'country_code' => 'DE', 'logo' => '/assets/img/flags/germany.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'GIBRALTAR', 'country_code' => 'GI', 'logo' => '/assets/img/flags/gibraltar.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'GREECE', 'country_code' => 'GR', 'logo' => '/assets/img/flags/greece.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'HUNGARY', 'country_code' => 'HU', 'logo' => '/assets/img/flags/hungary.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'ICELAND', 'country_code' => 'IS', 'logo' => '/assets/img/flags/iceland.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'IRELAND', 'country_code' => 'IE', 'logo' => '/assets/img/flags/ireland.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'ISRAEL', 'country_code' => 'IL', 'logo' => '/assets/img/flags/israel.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'ITALY', 'country_code' => 'IT', 'logo' => '/assets/img/flags/italy.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'KOSOVO', 'country_code' => 'XK', 'logo' => '/assets/img/flags/kosovo.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'LATVIA', 'country_code' => 'LV', 'logo' => '/assets/img/flags/latvia.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'LIECHTENSTEIN', 'country_code' => 'LI', 'logo' => '/assets/img/flags/liechtenstein.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'LITHUANIA', 'country_code' => 'LT', 'logo' => '/assets/img/flags/lithuania.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'LUXEMBOURG', 'country_code' => 'LU', 'logo' => '/assets/img/flags/luxembourg.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'MACEDONIA', 'country_code' => 'MK', 'logo' => '/assets/img/flags/macedonia.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'MALTA', 'country_code' => 'MT', 'logo' => '/assets/img/flags/malta.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'MONACO', 'country_code' => 'MC', 'logo' => '/assets/img/flags/monaco.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'MONTENEGRO', 'country_code' => 'ME', 'logo' => '/assets/img/flags/montenegro.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'MOROCCO', 'country_code' => 'MA', 'logo' => '/assets/img/flags/morocco.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'NETHERLANDS', 'country_code' => 'NL', 'logo' => '/assets/img/flags/netherlands.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'NEW ZEALAND', 'country_code' => 'NZ', 'logo' => '/assets/img/flags/new_zealand.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'NORTHERN IRELAND', 'country_code' => 'GB', 'logo' => '/assets/img/flags/northern_ireland.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'NORWAY', 'country_code' => 'NO', 'logo' => '/assets/img/flags/norway.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'PERU', 'country_code' => 'PE', 'logo' => '/assets/img/flags/peru.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'POLAND', 'country_code' => 'PL', 'logo' => '/assets/img/flags/poland.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'PORTUGAL', 'country_code' => 'PT', 'logo' => '/assets/img/flags/portugal.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'REPUBLIC OF MOLDOVA', 'country_code' => 'RO', 'logo' => '/assets/img/flags/moldova.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'REPUBLIC OF SERBIA', 'country_code' => 'RO', 'logo' => '/assets/img/flags/serbia.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'ROMANIA', 'country_code' => 'RO', 'logo' => '/assets/img/flags/romania.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'RUSSIAN FEDERATION', 'country_code' => 'RO', 'logo' => '/assets/img/flags/russian.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'SCOTLAND', 'country_code' => 'ST', 'logo' => '/assets/img/flags/scotland.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'SLOVAKIA', 'country_code' => 'SK', 'logo' => '/assets/img/flags/slovakia.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'SLOVENIA', 'country_code' => 'SI', 'logo' => '/assets/img/flags/slovenia.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'SOUTH AFRICA', 'country_code' => 'ZA', 'logo' => '/assets/img/flags/sf.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'SPAIN', 'country_code' => 'ES', 'logo' => '/assets/img/flags/spain.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'SWEDEN', 'country_code' => 'SE', 'logo' => '/assets/img/flags/sweden.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'SWITZERLAND', 'country_code' => 'CH', 'logo' => '/assets/img/flags/switzerland.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'TUNISIA', 'country_code' => 'TN', 'logo' => '/assets/img/flags/tunisia.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'TURKEY', 'country_code' => 'TR', 'logo' => '/assets/img/flags/turkey.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'UKRAINE', 'country_code' => 'UA', 'logo' => '/assets/img/flags/ukraine.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'UNITED ARAB EMIRATES', 'country_code' => 'AE ', 'logo' => '/assets/img/flags/united_arab_emirates.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'UNITED STATES OF AMERICA', 'country_code' => 'US', 'logo' => '/assets/img/flags/usa.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'UZBEKISTAN', 'country_code' => 'UZ', 'logo' => '/assets/img/flags/uzbekistan.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'WALES', 'country_code' => 'WLS', 'logo' => '/assets/img/flags/wales.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
        ]);
    }
}
