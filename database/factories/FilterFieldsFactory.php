<?php

use App\Models\Fabric\FactorySystem;
use App\Models\Fabric\FilterField;
use App\Models\Fabric\FilterOperator;
use App\Models\Fabric\FilterType;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */

$factory->define(FilterField::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'key' => $faker->regexify('[A-Za-z]{10}'),
        'factory_system_id' => factory(FactorySystem::class),
        'default' => $faker->randomElement([true, false]),
        'default_value' => $faker->word,
        'default_type_id' => factory(FilterType::class),
        'default_operator_id' => factory(FilterOperator::class)
    ];
});
