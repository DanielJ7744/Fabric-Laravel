<?php

use App\Models\Fabric\AuthorisationType;
use App\Models\Fabric\System;
use App\Models\Fabric\SystemAuthorisationType;
use Illuminate\Database\Eloquent\Factory;
use Faker\Generator as Faker;

/** @var Factory $factory */

$factory->define(SystemAuthorisationType::class, function (Faker $faker) {
    return [
        'system_id' => factory(System::class),
        'authorisation_type_id' => factory(AuthorisationType::class),
        'credentials_schema' => '{}'
    ];
});
