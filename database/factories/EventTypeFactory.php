<?php

use App\Models\Fabric\System;
use App\Models\Fabric\EventType;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */

$factory->define(EventType::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->randomElement(['Order Created', 'Order Deleted']),
        'key' => $faker->unique()->randomElement(['OrderCreated', 'OrderDeleted']),
        'schema_values' => $faker->word,
        'system_id' => factory(System::class),
    ];
});
