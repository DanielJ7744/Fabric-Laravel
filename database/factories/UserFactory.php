<?php

use App\Models\Fabric\User;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Str;

/** @var Factory $factory */

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => sprintf('%s %s', $faker->firstName, $faker->lastName),
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10),
    ];
});

$factory->afterMakingState(User::class, 'client user', fn ($user) => $user->assignRole('client user'));
$factory->afterMakingState(User::class, 'client admin', fn ($user) => $user->assignRole('client admin'));
$factory->afterMakingState(User::class, 'patchworks user', fn ($user) => $user->assignRole('patchworks user'));
$factory->afterMakingState(User::class, 'patchworks admin', fn ($user) => $user->assignRole('patchworks admin'));
