<?php

use App\Models\Tapestry\PaymentMap;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */

$factory->define(PaymentMap::class, function (Faker $faker) {
    return [
        'fallback' => sprintf('%s %s', $faker->word, $faker->word),
        'methods' => [
            $faker->word => [
                'input_key' => 'payment_gateway',
                'match' => $faker->randomElement(['exact', 'contains']),
                'output' => $faker->word
            ]
        ]
    ];
});
