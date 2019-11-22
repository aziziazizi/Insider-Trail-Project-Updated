<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\MatchTeam;
use Faker\Generator as Faker;

$factory->define(MatchTeam::class, function (Faker $faker) {
    return [
        'stadium' => $faker->city        
    ];
});
