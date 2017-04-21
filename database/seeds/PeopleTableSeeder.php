<?php
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PeopleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('people')->truncate();
        DB::table('people')->insert([
        	['first_name' => 'Finch', 'last_name' => 'Nayak', 'display_name' => 'Finch', 'address' => '', 
        	'dob' => Carbon::now()->format('Y-m-d H:i:s'), 'bio' => '', 'avatar' => '1491572922.png', 'gender' => 'm', 'primary_email' => 'kamal@aecordigital.com',
        	'secondary_email' => '', 'home_phone' => '2314568', 'work_phone' => '2314568', 'mobile_number' => '7418529634',
        	'v_card' => '', 'extra_info' => '', 'settings' => '', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

        	['first_name' => 'Bill', 'last_name' => 'Parikh', 'display_name' => 'Bill', 'address' => '', 
        	'dob' => Carbon::now()->format('Y-m-d H:i:s'), 'bio' => '', 'avatar' => '1491572922.png', 'gender' => 'm', 'primary_email' => 'kparikh@aecordigital.com',
        	'secondary_email' => '', 'home_phone' => '1234567', 'work_phone' => '1234567', 'mobile_number' => '7418529630',
        	'v_card' => '', 'extra_info' => '', 'settings' => '', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

        	['first_name' => 'Elon', 'last_name' => 'Shah', 'display_name' => 'Elon', 'address' => '', 
        	'dob' => Carbon::now()->format('Y-m-d H:i:s'), 'bio' => '', 'avatar' => '1491572922.png', 'gender' => 'm', 'primary_email' => 'rshah@aecordigital.com',
        	'secondary_email' => '', 'home_phone' => '1234567', 'work_phone' => '1234567', 'mobile_number' => '7418524567',
        	'v_card' => '', 'extra_info' => '', 'settings' => '', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')] 
        ]); 
    }
}
