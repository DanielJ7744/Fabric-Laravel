<?php

use App\Models\Alerting\AlertManager;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Carbon\Carbon;

/** @var Factory $factory */

$factory->define(AlertManager::class, function (Faker $faker) {
    return [
        'company_id' => 1,
        'service_id' => 1,
        'config_id' => 1,
        'recipient_id' => 1,
        'alert_type' => 'error',
        'send_from' => Carbon::now()->toDateTimeString(),
        'seen_on_dashboard' => 0
    ];
});
