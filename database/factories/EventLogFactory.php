<?php

use App\Models\Fabric\EventLog;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */

$factory->define(EventLog::class, function (Faker $faker) {
    return [
        'method' => $faker->randomElement(['api', 'dashboard', 'schedule']),
        'area' => $faker->randomElement(EventLog::$areas),
        'action' => $faker->randomElement(['service_scheduled_manually', 'created_user', 'login']),
        'value' => 'admin@patchworks.com',
        'successful' => $faker->boolean,
    ];
});
