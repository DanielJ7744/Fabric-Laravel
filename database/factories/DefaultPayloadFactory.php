<?php

use App\Models\Fabric\DefaultPayload;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */

$factory->define(DefaultPayload::class, function (Faker $faker) {
    return [
        'type' => $faker->randomElement(['csv', 'json']),
        'payload' => json_encode([$faker->word => $faker->word]),
    ];
});
