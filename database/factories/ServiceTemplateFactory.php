<?php

/** @var Factory $factory */

use Illuminate\Database\Eloquent\Factory;
use App\Models\Fabric\FactorySystem;
use App\Models\Fabric\ServiceTemplate;
use Faker\Generator as Faker;

$factory->define(ServiceTemplate::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'source_factory_system_id' => factory(FactorySystem::class),
        'destination_factory_system_id' => factory(FactorySystem::class),
        'enabled' => $faker->boolean
    ];
});
