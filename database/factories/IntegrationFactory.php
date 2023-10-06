<?php

use App\Models\Fabric\Integration;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Str;

/** @var Factory $factory */

$factory->define(Integration::class, function (Faker $faker) {
    return [
        'name' => sprintf(
            '%s %s',
            $faker->company,
            $faker->unique()->randomNumber(4)
        ),
        'username' => Str::slug($faker->userName, '_'),
        'server' => config('fabric.integration_server'),
        'active' => $faker->boolean,
    ];
});
