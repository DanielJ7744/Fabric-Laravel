<?php

use App\Models\Fabric\Entity;
use App\Models\Fabric\Factory as FabricFactory;
use App\Models\Fabric\FactorySystem;
use App\Models\Fabric\System;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */

$factory->define(FactorySystem::class, function (Faker $faker) {
    return [
        'factory_id' => factory(FabricFactory::class),
        'system_id' => factory(System::class),
        'entity_id' => factory(Entity::class),
        'direction' => $faker->randomElement(['pull', 'push']),
        'default_map_name' => $faker->word,
        'display_name' => sprintf('%s%s', $faker->word, $faker->word),
    ];
});
