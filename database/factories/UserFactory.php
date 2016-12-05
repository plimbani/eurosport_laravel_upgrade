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
$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'person_id' => function () {
            return factory(App\Models\Person::class)->create()->id;
        },
        'username' => function (array $user) {
            return App\Models\Person::find($user['person_id'])->display_name;
        },
        'email' => function (array $user) {
            return App\Models\Person::find($user['person_id'])->primary_email;
        },
        'password' => $password ?: $password = bcrypt('secret'),
        'token' => md5(uniqid(mt_rand(), true)),
        'remember_token' => str_random(10),
        'is_verified' => 0,
    ];
});
