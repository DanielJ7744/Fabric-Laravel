<?php

use App\Models\Alerting\AlertConfigs;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */

$factory->define(AlertConfigs::class, function (Faker $faker) {
    return [
        'company_id' => 1,
        'service_id' => $faker->numberBetween(1000,5000),
        'error_alert_threshold' => $faker->numberBetween(0, 999),
        'warning_alert_threshold' => $faker->numberBetween(0, 999),
        'frequency_alert_threshold' => $faker->numberBetween(0, 999999),
        'alert_frequency' => $faker->randomElement(['off', '0 * * * *', '0 0 * * *']),
        'throttle_value' => $faker->numberBetween(1,3),
        'alert_status' => $faker->numberBetween(0,1)
    ];
});
