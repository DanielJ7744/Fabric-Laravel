<?php

use App\Models\Fabric\FilterType;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */

$factory->define(FilterType::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->text(10),
        'placeholder' => $faker->unique()->text(10),
        'key' => $faker->unique()->text(10)
    ];
});
