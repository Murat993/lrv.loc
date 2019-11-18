<?php

use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use App\Entity\Adverts\Category;


/** @var Factory $factory */
$factory->define(Category::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->name,
        'slug' => $faker->slug(2),
        'parent_id' => null,
    ];
});
