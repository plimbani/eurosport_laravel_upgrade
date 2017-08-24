<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ClubsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('clubs')->truncate();
        $users = DB::table('users')->take(3)->select('id')->get()->toArray();

        DB::table('clubs')->insert(
        	[ 'user_id' => $users[array_rand($users)]->id, 'name' => 'Euro Sportring','created_at' => Carbon::now()->format('Y-m-d H:i:s')],
        	[ 'user_id' => $users[array_rand($users)]->id, 'name' => 'Cadiz Real', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
        	[ 'user_id' => $users[array_rand($users)]->id, 'name' => 'Jerez FC', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            [ 'user_id' => $users[array_rand($users)]->id, 'name' => 'Barmstedt', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')]
        );
    }
}
