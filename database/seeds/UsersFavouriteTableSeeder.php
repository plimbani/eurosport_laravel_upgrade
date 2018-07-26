<?php

use Illuminate\Database\Seeder;
use Laraspace\User;
use Carbon\Carbon;

class UsersFavouriteTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users_favourite')->truncate();

        DB::table('users_favourite')->insert([
            ['tournament_id' => '1','user_id' => '5',
            'is_default' => '1', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')]
        ]);
    }
}
