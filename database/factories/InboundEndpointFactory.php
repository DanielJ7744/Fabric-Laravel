<?php

use App\Models\Fabric\InboundEndpoint;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Str;

/** @var Factory $factory */

$factory->define(InboundEndpoint::class, function (Faker $faker) {
    return [
        'slug' => Str::slug($faker->unique()->word),
        'external_endpoint_id' => $faker->unique()->randomNumber(),
    ];
});
