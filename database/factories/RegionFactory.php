<?php

use App\Entity\Regions;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;


/** @var Factory $factory */
$factory->define(Regions::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->city,
        'slug' => $faker->slug(2),
        'parent_id' => null,
    ];
});
