<?php

use App\Models\Fabric\FactorySystem;
use App\Models\Fabric\FactorySystemSchema;
use App\Models\Fabric\Integration;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */

$factory->define(FactorySystemSchema::class, function (Faker $faker) {
    return [
        'type' => 'json',
        'schema' => $faker->word
    ];
});
