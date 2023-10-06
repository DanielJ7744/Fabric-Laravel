<?php

use App\Models\Tapestry\ShipmentMap;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */

$factory->define(ShipmentMap::class, function (Faker $faker) {
    return [
        'fallback' => sprintf('%s %s', $faker->word, $faker->word),
        'methods' => [
            $faker->word => [
                'input_key' => $faker->randomElement(['shipping_title', 'shipping_method']),
                'match' => $faker->randomElement(['exact', 'contains']),
                'output' => $faker->word
            ]
        ]
    ];
});
