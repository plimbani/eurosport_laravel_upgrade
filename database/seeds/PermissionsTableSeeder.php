<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->delete();
        DB::table('permissions')->insert([
            ['name' => 'Create users','guard_name' => 'web'],
            ['name' => 'Delete users','guard_name' => 'web'],
            ['name' => 'Update users','guard_name' => 'web'],
        ]);
    }
}
