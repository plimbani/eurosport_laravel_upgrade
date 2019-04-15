<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('roles')->truncate();
        DB::table('roles')->insert([
          ['name' => 'Super administrator', 'slug' => 'Super.administrator', 'description' => 'The magician', 'level' => '1', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          ['name' => 'Tournament administrator', 'slug' => 'tournament.administrator', 'description' => 'The GOD', 'level' => '1', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          ['name' => 'Internal administrator', 'slug' => 'Internal.administrator', 'description' => 'The Demigod', 'level' => '1', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          ['name' => 'Master administrator', 'slug' => 'Master.administrator', 'description' => 'The Man', 'level' => '1', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          ['name' => 'Mobile user', 'slug' => 'mobile.user', 'description' => 'Mobile User', 'level' => '1', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          ['name' => 'Customer', 'slug' => 'customer', 'description' => 'Customer', 'level' => '1', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
          ['name' => 'Results administrator', 'slug' => 'Results.administrator', 'description' => 'Results administrator', 'level' => '1', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')]
        ]);
        Schema::enableForeignKeyConstraints();
    }
}
