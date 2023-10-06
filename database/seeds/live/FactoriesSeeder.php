<?php

use App\Enums\Factories;
use App\Models\Fabric\Factory;
use Illuminate\Database\Seeder;

class FactoriesSeeder extends Seeder
{
    private const FACTORIES = [
        Factories::ADDRESSES,
        Factories::ASSEMBLY_BUILDS,
        Factories::BULK_ORDER_IC,
        Factories::BULK_PRODUCT_CREATE_IC,
        Factories::BULK_RECEIPT_IC,
        Factories::CANCELLATIONS,
        Factories::CASH_REFUNDS,
        Factories::CASH_SALES,
        Factories::CREDIT_MEMOS,
        Factories::CUSTOMERS,
        Factories::CUSTOM_EVENTS,
        Factories::DESPATCH_PACKAGES,
        Factories::ESTIMATES,
        Factories::EVENT_FULFILMENT,
        Factories::EVENT_TRACKING_NO,
        Factories::FILES,
        Factories::FULFILLED_SALES,
        Factories::FULFILMENT,
        Factories::FULFILMENT_SWEEPER,
        Factories::FULFILMENTS,
        Factories::GIFT_CARDS,
        Factories::GOODS_RECEIPTS,
        Factories::IMPORT_CONFIRMATIONS,
        Factories::IMPORT_RECORDS,
        Factories::INBOUND_SHIPMENT_RECEIPT_MR,
        Factories::INBOUND_SHIPMENTS,
        Factories::INVENTORY,
        Factories::INVENTORY_ADJUSTMENTS,
        Factories::INVENTORY_ADJUSTMENTS_MR,
        Factories::INVENTORY_ITEMS,
        Factories::INVENTORY_LOGS,
        Factories::INVOICES,
        Factories::ITEM_RECEIPTS,
        Factories::ITEM_RECEIPTS_MR,
        Factories::ITEMS,
        Factories::JSON_PAYLOAD,
        Factories::KIT_STOCK,
        Factories::KITS,
        Factories::LEADS,
        Factories::LOCATIONS,
        Factories::NE_CONTACTS,
        Factories::NE_CUSTOMERS,
        Factories::NE_ORDERS,
        Factories::NE_PRODUCTS,
        Factories::NE_SALES_DOCUMENT,
        Factories::NE_STOCK_LEVELS,
        Factories::OFFERS,
        Factories::ORDER_MESSAGES,
        Factories::ORDER_STATUS_UPDATE,
        Factories::ORDER_STATUSES,
        Factories::ORDER_TRACKING,
        Factories::ORDER_UPDATES,
        Factories::ORDERS,
        Factories::ORDERS_REBOUND,
        Factories::ORDERS_REFACTORED,
        Factories::PRICES,
        Factories::PRODUCT_SUPPLIERS,
        Factories::PRODUCTS,
        Factories::PRODUCTS_REFACTORED,
        Factories::PRODUCTS_UPDATE,
        Factories::PURCHASE_ORDERS,
        Factories::PURCHASE_ORDERS_MR,
        Factories::PVX_STOCK_LEVELS,
        Factories::QUOTES,
        Factories::RECORD_UPDATES_MR,
        Factories::RECORDS,
        Factories::REFUND_TRANSACTIONS,
        Factories::REFUNDS,
        Factories::REFUNDS_REFACTORED,
        Factories::RETURN_SWEEPER,
        Factories::RETURN_UPDATES,
        Factories::RETURNS,
        Factories::RETURNS_SWEEPER,
        Factories::REVERSE_FULFILMENT_SWEEPER,
        Factories::SALES,
        Factories::SALES_ORDERS,
        Factories::SALES_TRANSACTIONS,
        Factories::SHIPMENTS,
        Factories::SHIPPED_SALES,
        Factories::STOCK,
        Factories::STOCK_ADJUSTMENT,
        Factories::STOCK_CSV,
        Factories::STOCK_LEVELS,
        Factories::STOCK_LEVELS_MULTI_SKU,
        Factories::STOCK_MULTI_LOCATION,
        Factories::STOCK_OVERNIGHT_SUMMARY,
        Factories::STOCK_REFACTORED,
        Factories::STOCK_STATUS_MOVEMENT,
        Factories::STORE_FULFILMENTS,
        Factories::STORE_GOODS_RECEIPTS,
        Factories::SUBSCRIBERS,
        Factories::SUPPLIERS,
        Factories::TIMESTAMP_STOCK,
        Factories::TRANSFER_ORDER_FULFILMENTS,
        Factories::TRANSFER_ORDERS,
        Factories::WHOLESALE_ORDERS,
        Factories::WORK_ORDERS,
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        foreach (self::FACTORIES as $factoryName) {
            if (Factory::where(['name' => $factoryName])->doesntExist()) {
                Factory::create(['name' => $factoryName]);
            }
        }
    }
}
