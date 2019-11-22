<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */


use Faker\Generator as Faker;

$factory->define(\App\Team::class, function (Faker $faker) {
	
    return [
        'name' => $country = $faker->unique()->country,
        'flag' => file_exists(public_path("\\uploads\\countries\\".strtolower(str_replace(" ","_",$country)).'.gif')) ?	strtolower(str_replace(" ","_",$country)).'.gif' : 'default.gif'
       
    ];
});
