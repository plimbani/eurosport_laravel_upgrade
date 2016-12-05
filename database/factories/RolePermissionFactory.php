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
$factory->define(Duro85\Roles\Models\Role::class, function (Faker\Generator $faker) {
    $word = $faker->word;

    return [
        'name' => $word,
        'slug' => $word,
        'description' => $faker->sentence,
    ];
});
$factory->define(Duro85\Roles\Models\Permission::class, function (Faker\Generator $faker) {
    $word = $faker->word;

    return [
        'name' => $word,
        'slug' => $word,
        'description' => $faker->sentence,
    ];
});
