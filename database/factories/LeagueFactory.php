<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\League;
use Faker\Generator as Faker;

$factory->define(League::class, function (Faker $faker) {
    return [
        'name' => $faker->name,        
        'no_of_overs' => $faker->randomElement([50,20,10])       
    ];
});
