<?php

use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Spatie\Permission\Models\Role;

/** @var Factory $factory */

$factory->define(Role::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'guard_name' => 'web',
        'patchworks_role' => false
    ];
});
