<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CountriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('countries')->truncate();
        DB::table('countries')->insert([
            ['name' => 'ALBANIA', 'country_code' => 'AL ', 'country_flag' => 'al', 'logo' => '/assets/img/flags/AL.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'AUSTRIA', 'country_code' => 'AT', 'country_flag' => 'at', 'logo' => '/assets/img/flags/AT.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'AUSTRALIA', 'country_code' => 'AU', 'country_flag' => 'au', 'logo' => '/assets/img/flags/AU.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'BOSNIA AND HERZEGOVINA', 'country_code' => 'BA', 'country_flag' => 'ba', 'logo' => '/assets/img/flags/BA.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'BELGIUM', 'country_code' => 'BE', 'country_flag' => 'be', 'logo' => '/assets/img/flags/BE.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'BULGARIA', 'country_code' => 'BG', 'country_flag' => 'bg', 'logo' => '/assets/img/flags/BG.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'BELARUS', 'country_code' => 'BY', 'country_flag' => 'by', 'logo' => '/assets/img/flags/BY.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'CANADA', 'country_code' => 'CA', 'country_flag' => 'ca', 'logo' => '/assets/img/flags/CA.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'CHINA', 'country_code' => 'CN', 'country_flag' => 'cn', 'logo' => '/assets/img/flags/CN.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'CROATIA', 'country_code' => 'HR', 'country_flag' => 'hr', 'logo' => '/assets/img/flags/HR.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'CYPRUS', 'country_code' => 'CY', 'country_flag' => 'cy', 'logo' => '/assets/img/flags/CY.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'CZECH REPUBLIC', 'country_code' => 'CZ', 'country_flag' => 'cz', 'logo' => '/assets/img/flags/CZ.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'DENMARK', 'country_code' => 'DK', 'country_flag' => 'dk', 'logo' => '/assets/img/flags/DK.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'ENGLAND', 'country_code' => 'EN', 'country_flag' => 'gb-eng', 'logo' => '/assets/img/flags/GBEng.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'ESTONIA', 'country_code' => 'EE', 'country_flag' => 'ee', 'logo' => '/assets/img/flags/EE.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'FAROE ISLANDS', 'country_code' => 'FO', 'country_flag' => 'fo', 'logo' => '/assets/img/flags/FO.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'FINLAND', 'country_code' => 'FI', 'country_flag' => 'fi', 'logo' => '/assets/img/flags/FI.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'FRANCE', 'country_code' => 'FR', 'country_flag' => 'fr', 'logo' => '/assets/img/flags/FR.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'GERMANY', 'country_code' => 'DE', 'country_flag' => 'de', 'logo' => '/assets/img/flags/DE.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'GIBRALTAR', 'country_code' => 'GI', 'country_flag' => 'gi', 'logo' => '/assets/img/flags/GI.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'GREECE', 'country_code' => 'GR', 'country_flag' => 'gr', 'logo' => '/assets/img/flags/GR.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'HUNGARY', 'country_code' => 'HU', 'country_flag' => 'hu', 'logo' => '/assets/img/flags/HU.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'ICELAND', 'country_code' => 'IS', 'country_flag' => 'is', 'logo' => '/assets/img/flags/IS.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'IRELAND', 'country_code' => 'IE', 'country_flag' => 'ie', 'logo' => '/assets/img/flags/IE.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'ISRAEL', 'country_code' => 'IL', 'country_flag' => 'il', 'logo' => '/assets/img/flags/IL.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'ITALY', 'country_code' => 'IT', 'country_flag' => 'it', 'logo' => '/assets/img/flags/IT.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'KOSOVO', 'country_code' => 'XK', 'country_flag' => 'ko', 'logo' => '/assets/img/flags/KO.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'LATVIA', 'country_code' => 'LV', 'country_flag' => 'lv', 'logo' => '/assets/img/flags/LV.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'LIECHTENSTEIN', 'country_code' => 'LI', 'country_flag' => 'li', 'logo' => '/assets/img/flags/LI.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'LITHUANIA', 'country_code' => 'LT', 'country_flag' => 'lt', 'logo' => '/assets/img/flags/LT.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'LUXEMBOURG', 'country_code' => 'LU', 'country_flag' => 'lu', 'logo' => '/assets/img/flags/LU.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'MACEDONIA', 'country_code' => 'MK', 'country_flag' => 'mk', 'logo' => '/assets/img/flags/MK.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'MALTA', 'country_code' => 'MT', 'country_flag' => 'mt', 'logo' => '/assets/img/flags/MT.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'MONACO', 'country_code' => 'MC', 'country_flag' => 'mc', 'logo' => '/assets/img/flags/MC.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'MONTENEGRO', 'country_code' => 'ME', 'country_flag' => 'me', 'logo' => '/assets/img/flags/ME.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'MOROCCO', 'country_code' => 'MA', 'country_flag' => 'ma', 'logo' => '/assets/img/flags/MA.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'NETHERLANDS', 'country_code' => 'NL', 'country_flag' => 'nl', 'logo' => '/assets/img/flags/NL.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'NEW ZEALAND', 'country_code' => 'NZ', 'country_flag' => 'nz', 'logo' => '/assets/img/flags/NZ.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'NORTHERN IRELAND', 'country_code' => 'NI', 'country_flag' => 'gb-nir', 'logo' => '/assets/img/flags/GBNir.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'NORWAY', 'country_code' => 'NO', 'country_flag' => 'no', 'logo' => '/assets/img/flags/NO.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'PERU', 'country_code' => 'PE', 'country_flag' => 'pe', 'logo' => '/assets/img/flags/PE.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'POLAND', 'country_code' => 'PL', 'country_flag' => 'pl', 'logo' => '/assets/img/flags/PL.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'PORTUGAL', 'country_code' => 'PT', 'country_flag' => 'pt', 'logo' => '/assets/img/flags/PT.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'REPUBLIC OF MOLDOVA', 'country_code' => 'MD', 'country_flag' => 'md', 'logo' => '/assets/img/flags/MD.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'REPUBLIC OF SERBIA', 'country_code' => 'RS', 'country_flag' => 'rs', 'logo' => '/assets/img/flags/RS.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'ROMANIA', 'country_code' => 'RO', 'country_flag' => 'ro', 'logo' => '/assets/img/flags/RO.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'RUSSIAN FEDERATION', 'country_code' => 'RU', 'country_flag' => 'ru', 'logo' => '/assets/img/flags/RU.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'SCOTLAND', 'country_code' => 'SC', 'country_flag' => 'gb-sct', 'logo' => '/assets/img/flags/GBSct.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'SLOVAKIA', 'country_code' => 'SK', 'country_flag' => 'sk', 'logo' => '/assets/img/flags/SK.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'SLOVENIA', 'country_code' => 'SI', 'country_flag' => 'si', 'logo' => '/assets/img/flags/SI.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'SOUTH AFRICA', 'country_code' => 'ZA', 'country_flag' => 'za', 'logo' => '/assets/img/flags/ZA.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'SPAIN', 'country_code' => 'ES', 'country_flag' => 'es', 'logo' => '/assets/img/flags/ES.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'SWEDEN', 'country_code' => 'SE', 'country_flag' => 'se', 'logo' => '/assets/img/flags/SE.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'SWITZERLAND', 'country_code' => 'CH', 'country_flag' => 'ch', 'logo' => '/assets/img/flags/CH.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'TUNISIA', 'country_code' => 'TN', 'country_flag' => 'tn', 'logo' => '/assets/img/flags/TN.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'TURKEY', 'country_code' => 'TR', 'country_flag' => 'tr', 'logo' => '/assets/img/flags/TR.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'UKRAINE', 'country_code' => 'UA', 'country_flag' => 'ua', 'logo' => '/assets/img/flags/UA.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'UNITED ARAB EMIRATES', 'country_code' => 'AE ', 'country_flag' => 'ae', 'logo' => '/assets/img/flags/AE.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'UNITED STATES OF AMERICA', 'country_code' => 'US', 'country_flag' => 'us', 'logo' => '/assets/img/flags/US.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'UZBEKISTAN', 'country_code' => 'UZ', 'country_flag' => 'uz', 'logo' => '/assets/img/flags/UZ.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'WALES', 'country_code' => 'WA', 'country_flag' => 'gb-wls', 'logo' => '/assets/img/flags/GBWls.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'ARGENTINA', 'country_code' => 'AR', 'country_flag' => 'ar', 'logo' => '/assets/img/flags/AR.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'BRAZIL', 'country_code' => 'BR', 'country_flag' => 'br', 'logo' => '/assets/img/flags/BR.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'ALGERIA', 'country_code' => 'DZ', 'country_flag' => 'dz', 'logo' => '/assets/img/flags/DZ.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'GEORGIA', 'country_code' => 'GE', 'country_flag' => 'ge', 'logo' => '/assets/img/flags/GE.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'ANDORRA', 'country_code' => 'AD', 'country_flag' => 'ad', 'logo' => '/assets/img/flags/AD.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'ISLE OF MAN', 'country_code' => 'IM', 'country_flag' => 'im', 'logo' => '/assets/img/flags/IM.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'JAPAN', 'country_code' => 'JP', 'country_flag' => 'jp', 'logo' => '/assets/img/flags/JP.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'MOLDAVA', 'country_code' => 'MD', 'country_flag' => 'md', 'logo' => '/assets/img/flags/MD.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'SAN MARINO', 'country_code' => 'SM', 'country_flag' => 'sm', 'logo' => '/assets/img/flags/SM.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'SERBIA', 'country_code' => 'RS', 'country_flag' => 'rs', 'logo' => '/assets/img/flags/RS.png', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
        ]);
        Schema::enableForeignKeyConstraints();
    }
}
