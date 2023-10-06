<?php

use App\Models\Alerting\AlertGroups;
use App\Models\Fabric\Company;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */

$factory->define(AlertGroups::class, function (Faker $faker) {
    return [
        'name' => 'Test Group UK',
        'company_id' => factory(Company::class),
    ];
});
