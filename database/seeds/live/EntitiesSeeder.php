<?php

use App\Enums\EntityNames;
use App\Models\Fabric\Entity;
use Illuminate\Database\Seeder;

class EntitiesSeeder extends Seeder
{
    private const ENTITIES = [
        EntityNames::ADDRESSES,
        EntityNames::ASSEMBLY_BUILDS,
        EntityNames::CANCELLATIONS,
        EntityNames::CASH_REFUNDS,
        EntityNames::CASH_SALES,
        EntityNames::CREDIT_MEMOS,
        EntityNames::CUSTOMERS,
        EntityNames::CUSTOM_EVENTS,
        EntityNames::ESTIMATES,
        EntityNames::FULFILMENTS,
        EntityNames::GIFT_CARDS,
        EntityNames::IMPORT_CONFIRMATIONS,
        EntityNames::LOCATIONS,
        EntityNames::ORDERS,
        EntityNames::PRODUCTS,
        EntityNames::PURCHASE_ORDERS,
        EntityNames::QUOTES,
        EntityNames::RECEIPTS,
        EntityNames::RECORDS,
        EntityNames::REFUND_TRANSACTIONS,
        EntityNames::REFUNDS,
        EntityNames::RETURNS,
        EntityNames::SALES_TRANSACTIONS,
        EntityNames::SHIPMENTS,
        EntityNames::STOCK,
        EntityNames::SUPPLIERS,
        EntityNames::TRANSFER_ORDERS,
        EntityNames::WHOLESALE_ORDERS,
        EntityNames::WORK_ORDERS,
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        foreach (self::ENTITIES as $entity) {
            if (Entity::where(['name' => $entity])->doesntExist()) {
                Entity::create(['name' => $entity]);
            }
        }
    }
}
