<?php

use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Spatie\Permission\Models\Permission;

/** @var Factory $factory */

$factory->define(Permission::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'guard_name' => 'web'
    ];
});
