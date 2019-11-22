<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Player;
use Faker\Generator as Faker;

$factory->define(Player::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'birth_date' =>  $faker->dateTimeBetween('-30 years', 'now'),
		'image' => $faker->randomElement(range(1,5)).".png" ,
		'player_type' => $faker->randomElement(['batsman','bowler','wicketkeeper','allrounder'])
    ];
});
