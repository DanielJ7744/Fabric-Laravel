<?php

use App\Models\Fabric\FilterOperator;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */

$factory->define(FilterOperator::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'key' => $faker->word,
    ];
});
