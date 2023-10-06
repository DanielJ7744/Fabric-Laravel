<?php

use App\Models\Tapestry\Service;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */

$factory->define(Service::class, function (Faker $faker) {
    return [
        'status' => $faker->boolean(),
        'description' => $faker->company,
        'schedule' => $faker->randomElement(['off', '* * * * *', '0 0 * * 1-5']),
        'from_environment' => $faker->randomElement(['dev', 'test', 'prod']),
        'to_environment' => $faker->randomElement(['dev', 'test', 'prod']),
        'from_factory' => $faker->randomElement(['Shopify\\Pull\\Orders', 'Shopify\\Pull\\Products']),
        'to_factory' => $faker->randomElement(['Magentotwo\\Push\\Orders', 'Magentotwo\\Pull\\Products']),
        'username' => $faker->word,
        'from_options' => [
            'page_size' => $faker->numberBetween(1, 1000),
            'max_attempts' => $faker->numberBetween(1, 15),
            'timezone' => $faker->timezone,
        ],
        'to_options' => [
            'timezone' => $faker->timezone,
        ],
        'billable' => $faker->boolean(),
    ];
});
