<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

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
        	[ 'name' => 'Create users', 'slug' => 'create.users', 'description' => 'euro', 'model' => '', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
        	[ 'name' => 'Delete users', 'slug' => 'delete.users', 'description' => 'euro', 'model' => '', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
        	[ 'name' => 'Update users', 'slug' => 'update.users', 'description' => 'euro', 'model' => '', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
        ]);
    }
}
