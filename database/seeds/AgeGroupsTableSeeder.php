<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AgeGroupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('age_groups')->delete();
        foreach (range(11, 19) as $index) {
            DB::table('age_groups')->insert([
                ['name' => 'U'.$index, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ]);
        }
        DB::table('age_groups')->insert([
                ['name' => 'Adults', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'Veterans', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);
    }
}
