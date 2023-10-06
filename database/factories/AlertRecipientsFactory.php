<?php

use App\Models\Alerting\AlertRecipients;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */

$factory->define(AlertRecipients::class, function (Faker $faker) {
    return [
        'company_id' => $faker->numberBetween(1,5),
        'group_id' => $faker->numberBetween(1,5),
        'name' => $faker->unique()->text(10),
        'user_id' => NULL,
        'email' => $faker->unique()->safeEmail
    ];
});
