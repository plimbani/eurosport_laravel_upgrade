<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class RefereeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('referee')->truncate();
        $users = DB::table('users')->take(3)->select('id')->get()->toArray();
        $tournament = DB::table('tournaments')->take(3)->select('id')->get()->toArray();
        $tournaments = DB::table('tournament_competation_template')->select('id')->take(3)->get()->toArray();

        DB::table('referee')->insert([
        	[ 'user_id' => $users[array_rand($users)]->id,
            'tournament_id' => $tournament[array_rand($tournament)]->id,'first_name' => 'test1fname',
            'last_name' => 'test1lname', 'telephone' => '1234567890','email' => 'test1@gmail.com', 'comments' => 'Labore quidem voluptas modi similique. Velit nisi blanditiis molestiae ipsum at. Assumenda enim quaerat cum sapiente nihil aut sit omnis.',
        	'age_group_id' => $tournaments[array_rand($tournaments)]->id, 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

        	['user_id' => $users[array_rand($users)]->id,
            'tournament_id' => $tournament[array_rand($tournament)]->id,'first_name' => 'test2fname',
        	'last_name' => 'test2lname', 'telephone' => '1234567890', 'email' => 'test2@gmail.com', 'comments' => 'Labore quidem voluptas modi similique. Velit nisi blanditiis molestiae ipsum at. Assumenda enim quaerat cum sapiente nihil aut sit omnis.',
        	'age_group_id' => $tournaments[array_rand($tournaments)]->id, 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

        	['user_id' => $users[array_rand($users)]->id,
            'tournament_id' => $tournament[array_rand($tournament)]->id,
          'first_name' =>'test3fname',
        	'last_name' => 'test3lname', 'telephone' => '1234567890', 'email' => 'test3@gmail.com', 'comments' => 'Labore quidem voluptas modi similique. Velit nisi blanditiis molestiae ipsum at. Assumenda enim quaerat cum sapiente nihil aut sit omnis.',
        	'age_group_id' => $tournaments[array_rand($tournaments)]->id, 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

          ['user_id' => $users[array_rand($users)]->id,
          'tournament_id' => 4,
          'first_name' =>'Steve',
          'last_name' => 'Rogers', 'telephone' => '1234567890', 'email' => 'steveRoger@gmail.com', 'comments' => 'Labore quidem voluptas modi similique. Velit nisi blanditiis molestiae ipsum at. Assumenda enim quaerat cum sapiente nihil aut sit omnis.',
          'age_group_id' => 5, 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          ['user_id' => $users[array_rand($users)]->id,
            'tournament_id' => 5,
          'first_name' =>'Tony',
          'last_name' => 'Starck', 'telephone' => '1234567890', 'email' => 'tonyStark@gmail.com', 'comments' => 'Labore quidem voluptas modi similique. Velit nisi blanditiis molestiae ipsum at. Assumenda enim quaerat cum sapiente nihil aut sit omnis.',
          'age_group_id' => 4, 'created_at' => Carbon::now()->format('Y-m-d H:i:s')]
        ]);
    }
}
