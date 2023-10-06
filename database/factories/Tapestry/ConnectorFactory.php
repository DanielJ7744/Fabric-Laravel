<?php

use App\Models\Tapestry\Connector;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */

$factory->define(Connector::class, function (Faker $faker) {
    return [
        'system_chain' => $faker->randomElement(['Shopify', 'Peoplevox']),
        'common_ref' => $faker->randomElement(['dev', 'live']),
        'extra' => [
            'client_id' => 1,
            'url' => $faker->url,
        ]
    ];
});
