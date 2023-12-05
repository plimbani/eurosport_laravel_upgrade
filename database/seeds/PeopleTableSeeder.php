<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

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
            ['first_name' => 'Super', 'last_name' => 'Admin', 'display_name' => 'Super Admin', 'address' => '',
                'dob' => Carbon::now()->format('Y-m-d H:i:s'), 'bio' => '', 'avatar' => '1491572922.png', 'gender' => 'm', 'primary_email' => 'rstenson@aecordigital.com',
                'secondary_email' => '', 'home_phone' => '123456', 'work_phone' => '123456', 'mobile_number' => '1234567890',
                'v_card' => '', 'extra_info' => '', 'settings' => '', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

            ['first_name' => 'Albert', 'last_name' => 'Mens', 'display_name' => 'Albert Mens', 'address' => '',
                'dob' => Carbon::now()->format('Y-m-d H:i:s'), 'bio' => '', 'avatar' => '1491572922.png', 'gender' => 'm', 'primary_email' => 'a.mens@euro-sportring.org',
                'secondary_email' => '', 'home_phone' => '123456', 'work_phone' => '123456', 'mobile_number' => '1234567890',
                'v_card' => '', 'extra_info' => '', 'settings' => '', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

            ['first_name' => 'Nitin', 'last_name' => 'Deopura', 'display_name' => 'Nitin Deopura', 'address' => '',
                'dob' => Carbon::now()->format('Y-m-d H:i:s'), 'bio' => '', 'avatar' => '1491572922.png', 'gender' => 'm', 'primary_email' => 'ndeopura@aecordigital.com',
                'secondary_email' => '', 'home_phone' => '123456', 'work_phone' => '123456', 'mobile_number' => '1234567890',
                'v_card' => '', 'extra_info' => '', 'settings' => '', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

            ['first_name' => 'Rich', 'last_name' => 'Internal', 'display_name' => 'Rich Internal', 'address' => '',
                'dob' => Carbon::now()->format('Y-m-d H:i:s'), 'bio' => '', 'avatar' => '1491572922.png', 'gender' => 'm',
                'primary_email' => 'richinternal@mailinator.com', 'secondary_email' => '', 'home_phone' => '123456',
                'work_phone' => '123456', 'mobile_number' => '1234567890',
                'v_card' => '', 'extra_info' => '', 'settings' => '',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

            ['first_name' => 'Test', 'last_name' => 'Email', 'display_name' => 'Test Email', 'address' => '',
                'dob' => Carbon::now()->format('Y-m-d H:i:s'), 'bio' => '', 'avatar' => '1491572922.png', 'gender' => 'm',
                'primary_email' => 'testerRS1@mailinator.com', 'secondary_email' => '', 'home_phone' => '123456',
                'work_phone' => '123456', 'mobile_number' => '1234567890',
                'v_card' => '', 'extra_info' => '', 'settings' => '',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

            ['first_name' => 'Test', 'last_name' => 'Admin', 'display_name' => 'Test Admin', 'address' => '',
                'dob' => Carbon::now()->format('Y-m-d H:i:s'), 'bio' => '', 'avatar' => '1491572922.png', 'gender' => 'm',
                'primary_email' => 'testadmin@mailinator.com', 'secondary_email' => '', 'home_phone' => '123456',
                'work_phone' => '123456', 'mobile_number' => '1234567890',
                'v_card' => '', 'extra_info' => '', 'settings' => '',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

            ['first_name' => 'Rich', 'last_name' => 'Results', 'display_name' => 'Rich Results', 'address' => '',
                'dob' => Carbon::now()->format('Y-m-d H:i:s'), 'bio' => '', 'avatar' => '1491572922.png', 'gender' => 'm',
                'primary_email' => 'richresults@mailinator.com', 'secondary_email' => '', 'home_phone' => '123456',
                'work_phone' => '123456', 'mobile_number' => '1234567890',
                'v_card' => '', 'extra_info' => '', 'settings' => '',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

            // ['first_name' => 'Bill', 'last_name' => 'Bill', 'display_name' => 'Bill', 'address' => '',
            // 'dob' => Carbon::now()->format('Y-m-d H:i:s'), 'bio' => '', 'avatar' => '1491572922.png', 'gender' => 'm', 'primary_email' => 'kparikh@aecordigital.com',
            // 'secondary_email' => '', 'home_phone' => '1234567', 'work_phone' => '1234567', 'mobile_number' => '7418529630',
            // 'v_card' => '', 'extra_info' => '', 'settings' => '', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

            // ['first_name' => 'Elon', 'last_name' => 'Elon', 'display_name' => 'Elon', 'address' => '',
            // 'dob' => Carbon::now()->format('Y-m-d H:i:s'), 'bio' => '', 'avatar' => '1491572922.png', 'gender' => 'm', 'primary_email' => 'rshah@aecordigital.com',
            // 'secondary_email' => '', 'home_phone' => '1234567', 'work_phone' => '1234567', 'mobile_number' => '7418524567',
            // 'v_card' => '', 'extra_info' => '', 'settings' => '', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            //    ['first_name' => 'Joseph', 'last_name' => 'Joseph', 'display_name' => 'Joseph', 'address' => '',
            //    'dob' => Carbon::now()->format('Y-m-d H:i:s'), 'bio' => '', 'avatar' => '1491572922.png', 'gender' => 'm', 'primary_email' => 'upatel@aecordigital.com',
            //    'secondary_email' => '', 'home_phone' => '1234567', 'work_phone' => '1234567', 'mobile_number' => '7418524567',
            //    'v_card' => '', 'extra_info' => '', 'settings' => '', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            //    ['first_name' => 'Rick', 'last_name' => 'Allen', 'display_name' => 'Rlen', 'address' => '',
            //    'dob' => Carbon::now()->format('Y-m-d H:i:s'), 'bio' => '', 'avatar' => '1491572922.png', 'gender' => 'm', 'primary_email' => 'knayak@aecordigital.com',
            //    'secondary_email' => '', 'home_phone' => '1234567', 'work_phone' => '1234567', 'mobile_number' => '74185245671',
            //    'v_card' => '', 'extra_info' => '', 'settings' => '', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')]
        ]);
    }
}
