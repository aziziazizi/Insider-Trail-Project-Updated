<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */


use Faker\Generator as Faker;

$factory->define(App\Match::class, function (Faker $faker) {
    return [
        'round' => $faker->randomDigit
    ];
});
