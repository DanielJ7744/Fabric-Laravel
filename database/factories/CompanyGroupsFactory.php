<?php

use App\Models\Companies\CompanyGroup;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */

$factory->define(CompanyGroup::class, function (Faker $faker) {
    $groupName = $faker->word;
    return [
        'company_id' => 1,
        'group_name' => $groupName,
        'idx_table_name' => sprintf('idx_%s', mb_strtolower($groupName)),
    ];
});
