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
        DB::table('countries')->delete();
        DB::table('countries')->insert([
          [ 'name' => 'ALBANIA', 'country_code' => 'AL ', 'country_flag' => 'al', 'logo' => '/assets/img/flags/al.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'AUSTRIA', 'country_code' => 'AT', 'country_flag' => 'at', 'logo' => '/assets/img/flags/austr.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'AUSTRALIA', 'country_code' => 'AU', 'country_flag' => 'au', 'logo' => '/assets/img/flags/aust.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'BOSNIA AND HERZEGOVINA', 'country_code' => 'BA', 'country_flag' => 'ba', 'logo' => '/assets/img/flags/bosnia.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'BELGIUM', 'country_code' => 'BE', 'country_flag' => 'be', 'logo' => '/assets/img/flags/belgium.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'BULGARIA', 'country_code' => 'BG', 'country_flag' => 'bg', 'logo' => '/assets/img/flags/bulgaria.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'BELARUS', 'country_code' => 'BY', 'country_flag' => 'by', 'logo' => '/assets/img/flags/belarus.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'CANADA', 'country_code' => 'CA', 'country_flag' => 'ca', 'logo' => '/assets/img/flags/canada.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'CHINA', 'country_code' => 'CN', 'country_flag' => 'cn', 'logo' => '/assets/img/flags/china.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'CROATIA', 'country_code' => 'HR', 'country_flag' => 'hr', 'logo' => '/assets/img/flags/croatia.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'CYPRUS', 'country_code' => 'CY', 'country_flag' => 'cy', 'logo' => '/assets/img/flags/cyprus.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'CZECH REPUBLIC', 'country_code' => 'CZ', 'country_flag' => 'cz', 'logo' => '/assets/img/flags/czech.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'DENMARK', 'country_code' => 'DK', 'country_flag' => 'dk', 'logo' => '/assets/img/flags/denmark.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'ENGLAND', 'country_code' => 'GB', 'country_flag' => 'gb', 'logo' => '/assets/img/flags/england.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'ESTONIA', 'country_code' => 'EE', 'country_flag' => 'ee', 'logo' => '/assets/img/flags/estonia.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'FAROE ISLANDS', 'country_code' => 'FO', 'country_flag' => 'fo', 'logo' => '/assets/img/flags/faroe.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'FINLAND', 'country_code' => 'FI', 'country_flag' => 'fi', 'logo' => '/assets/img/flags/finland.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'FRANCE', 'country_code' => 'FR', 'country_flag' => 'fr', 'logo' => '/assets/img/flags/france.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'GERMANY', 'country_code' => 'DE', 'country_flag' => 'de', 'logo' => '/assets/img/flags/germany.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'GIBRALTAR', 'country_code' => 'GI', 'country_flag' => 'gi', 'logo' => '/assets/img/flags/gibraltar.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'GREECE', 'country_code' => 'GR', 'country_flag' => 'gr', 'logo' => '/assets/img/flags/greece.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'HUNGARY', 'country_code' => 'HU', 'country_flag' => 'hu', 'logo' => '/assets/img/flags/hungary.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'ICELAND', 'country_code' => 'IS', 'country_flag' => 'is', 'logo' => '/assets/img/flags/iceland.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'IRELAND', 'country_code' => 'IE', 'country_flag' => 'ie', 'logo' => '/assets/img/flags/ireland.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'ISRAEL', 'country_code' => 'IL', 'country_flag' => 'il', 'logo' => '/assets/img/flags/israel.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'ITALY', 'country_code' => 'IT', 'country_flag' => 'it', 'logo' => '/assets/img/flags/italy.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'KOSOVO', 'country_code' => 'XK', 'country_flag' => 'xk', 'logo' => '/assets/img/flags/kosovo.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'LATVIA', 'country_code' => 'LV', 'country_flag' => 'lv', 'logo' => '/assets/img/flags/latvia.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'LIECHTENSTEIN', 'country_code' => 'LI', 'country_flag' => 'li', 'logo' => '/assets/img/flags/liechtenstein.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'LITHUANIA', 'country_code' => 'LT', 'country_flag' => 'lt', 'logo' => '/assets/img/flags/lithuania.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'LUXEMBOURG', 'country_code' => 'LU', 'country_flag' => 'lu', 'logo' => '/assets/img/flags/luxembourg.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'MACEDONIA', 'country_code' => 'MK', 'country_flag' => 'mk', 'logo' => '/assets/img/flags/macedonia.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'MALTA', 'country_code' => 'MT', 'country_flag' => 'mt', 'logo' => '/assets/img/flags/malta.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'MONACO', 'country_code' => 'MC', 'country_flag' => 'mc', 'logo' => '/assets/img/flags/monaco.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'MONTENEGRO', 'country_code' => 'ME', 'country_flag' => 'me', 'logo' => '/assets/img/flags/montenegro.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'MOROCCO', 'country_code' => 'MA', 'country_flag' => 'ma', 'logo' => '/assets/img/flags/morocco.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'NETHERLANDS', 'country_code' => 'NL', 'country_flag' => 'nl', 'logo' => '/assets/img/flags/netherlands.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'NEW ZEALAND', 'country_code' => 'NZ', 'country_flag' => 'nz', 'logo' => '/assets/img/flags/new_zealand.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'NORTHERN IRELAND', 'country_code' => 'GB', 'country_flag' => 'gb', 'logo' => '/assets/img/flags/northern_ireland.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'NORWAY', 'country_code' => 'NO', 'country_flag' => 'no', 'logo' => '/assets/img/flags/norway.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'PERU', 'country_code' => 'PE', 'country_flag' => 'pe', 'logo' => '/assets/img/flags/peru.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'POLAND', 'country_code' => 'PL', 'country_flag' => 'pl', 'logo' => '/assets/img/flags/poland.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'PORTUGAL', 'country_code' => 'PT', 'country_flag' => 'pt', 'logo' => '/assets/img/flags/portugal.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'REPUBLIC OF MOLDOVA', 'country_code' => 'MD', 'country_flag' => 'md', 'logo' => '/assets/img/flags/moldova.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'REPUBLIC OF SERBIA', 'country_code' => 'RS', 'country_flag' => 'rs', 'logo' => '/assets/img/flags/serbia.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'ROMANIA', 'country_code' => 'RO', 'country_flag' => 'ro', 'logo' => '/assets/img/flags/romania.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'RUSSIAN FEDERATION', 'country_code' => 'RU', 'country_flag' => 'ru', 'logo' => '/assets/img/flags/russian.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'SCOTLAND', 'country_code' => 'ST', 'country_flag' => 'st', 'logo' => '/assets/img/flags/scotland.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'SLOVAKIA', 'country_code' => 'SK', 'country_flag' => 'sk', 'logo' => '/assets/img/flags/slovakia.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'SLOVENIA', 'country_code' => 'SI', 'country_flag' => 'si', 'logo' => '/assets/img/flags/slovenia.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'SOUTH AFRICA', 'country_code' => 'ZA', 'country_flag' => 'za', 'logo' => '/assets/img/flags/sf.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'SPAIN', 'country_code' => 'ES', 'country_flag' => 'es', 'logo' => '/assets/img/flags/spain.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'SWEDEN', 'country_code' => 'SE', 'country_flag' => 'se', 'logo' => '/assets/img/flags/sweden.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'SWITZERLAND', 'country_code' => 'CH', 'country_flag' => 'ch', 'logo' => '/assets/img/flags/switzerland.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'TUNISIA', 'country_code' => 'TN', 'country_flag' => 'tn', 'logo' => '/assets/img/flags/tunisia.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'TURKEY', 'country_code' => 'TR', 'country_flag' => 'tr', 'logo' => '/assets/img/flags/turkey.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'UKRAINE', 'country_code' => 'UA', 'country_flag' => 'ua', 'logo' => '/assets/img/flags/ukraine.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'UNITED ARAB EMIRATES', 'country_code' => 'AE ', 'country_flag' => 'ae', 'logo' => '/assets/img/flags/united_arab_emirates.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'UNITED STATES OF AMERICA', 'country_code' => 'US', 'country_flag' => 'us', 'logo' => '/assets/img/flags/usa.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'UZBEKISTAN', 'country_code' => 'UZ', 'country_flag' => 'uz', 'logo' => '/assets/img/flags/uzbekistan.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          [ 'name' => 'WALES', 'country_code' => 'WLS', 'country_flag' => 'gb', 'logo' => '/assets/img/flags/wales.gif', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
        ]);
    }
}
