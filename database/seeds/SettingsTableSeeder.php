<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class FixturesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->truncate();

        DB::table('fixtures')->insert([
        	['tournament_id' => 4,  'user_id' => '5', 'option' => '',
          'value'=>'{"is_sound":"true","is_vibration":"true","is_notification":"true"}' ,'created_at' => Carbon::now()->format('Y-m-d H:i:s')]
        ]);
    }
}
