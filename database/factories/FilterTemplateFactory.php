<?php

use App\Models\Fabric\FilterTemplate;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */

$factory->define(FilterTemplate::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'factory_system_id' => 1,
        'filter_key' => $faker->unique()->randomElement([
                'id',
                'No',
                'internalid',
                'itemid',
                'Item+Code',
                'Despatch+Number',
                'STOCK_CODE',
                'increment_id',
            ]),
        'template' => $faker->unique()->randomElement([
                '{"id":"%s"}',
                '{"No":"%s"}',
                '{"internalid anyof":"[%s]"}',
                '{"itemid startswith":"%s"}',
                '{"Item+Code":"%s"}',
                '{"Despatch+Number":"%s"}',
                '{"STOCK_CODE":"%s"}',
                '{"increment_id":"%s"}',
            ]),
        'note' => $faker->word,
        'pw_value_field' => $faker->unique()->randomElement([
                'id',
                'No',
                'internalid',
                'itemid',
                'Item+Code',
                'Despatch+Number',
                'STOCK_CODE',
                'increment_id',
            ]),
    ];
});
