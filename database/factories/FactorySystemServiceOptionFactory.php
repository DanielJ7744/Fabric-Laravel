<?php

/** @var Factory $factory */

use App\Models\Fabric\FactorySystem;
use App\Models\Fabric\FactorySystemServiceOption;
use App\Models\Fabric\ServiceOption;
use Illuminate\Database\Eloquent\Factory;
use Faker\Generator as Faker;

$factory->define(FactorySystemServiceOption::class, function (Faker $faker) {
    return [
        'factory_system_id' => factory(FactorySystem::class),
        'service_option_id' => factory(ServiceOption::class),
        'value' => $faker->word,
        'user_configurable' => $faker->boolean()
    ];
});
