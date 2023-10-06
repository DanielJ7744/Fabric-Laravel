<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Fabric\Subscription;
use Faker\Generator as Faker;

$factory->define(Subscription::class, function (Faker $faker) {
    return [
        'name' => $faker->randomElement(['Free', 'Basic', 'Pro', 'Enterprise']),
        'default' => false,
        'upgrade' => $faker->boolean(),
        'services' => $faker->randomElement([2, 5, 20, 100]),
        'transactions' => $faker->randomElement([5_000, 20_000, 100_000]),
        'business_insights' => false,
        'api_keys' => $faker->numberBetween(0, 3),
        'sftp' => $faker->boolean(),
        'users' => $faker->randomElement([1, 3, 10, 999]),
        'price' => $faker->randomElement([null, 500, 1_200, 2_000]),
        'sku' => $faker->unique()->uuid(),
    ];
});
