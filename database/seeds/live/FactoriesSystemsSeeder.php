<?php

use App\Enums\EntityNames;
use App\Enums\Factories;
use App\Enums\Systems;
use App\Models\Fabric\Entity;
use App\Models\Fabric\Factory;
use App\Models\Fabric\FactorySystem;
use App\Models\Fabric\System;
use Illuminate\Database\Seeder;

class FactoriesSystemsSeeder extends Seeder
{
    private const FACTORIES_SYSTEMS = [
        Systems::SHOPIFY => [
            'pull' => [
                Factories::ADDRESSES => EntityNames::ADDRESSES,
                Factories::CASH_REFUNDS => EntityNames::CASH_REFUNDS,
                Factories::CUSTOMERS => EntityNames::CUSTOMERS,
                Factories::FULFILMENTS => EntityNames::FULFILMENTS,
                Factories::GIFT_CARDS => EntityNames::GIFT_CARDS,
                Factories::LOCATIONS => EntityNames::LOCATIONS,
                Factories::ORDERS => EntityNames::ORDERS,
                Factories::ORDERS_REBOUND => EntityNames::ORDERS,
                Factories::PRODUCTS => EntityNames::PRODUCTS,
                Factories::REFUNDS => EntityNames::REFUNDS,
                Factories::STOCK_LEVELS => EntityNames::STOCK
            ],
            'push' => [
                Factories::ORDERS => EntityNames::ORDERS,
                Factories::CUSTOMERS => EntityNames::CUSTOMERS,
                Factories::FULFILMENTS => EntityNames::FULFILMENTS,
                Factories::INVENTORY_ADJUSTMENTS => EntityNames::STOCK,
                Factories::INVENTORY_ITEMS => EntityNames::STOCK,
                Factories::ORDER_UPDATES => EntityNames::ORDERS,
                Factories::PRICES => EntityNames::PRODUCTS,
                Factories::PRODUCTS => EntityNames::PRODUCTS,
                Factories::PVX_STOCK_LEVELS => EntityNames::STOCK,
                Factories::REFUNDS => EntityNames::REFUNDS,
                Factories::REFUNDS_REFACTORED => EntityNames::REFUNDS,
                Factories::STOCK_ADJUSTMENT => EntityNames::STOCK,
                Factories::STOCK_LEVELS => EntityNames::STOCK,
                Factories::STOCK_LEVELS_MULTI_SKU => EntityNames::STOCK,
                Factories::STOCK_MULTI_LOCATION => EntityNames::STOCK,
            ]
        ],
        Systems::PEOPLEVOX => [
            'pull' => [
                Factories::DESPATCH_PACKAGES => EntityNames::FULFILMENTS,
                Factories::EVENT_FULFILMENT => EntityNames::FULFILMENTS,
                Factories::EVENT_TRACKING_NO => EntityNames::FULFILMENTS,
                Factories::FULFILMENT => EntityNames::FULFILMENTS,
                Factories::FULFILMENT_SWEEPER => EntityNames::FULFILMENTS,
                Factories::ITEM_RECEIPTS => EntityNames::RECEIPTS,
                Factories::KIT_STOCK => EntityNames::STOCK,
                Factories::ORDERS => EntityNames::ORDERS,
                Factories::ORDERS_REFACTORED => EntityNames::ORDERS,
                Factories::PRODUCTS => EntityNames::PRODUCTS,
                Factories::RETURNS => EntityNames::RETURNS,
                Factories::RETURNS_SWEEPER => EntityNames::RETURNS,
                Factories::REVERSE_FULFILMENT_SWEEPER => EntityNames::FULFILMENTS,
                Factories::STOCK => EntityNames::STOCK,
                Factories::STOCK_CSV => EntityNames::STOCK,
                Factories::TIMESTAMP_STOCK => EntityNames::STOCK,
            ],
            'push' => [
                Factories::KITS => EntityNames::PRODUCTS,
                Factories::ORDERS => EntityNames::ORDERS,
                Factories::PRODUCTS => EntityNames::PRODUCTS,
                Factories::PRODUCT_SUPPLIERS => EntityNames::SUPPLIERS,
                Factories::PURCHASE_ORDERS => EntityNames::PURCHASE_ORDERS,
                Factories::SUPPLIERS => EntityNames::SUPPLIERS,
            ]
        ],
        Systems::NETSUITE => [
            'pull' => [
                Factories::ASSEMBLY_BUILDS => EntityNames::ASSEMBLY_BUILDS,
                Factories::CASH_REFUNDS => EntityNames::CASH_REFUNDS,
                Factories::CASH_SALES => EntityNames::CASH_SALES,
                Factories::CREDIT_MEMOS => EntityNames::CREDIT_MEMOS,
                Factories::CUSTOMERS => EntityNames::CUSTOMERS,
                Factories::FULFILMENTS => EntityNames::FULFILMENTS,
                Factories::IMPORT_CONFIRMATIONS => EntityNames::IMPORT_CONFIRMATIONS,
                Factories::INBOUND_SHIPMENTS => EntityNames::FULFILMENTS,
                Factories::INVOICES => EntityNames::ORDERS,
                Factories::ITEM_RECEIPTS => EntityNames::RECEIPTS,
                Factories::KITS => EntityNames::PRODUCTS,
                Factories::ORDERS => EntityNames::ORDERS,
                Factories::PRICES => EntityNames::PRODUCTS,
                Factories::PRODUCTS_REFACTORED => EntityNames::PRODUCTS,
                Factories::PURCHASE_ORDERS => EntityNames::PURCHASE_ORDERS,
                Factories::QUOTES => EntityNames::QUOTES,
                Factories::RECORDS => EntityNames::RECORDS,
                Factories::REFUNDS => EntityNames::REFUNDS,
                Factories::RETURNS => EntityNames::RETURNS,
                Factories::STOCK_LEVELS => EntityNames::STOCK,
                Factories::STOCK_REFACTORED => EntityNames::STOCK,
                Factories::TRANSFER_ORDERS => EntityNames::TRANSFER_ORDERS,
                Factories::WORK_ORDERS => EntityNames::WORK_ORDERS,
            ],
            'push' => [
                Factories::CASH_REFUNDS => EntityNames::CASH_REFUNDS,
                Factories::CASH_SALES => EntityNames::CASH_SALES,
                Factories::CUSTOMERS => EntityNames::CUSTOMERS,
                Factories::ESTIMATES => EntityNames::ESTIMATES,
                Factories::FULFILMENTS => EntityNames::FULFILMENTS,
                Factories::INBOUND_SHIPMENT_RECEIPT_MR => EntityNames::RECEIPTS,
                Factories::INVENTORY_ADJUSTMENTS => EntityNames::STOCK,
                Factories::INVENTORY_ADJUSTMENTS_MR => EntityNames::STOCK,
                Factories::ITEM_RECEIPTS => EntityNames::RECEIPTS,
                Factories::ITEM_RECEIPTS_MR => EntityNames::RECEIPTS,
                Factories::ORDERS => EntityNames::ORDERS,
                Factories::PRODUCTS => EntityNames::PRODUCTS,
                Factories::PURCHASE_ORDERS => EntityNames::PURCHASE_ORDERS,
                Factories::PURCHASE_ORDERS_MR => EntityNames::PURCHASE_ORDERS,
                Factories::RECORD_UPDATES_MR => EntityNames::RECORDS,
                Factories::REFUNDS => EntityNames::REFUNDS,
                Factories::RETURNS => EntityNames::RETURNS,
                Factories::TRANSFER_ORDER_FULFILMENTS => EntityNames::FULFILMENTS,
            ]
        ],
        Systems::REBOUND => [
            'pull' => [
                Factories::RETURNS => EntityNames::RETURNS,
                Factories::RETURN_UPDATES => EntityNames::RETURNS,
                Factories::CANCELLATIONS => EntityNames::CANCELLATIONS,
            ],
            'push' => [
                Factories::ORDERS => EntityNames::ORDERS,
            ]
        ],
        Systems::DYNAMICS_NAV => [
            'pull' => [
                Factories::CUSTOMERS => EntityNames::CUSTOMERS,
                Factories::FULFILMENTS => EntityNames::FULFILMENTS,
                Factories::NE_CONTACTS => EntityNames::CUSTOMERS,
                Factories::NE_CUSTOMERS => EntityNames::CUSTOMERS,
                Factories::NE_PRODUCTS => EntityNames::PRODUCTS,
                Factories::NE_SALES_DOCUMENT => EntityNames::ORDERS,
                Factories::NE_STOCK_LEVELS => EntityNames::STOCK,
                Factories::PRODUCTS => EntityNames::PRODUCTS,
                Factories::PURCHASE_ORDERS => EntityNames::PURCHASE_ORDERS,
                Factories::RETURNS => EntityNames::RETURNS,
                Factories::STOCK_LEVELS => EntityNames::STOCK,
            ],
            'push' => [
                Factories::INVENTORY_ADJUSTMENTS => EntityNames::STOCK,
                Factories::ITEM_RECEIPTS => EntityNames::RECEIPTS,
                Factories::NE_CUSTOMERS => EntityNames::CUSTOMERS,
                Factories::NE_ORDERS => EntityNames::ORDERS,
                Factories::ORDERS => EntityNames::ORDERS,
                Factories::RETURNS => EntityNames::RETURNS,
            ]
        ],
        Systems::LIGHTSPEED => [
            'pull' => [
                Factories::INVENTORY_LOGS => EntityNames::STOCK,
                Factories::REFUNDS => EntityNames::REFUNDS,
                Factories::SALES => EntityNames::ORDERS,
            ],
            'push' => [
                Factories::ITEMS => EntityNames::PRODUCTS,
            ]
        ],
        Systems::MAGENTO_2 => [
            'pull' => [
                Factories::CUSTOMERS => EntityNames::CUSTOMERS,
                Factories::ORDERS => EntityNames::ORDERS,
                Factories::PRODUCTS => EntityNames::PRODUCTS,
                Factories::REFUNDS => EntityNames::REFUNDS,
            ],
            'push' => [
                Factories::CUSTOMERS => EntityNames::CUSTOMERS,
                Factories::FULFILMENTS => EntityNames::FULFILMENTS,
                Factories::ORDERS => EntityNames::ORDERS,
                Factories::PRICES => EntityNames::PRODUCTS,
                Factories::PRODUCTS => EntityNames::PRODUCTS,
                Factories::RETURNS => EntityNames::RETURNS,
                Factories::STOCK_LEVELS => EntityNames::STOCK,
            ]
        ],
        Systems::KHAOS => [
            'pull' => [
                Factories::FULFILMENTS => EntityNames::FULFILMENTS,
                Factories::PRICES => EntityNames::PRODUCTS,
                Factories::PRODUCTS => EntityNames::PRODUCTS,
                Factories::STOCK_LEVELS => EntityNames::STOCK,
            ],
            'push' => [
                Factories::ORDERS => EntityNames::ORDERS,
                Factories::RETURNS => EntityNames::RETURNS,
            ]
        ],
        Systems::LINNWORKS => [
            'pull' => [
                Factories::PRODUCTS => EntityNames::PRODUCTS,
                Factories::STOCK => EntityNames::STOCK,
            ],
            'push' => [
                Factories::ORDERS => EntityNames::ORDERS,
                Factories::REFUNDS => EntityNames::REFUNDS,
                Factories::STOCK => EntityNames::STOCK,
            ]
        ],
        Systems::VEND => [
            'pull' => [
                Factories::INVENTORY => EntityNames::STOCK,
                Factories::PRODUCTS => EntityNames::PRODUCTS,
                Factories::REFUNDS => EntityNames::REFUNDS,
                Factories::SALES => EntityNames::ORDERS,
            ],
            'push' => [
                Factories::FULFILMENT => EntityNames::FULFILMENTS,
                Factories::PRODUCTS => EntityNames::PRODUCTS,
                Factories::STOCK => EntityNames::STOCK,
            ]
        ],
        Systems::BIGCOMMERCE => [
            'pull' => [
                Factories::ORDERS => EntityNames::ORDERS,
                Factories::REFUNDS => EntityNames::REFUNDS,
                Factories::PRODUCTS => EntityNames::PRODUCTS,
                Factories::CUSTOMERS => EntityNames::CUSTOMERS
            ],
            'push' => [
                Factories::STOCK_LEVELS => EntityNames::STOCK,
                Factories::CUSTOMERS => EntityNames::CUSTOMERS,
                Factories::PRODUCTS => EntityNames::PRODUCTS,
                Factories::FULFILMENTS => EntityNames::FULFILMENTS
            ]
        ],
        Systems::COMMERCETOOLS => [
            'pull' => [
                Factories::ORDERS => EntityNames::ORDERS,
                Factories::PRODUCTS => EntityNames::PRODUCTS,
                Factories::INVENTORY_ITEMS => EntityNames::STOCK,
                Factories::ORDER_MESSAGES => EntityNames::ORDERS,
            ],
            'push' => [
                Factories::FULFILMENTS => EntityNames::FULFILMENTS,
                Factories::ORDER_STATUS_UPDATE => EntityNames::ORDERS,
                Factories::STOCK_LEVELS => EntityNames::STOCK,
            ],
        ],
        Systems::MIRAKL => [
            'pull' => [
                Factories::ORDERS => EntityNames::ORDERS
            ],
            'push' => [
                Factories::OFFERS => EntityNames::PRODUCTS,
                Factories::ORDER_TRACKING => EntityNames::FULFILMENTS,
                Factories::REFUNDS => EntityNames::REFUNDS
            ]
        ],
        Systems::WOOCOMMERCE => [
            'pull' => [
                Factories::ORDERS => EntityNames::ORDERS,
                Factories::PRODUCTS => EntityNames::PRODUCTS,
                Factories::REFUNDS => EntityNames::REFUNDS
            ],
            'push' => [
                Factories::STOCK_LEVELS => EntityNames::STOCK,
                Factories::CREDIT_MEMOS => EntityNames::REFUNDS,
                Factories::PRODUCTS => EntityNames::PRODUCTS,
                Factories::FULFILMENTS => EntityNames::FULFILMENTS
            ]
        ],
        Systems::BRIGHTPEARL => [
            'pull' => [
                Factories::FULFILLED_SALES => EntityNames::ORDERS,
                Factories::GOODS_RECEIPTS => EntityNames::RECEIPTS,
                Factories::ORDERS => EntityNames::ORDERS,
                Factories::PRODUCTS => EntityNames::PRODUCTS,
                Factories::PURCHASE_ORDERS => EntityNames::PURCHASE_ORDERS,
                Factories::SHIPPED_SALES => EntityNames::ORDERS,
                Factories::STOCK => EntityNames::STOCK,
                Factories::SUPPLIERS => EntityNames::SUPPLIERS,
                Factories::TRANSFER_ORDERS => EntityNames::TRANSFER_ORDERS
            ],
            'push' => [
                Factories::CUSTOMERS => EntityNames::CUSTOMERS,
                Factories::ORDERS => EntityNames::ORDERS,
                Factories::PRODUCTS => EntityNames::PRODUCTS,
                Factories::PURCHASE_ORDERS => EntityNames::PURCHASE_ORDERS,
                Factories::RETURNS => EntityNames::RETURNS,
                Factories::SHIPMENTS => EntityNames::SHIPMENTS,
                Factories::STOCK => EntityNames::STOCK
            ]
        ],
        Systems::OPSUITE => [
            'pull' => [
                Factories::PRODUCTS => EntityNames::PRODUCTS,
                Factories::STOCK => EntityNames::STOCK,
            ],
            'push' => [
                Factories::SALES_TRANSACTIONS => EntityNames::SALES_TRANSACTIONS,
                Factories::REFUND_TRANSACTIONS => EntityNames::REFUND_TRANSACTIONS,
                Factories::SALES_ORDERS => EntityNames::ORDERS
            ]
        ],
        Systems::EMARSYS => [
            'pull' => [],
            'push' => [
                Factories::CUSTOMERS => EntityNames::CUSTOMERS,
                Factories::ORDERS => EntityNames::ORDERS,
                Factories::PRODUCTS => EntityNames::PRODUCTS,
            ],
        ],
        Systems::SEKO => [
            'push' => [
                Factories::ORDERS => EntityNames::ORDERS,
                Factories::PURCHASE_ORDERS => EntityNames::ORDERS,
                Factories::TRANSFER_ORDERS => EntityNames::ORDERS,
                Factories::WHOLESALE_ORDERS => EntityNames::ORDERS,
                Factories::PRODUCTS_REFACTORED => EntityNames::PRODUCTS,
                Factories::RETURNS => EntityNames::RETURNS,
                Factories::PRODUCTS => EntityNames::PRODUCTS
            ],
            'pull' => [
                Factories::BULK_ORDER_IC => EntityNames::ORDERS,
                Factories::FULFILMENTS => EntityNames::FULFILMENTS,
                Factories::STOCK_OVERNIGHT_SUMMARY => EntityNames::STOCK,
                Factories::STORE_FULFILMENTS => EntityNames::FULFILMENTS,
                Factories::BULK_RECEIPT_IC => EntityNames::RETURNS,
                Factories::STORE_GOODS_RECEIPTS => EntityNames::RETURNS,
                Factories::GOODS_RECEIPTS => EntityNames::RETURNS,
                Factories::BULK_PRODUCT_CREATE_IC => EntityNames::PRODUCTS,
                Factories::STOCK_ADJUSTMENT => EntityNames::STOCK,
                Factories::STOCK_STATUS_MOVEMENT => EntityNames::STOCK,
            ]
        ],
        Systems::SEKO_API => [
            'push' => [
                Factories::ORDERS => EntityNames::ORDERS,
                Factories::PRODUCTS => EntityNames::PRODUCTS,
                Factories::INVENTORY => EntityNames::STOCK,
                Factories::FULFILMENTS => EntityNames::FULFILMENTS,
                Factories::INVENTORY_ADJUSTMENTS => EntityNames::STOCK,
                Factories::EVENT_FULFILMENT => EntityNames::FULFILMENTS,
                Factories::PURCHASE_ORDERS => EntityNames::ORDERS
            ],
            'pull' => [
                Factories::STOCK_LEVELS => EntityNames::STOCK,
                Factories::STOCK => EntityNames::STOCK,
                Factories::ORDERS => EntityNames::ORDERS,
                Factories::EVENT_FULFILMENT => EntityNames::FULFILMENTS,
                Factories::PRODUCTS => EntityNames::PRODUCTS,
                Factories::INVENTORY => EntityNames::STOCK,
                Factories::FULFILMENTS => EntityNames::FULFILMENTS,
                Factories::INVENTORY_ADJUSTMENTS => EntityNames::STOCK,
                Factories::PURCHASE_ORDERS => EntityNames::PURCHASE_ORDERS
            ]
        ],
        Systems::VISUALSOFT => [
            'pull' => [
                Factories::ORDERS => EntityNames::ORDERS,
                Factories::CUSTOMERS => EntityNames::CUSTOMERS,
            ],
            'push' => [
                Factories::ORDER_STATUSES => EntityNames::ORDERS,
                Factories::STOCK_LEVELS => EntityNames::STOCK,
                Factories::PRODUCTS => EntityNames::PRODUCTS,
            ],
        ],
        Systems::SFTP => [
            'pull' => [
                Factories::FILES => [
                    EntityNames::ORDERS,
                    EntityNames::PRODUCTS,
                    EntityNames::STOCK
                ],
            ],
            'push' => []
        ],
        Systems::INBOUND_API => [
            'pull' => [
                Factories::JSON_PAYLOAD => [
                    EntityNames::ORDERS,
                    EntityNames::PRODUCTS
                ],
            ],
            'push' => []
        ],
        Systems::VEEQO => [
            'pull' => [
                Factories::PRODUCTS => EntityNames::PRODUCTS,
            ],
            'push' => [
                Factories::ORDERS => EntityNames::ORDERS,
                Factories::STOCK_LEVELS => EntityNames::STOCK,
            ]
        ],
        Systems::EPOSNOW => [
            'pull' => [
                Factories::ORDERS => EntityNames::ORDERS,
                Factories::STOCK_LEVELS => EntityNames::STOCK,
            ],
            'push' => [
                Factories::PRODUCTS => EntityNames::PRODUCTS,
            ]
        ]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        foreach (self::FACTORIES_SYSTEMS as $systemname => $directions) {
            $system = System::firstWhere('name', $systemname);
            if (!$system) {
                continue;
            }

            foreach ($directions as $directionKey => $factories) {
                foreach ($factories as $factoryName => $entityName) {
                    if (is_array($entityName)) {
                        foreach ($entityName as $name) {
                            $factory = Factory::firstWhere('name', $factoryName);
                            $entity = Entity::firstWhere('name', $name);
                            if (!$factory || !$entity) {
                                continue;
                            }

                            FactorySystem::updateOrCreate(
                                [
                                    'direction' => $directionKey,
                                    'factory_id' => $factory->id,
                                    'system_id' => $system->id,
                                    'entity_id' => $entity->id
                                ],
                                [
                                    'direction' => $directionKey,
                                    'factory_id' => $factory->id,
                                    'system_id' => $system->id,
                                    'entity_id' => $entity->id,
                                ]
                            );
                        }
                        continue;
                    }
                    $factory = Factory::firstWhere('name', $factoryName);
                    $entity = Entity::firstWhere('name', $entityName);
                    if (!$factory || !$entity) {
                        continue;
                    }

                    FactorySystem::updateOrCreate(
                        [
                            'direction' => $directionKey,
                            'factory_id' => $factory->id,
                            'system_id' => $system->id,
                        ],
                        [
                            'direction' => $directionKey,
                            'factory_id' => $factory->id,
                            'system_id' => $system->id,
                            'entity_id' => $entity->id,
                        ]
                    );
                }
            }
        }
    }
}
