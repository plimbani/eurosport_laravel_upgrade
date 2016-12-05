<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Person::class, function (Faker\Generator $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'display_name' => $faker->unique()->userName,
        'address' => $faker->address,
        'dob' => $faker->dateTime($max = '-18 years'),
        'bio' => $faker->realText(),
        'avatar' => $faker->imageUrl($width = 640, $height = 480, 'people'),
        'gender' => $faker->randomElement(['m', 'f', 'n']),
        'primary_email' => $faker->unique()->safeEmail,
        'secondary_email' => $faker->safeEmail,
        'home_phone' => $faker->phoneNumber,
        'work_phone' => $faker->phoneNumber,
        'mobile_number' => $faker->phoneNumber,
    ];
});
