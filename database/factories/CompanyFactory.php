<?php

use App\Models\Fabric\Company;
use App\Models\Fabric\Subscription;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */

$factory->define(Company::class, function (Faker $faker) {
    return [
        'name' => $faker->word() . ' Ltd',
        'active' => true,
        'company_email' => $faker->email,
        'company_phone' => "01234567890",
        'company_website' => $faker->url,
    ];
});

$factory->state(Company::class, 'active', ['active' => true]);

$factory
    ->afterCreating(Company::class, fn ($company) => $company
        ->subscriptions()
        ->attach(factory(Subscription::class)
            ->create(['services' => 5])));
