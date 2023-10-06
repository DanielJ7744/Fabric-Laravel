<?php

use App\Models\Fabric\AuthorisationType;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */

$factory->define(AuthorisationType::class, function (Faker $faker) {
    return [
        'name' => 'Test Auth Type'
    ];
});
