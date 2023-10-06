<?php

use Faker\Generator as Faker;
use App\Models\Tapestry\ServiceLog;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */

$factory->define(ServiceLog::class, function (Faker $faker) {
    return [
        'service_id' => $faker->randomNumber(),
        'from_factory' => $faker->randomElement(['Shopify\\Pull\\Orders', 'Shopify\\Pull\\Products']),
        'from_environment' => $faker->randomElement(['dev', 'test', 'prod']),
        'to_factory' => $faker->randomElement(['Magento\\Push\\Orders', 'Magento\\Pull\\Products']),
        'to_environment' => $faker->randomElement(['dev', 'test', 'prod']),
        'username' => $faker->word,
        'requested_by' => $faker->randomElement(['cron', 'cli', 'resync']),
        'status' => $faker->randomElement(['complete', 'failed', 'running', 'pending']),
        'notes' => $faker->word,
        'runtime' => $faker->randomNumber(),
        'current_page' => $faker->randomNumber(),
        'total_pages' => $faker->randomNumber(),
        'total_count' => $faker->randomNumber(),
        'page_size' => $faker->randomNumber(),
        'last_page_time' => $faker->randomNumber(),
        'error' => $faker->randomNumber(),
        'warning' => $faker->randomNumber(),
        'other' => $faker->randomNumber(),
        'filters' => $faker->word,
        'due_at' => $faker->time('Y-m-d H:i:s'),
        'queued_at' => $faker->time('Y-m-d H:i:s'),
        'started_at' => $faker->time('Y-m-d H:i:s'),
        'finished_at' => $faker->time('Y-m-d H:i:s'),
        'reported_at' => $faker->time('Y-m-d H:i:s'),
        'process_id' => $faker->randomNumber(),
        'total_pull_data_size' => $faker->randomNumber(),
    ];
});
