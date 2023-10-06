<?php

use App\Models\Fabric\ServiceOption;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */

$factory->define(ServiceOption::class, function (Faker $faker) {
    return [
        'key' => $faker->word,
    ];
});
