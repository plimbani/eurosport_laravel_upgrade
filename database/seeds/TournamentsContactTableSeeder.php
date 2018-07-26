<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TournamentsContactTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tournament_contact')->truncate();
        $tournament = DB::table('tournaments')->take(3)->select('id')->get()->toArray();

        DB::table('tournament_contact')->insert([
        	['tournament_id' => 2, 'first_name' => 'Ivan', 'last_name' => 'Vanrykel',
            'telephone' => '+32 475 25 73 39', 'email' => 'tivanrykel@gmail.com', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

        	['tournament_id' =>1, 'first_name' => 'Selmir', 'last_name' => 'Sabic',
            'telephone' => '0172 4192 144', 'email' => 'selmir.sabic@gmail.com', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

        	['tournament_id' => 3, 'first_name' => 'Peter', 'last_name' => 'Deseyn',
            'telephone' => '0498623343', 'email' => 'peterdeseyn@gmail.com', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          ['tournament_id' => 4, 'first_name' => 'Maarten', 'last_name' => 'Boudens',
            'telephone' => '0498623343', 'email' => 'Maarten@gmail.com', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          ['tournament_id' => 5, 'first_name' => 'ESR', 'last_name' => 'tournament',
            'telephone' => '0498623343', 'email' => 'hans.kolder@gmail.com', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')]
        ]);
    }
}
