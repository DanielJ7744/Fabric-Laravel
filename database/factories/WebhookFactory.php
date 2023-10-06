<?php

use App\Models\Fabric\Webhook;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */

$factory->define(Webhook::class, function (Faker $faker) {

    return [
        'active' => true,
        'remote_reference' => $faker->randomNumber()
    ];
});
