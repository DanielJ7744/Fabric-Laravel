<?php

/** @var Factory $factory */

use App\Models\Fabric\ServiceOption;
use App\Models\Fabric\ServiceTemplate;
use App\Models\Fabric\ServiceTemplateOption;
use Illuminate\Database\Eloquent\Factory;
use Faker\Generator as Faker;

$factory->define(ServiceTemplateOption::class, function (Faker $faker) {
    return [
        'service_option_id' => factory(ServiceOption::class),
        'service_template_id' => factory(ServiceTemplate::class),
        'target' => $faker->randomElement(['destination', 'source']),
        'value' => $faker->word,
        'user_configurable' => 0
    ];
});
