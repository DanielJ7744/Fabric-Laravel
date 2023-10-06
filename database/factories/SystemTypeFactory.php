<?php

use App\Models\Fabric\SystemType;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */

$factory->define(SystemType::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->randomElement(['eCommerce', 'Accounting', 'Marketplaces', $this->faker->word]),
        'active' => $faker->boolean,
    ];
});

$factory->state(SystemType::class, 'active', [
    'active' => true,
]);
