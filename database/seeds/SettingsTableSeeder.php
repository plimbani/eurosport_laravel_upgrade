<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->truncate();

        DB::table('settings')->insert([
            [ 'user_id' => 1, 'option' => '',
          'value'=>'{"is_sound":"true","is_vibration":"true","is_notification":"true"}' ,'created_at' => Carbon::now()->format('Y-m-d H:i:s')]
        ]);

        DB::table('settings')->insert([
            [ 'user_id' => 2, 'option' => '',
          'value'=>'{"is_sound":"true","is_vibration":"true","is_notification":"true"}' ,'created_at' => Carbon::now()->format('Y-m-d H:i:s')]
        ]);

        DB::table('settings')->insert([
            [ 'user_id' => 3, 'option' => '',
          'value'=>'{"is_sound":"true","is_vibration":"true","is_notification":"true"}' ,'created_at' => Carbon::now()->format('Y-m-d H:i:s')]
        ]);
    }
}
