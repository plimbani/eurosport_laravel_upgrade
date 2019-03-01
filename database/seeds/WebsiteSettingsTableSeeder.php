<?php

use Illuminate\Database\Seeder;

class WebsiteSettingsTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Default setting value
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('website_settings')->insert([
                ['key_field' => 'currency', 'value_field' => '{"eur":"1","gbp":"1.5"}'],
        ]);
    }
}
