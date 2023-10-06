<?php

use App\Models\Fabric\Factory as FactoryModel;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */

$factory->define(FactoryModel::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->randomElement([
                'CreditMemos',
                'Customers',
                'Fulfilments',
                'GoodsReceipts',
                'InventoryAdjustments',
                'InventoryItem',
                'ItemReceipts',
                'Orders',
                'OrdersRebound',
                'Products',
                'PurchaseOrders',
            ]) . rand(1, 9999),
    ];
});
