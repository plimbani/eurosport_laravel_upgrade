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
        DB::table('website_settings')->insert([
                ['key_field' => 'currency', 'value_field' => json_encode(['eur' => 1, 'gbp' => 1.5])],
        ]);
    }
}
