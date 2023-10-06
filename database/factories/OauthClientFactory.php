<?php

use App\Models\Fabric\OauthClient;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Str;

/** @var Factory $factory */

$factory->define(OauthClient::class, function (Faker $faker) {
    return [
        'name' => $faker->word() . ' Grant Client',
        'secret' => Str::random(),
        'redirect' => $faker->url,
        'personal_access_client' => false,
        'password_client' => false,
        'revoked' => false
    ];
});
