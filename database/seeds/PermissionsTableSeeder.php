<?php

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
        DB::table('permissoins')->truncate();
        DB::table('permissions')->insert([
        	[ 'name' => 'Kamal', 'slug' => 'kamal', 'description' => 'euro', 'model' => ''],
        	[ 'name' => 'Krunal', 'slug' => 'krunal', 'description' => 'euro', 'model' => ''],
        	[ 'name' => 'Rishabh', 'slug' => 'rishabh', 'description' => 'euro', 'model' => ''],
        ]);
    }
}
