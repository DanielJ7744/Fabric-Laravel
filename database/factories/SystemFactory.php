<?php

use App\Models\Fabric\System;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */

$factory->define(System::class, function (Faker $faker) {
    return [
        'system_type_id' => 1,
        'name' => $faker->word,
        'factory_name' => $faker->randomElement(['Shopify', 'Magentotwo', 'Clover']),
        'slug' => null,
        'website' => 'https://www.patchworks.co.uk',
        'popular' => false,
        'description' => 'test system description',
        'status' => $faker->randomElement(['active', 'inactive', 'development', 'hidden']),
    ];
});

$factory->state(System::class, 'inbound-api', [
    'name' => 'Inbound Api',
    'factory_name' => 'InboundAPI',
]);
