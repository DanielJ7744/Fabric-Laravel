<?php

use App\Enums\Factories;
use App\Enums\ServiceNames;
use App\Enums\Systems;
use App\Models\Fabric\Factory;
use App\Models\Fabric\FactorySystem;
use App\Models\Fabric\ServiceTemplate;
use App\Models\Fabric\System;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class ServiceTemplateSeeder extends Seeder
{
    private const SERVICE_TEMPLATES = [
        [
            'name' => ServiceNames::FULFILMENTS,
            'source' => [
                'factory' => Factories::FULFILMENTS,
                'system' => Systems::SEKO
            ],
            'destination' => [
                'factory' => Factories::FULFILMENTS,
                'system' => Systems::NETSUITE
            ]
        ],
        [
            'name' => ServiceNames::GRN_RETURNS,
            'source' => [
                'factory' => Factories::GOODS_RECEIPTS,
                'system' => Systems::SEKO
            ],
            'destination' => [
                'factory' => Factories::RETURNS,
                'system' => Systems::NETSUITE
            ]
        ],
        [
            'name' => ServiceNames::ITEM_RECEIPTS,
            'source' => [
                'factory' => Factories::GOODS_RECEIPTS,
                'system' => Systems::SEKO
            ],
            'destination' => [
                'factory' => Factories::ITEM_RECEIPTS_MR,
                'system' => Systems::NETSUITE
            ]
        ],
        [
            'name' => ServiceNames::STOCK,
            'source' => [
                'factory' => Factories::STOCK_ADJUSTMENT,
                'system' => Systems::SEKO
            ],
            'destination' => [
                'factory' => Factories::INVENTORY_ADJUSTMENTS_MR,
                'system' => Systems::NETSUITE
            ]
        ],
        [
            'name' => ServiceNames::STOCK_STATUS_MOVEMENT,
            'source' => [
                'factory' => Factories::STOCK_STATUS_MOVEMENT,
                'system' => Systems::SEKO
            ],
            'destination' => [
                'factory' => Factories::INVENTORY_ADJUSTMENTS_MR,
                'system' => Systems::NETSUITE
            ]
        ],
        [
            'name' => ServiceNames::FULFILMENTS,
            'source' => [
                'factory' => Factories::FULFILMENTS,
                'system' => Systems::SEKO
            ],
            'destination' => [
                'factory' => Factories::FULFILMENTS,
                'system' => Systems::SHOPIFY
            ]
        ],
        [
            'name' => ServiceNames::STOCK_OVERNIGHT_SUMMARY,
            'source' => [
                'factory' => Factories::STOCK_OVERNIGHT_SUMMARY,
                'system' => Systems::SEKO
            ],
            'destination' => [
                'factory' => Factories::STOCK_LEVELS,
                'system' => Systems::SHOPIFY
            ]
        ],
        [
            'name' => ServiceNames::ORDERS,
            'source' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::NETSUITE
            ],
            'destination' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::SEKO
            ]
        ],
        [
            'name' => ServiceNames::PRODUCTS,
            'source' => [
                'factory' => Factories::PRODUCTS_REFACTORED,
                'system' => Systems::NETSUITE
            ],
            'destination' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::SEKO
            ]
        ],
        [
            'name' => ServiceNames::PURCHASE_ORDERS,
            'source' => [
                'factory' => Factories::PURCHASE_ORDERS,
                'system' => Systems::NETSUITE
            ],
            'destination' => [
                'factory' => Factories::PURCHASE_ORDERS,
                'system' => Systems::SEKO
            ]
        ],
        [
            'name' => ServiceNames::RETURNS,
            'source' => [
                'factory' => Factories::RETURNS,
                'system' => Systems::NETSUITE
            ],
            'destination' => [
                'factory' => Factories::RETURNS,
                'system' => Systems::SEKO
            ]
        ],
        [
            'name' => ServiceNames::TRANSFER_ORDERS,
            'source' => [
                'factory' => Factories::TRANSFER_ORDERS,
                'system' => Systems::NETSUITE
            ],
            'destination' => [
                'factory' => Factories::TRANSFER_ORDERS,
                'system' => Systems::SEKO
            ]
        ],
        [
            'name' => ServiceNames::ORDERS,
            'source' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::SHOPIFY
            ],
            'destination' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::SEKO
            ]
        ],
        [
            'name' => ServiceNames::STOCK,
            'source' => [
                'factory' => Factories::STOCK,
                'system' => Systems::SEKO_API
            ],
            'destination' => [
                'factory' => Factories::STOCK_LEVELS,
                'system' => Systems::SHOPIFY
            ]
        ],
        [
            'name' => ServiceNames::EVENT_FULFILMENTS,
            'source' => [
                'factory' => Factories::EVENT_FULFILMENT,
                'system' => Systems::SEKO_API
            ],
            'destination' => [
                'factory' => Factories::FULFILMENTS,
                'system' => Systems::SHOPIFY
            ]
        ],
        [
            'name' => ServiceNames::ORDERS,
            'source' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::SHOPIFY
            ],
            'destination' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::SEKO_API
            ]
        ],
        [
            'name' => ServiceNames::PRODUCTS,
            'source' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::SHOPIFY
            ],
            'destination' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::SEKO_API
            ]
        ],
        [
            'name' => ServiceNames::ORDERS,
            'source' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::SHOPIFY,
            ],
            'destination' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::NETSUITE,
            ],
        ],
        [
            'name' => ServiceNames::CUSTOMERS,
            'source' => [
                'factory' => Factories::CUSTOMERS,
                'system' => Systems::SHOPIFY,
            ],
            'destination' => [
                'factory' => Factories::CUSTOMERS,
                'system' => Systems::NETSUITE,
            ],
        ],
        [
            'name' => ServiceNames::RETURNS,
            'source' => [
                'factory' => Factories::REFUNDS,
                'system' => Systems::SHOPIFY,
            ],
            'destination' => [
                'factory' => Factories::RETURNS,
                'system' => Systems::NETSUITE,
            ],
        ],
        [
            'name' => ServiceNames::CASH_SALES,
            'source' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::SHOPIFY,
            ],
            'destination' => [
                'factory' => Factories::CASH_SALES,
                'system' => Systems::NETSUITE,
            ],
        ],
        [
            'name' => ServiceNames::STOCK,
            'source' => [
                'factory' => Factories::STOCK_REFACTORED,
                'system' => Systems::NETSUITE,
            ],
            'destination' => [
                'factory' => Factories::STOCK_LEVELS,
                'system' => Systems::SHOPIFY,
            ],
        ],
        [
            'name' => ServiceNames::REFUNDS,
            'source' => [
                'factory' => Factories::RETURNS,
                'system' => Systems::NETSUITE,
            ],
            'destination' => [
                'factory' => Factories::REFUNDS_REFACTORED,
                'system' => Systems::SHOPIFY,
            ],
        ],
        [
            'name' => ServiceNames::CUSTOMERS,
            'source' => [
                'factory' => Factories::CUSTOMERS,
                'system' => Systems::NETSUITE,
            ],
            'destination' => [
                'factory' => Factories::CUSTOMERS,
                'system' => Systems::SHOPIFY,
            ],
        ],
        [
            'name' => ServiceNames::FULFILMENTS,
            'source' => [
                'factory' => Factories::FULFILMENTS,
                'system' => Systems::NETSUITE,
            ],
            'destination' => [
                'factory' => Factories::FULFILMENTS,
                'system' => Systems::SHOPIFY,
            ],
        ],
        [
            'name' => ServiceNames::INVOICE_UPDATES,
            'source' => [
                'factory' => Factories::INVOICES,
                'system' => Systems::NETSUITE,
            ],
            'destination' => [
                'factory' => Factories::ORDER_UPDATES,
                'system' => Systems::SHOPIFY,
            ],
        ],
        [
            'name' => ServiceNames::KITS,
            'source' => [
                'factory' => Factories::KITS,
                'system' => Systems::NETSUITE,
            ],
            'destination' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::SHOPIFY,
            ],
        ],
        [
            'name' => ServiceNames::ORDER_UPDATES,
            'source' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::NETSUITE,
            ],
            'destination' => [
                'factory' => Factories::ORDER_UPDATES,
                'system' => Systems::SHOPIFY,
            ],
        ],
        [
            'name' => ServiceNames::PRODUCTS,
            'source' => [
                'factory' => Factories::PRODUCTS_REFACTORED,
                'system' => Systems::NETSUITE,
            ],
            'destination' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::SHOPIFY,
            ],
        ],
        [
            'name' => ServiceNames::ORDERS,
            'source' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::SHOPIFY,
            ],
            'destination' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::PEOPLEVOX,
            ],
        ],
        [
            'name' => ServiceNames::PRODUCTS,
            'source' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::SHOPIFY,
            ],
            'destination' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::PEOPLEVOX,
            ],
        ],
        [
            'name' => ServiceNames::STOCK,
            'source' => [
                'factory' => Factories::TIMESTAMP_STOCK,
                'system' => Systems::PEOPLEVOX,
            ],
            'destination' => [
                'factory' => Factories::PVX_STOCK_LEVELS,
                'system' => Systems::SHOPIFY,
            ],
        ],
        [
            'name' => ServiceNames::REFUNDS,
            'source' => [
                'factory' => Factories::RETURNS_SWEEPER,
                'system' => Systems::PEOPLEVOX,
            ],
            'destination' => [
                'factory' => Factories::REFUNDS_REFACTORED,
                'system' => Systems::SHOPIFY,
            ],
        ],
        [
            'name' => ServiceNames::FULFILMENTS,
            'source' => [
                'factory' => Factories::FULFILMENT_SWEEPER,
                'system' => Systems::PEOPLEVOX,
            ],
            'destination' => [
                'factory' => Factories::FULFILMENTS,
                'system' => Systems::SHOPIFY,
            ],
        ],
        [
            'name' => ServiceNames::ORDERS,
            'source' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::SHOPIFY,
            ],
            'destination' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::DYNAMICS_NAV,
            ],
        ],
        [
            'name' => ServiceNames::RETURNS,
            'source' => [
                'factory' => Factories::REFUNDS,
                'system' => Systems::SHOPIFY,
            ],
            'destination' => [
                'factory' => Factories::RETURNS,
                'system' => Systems::DYNAMICS_NAV,
            ],
        ],
        [
            'name' => ServiceNames::FULFILMENTS,
            'source' => [
                'factory' => Factories::FULFILMENTS,
                'system' => Systems::DYNAMICS_NAV,
            ],
            'destination' => [
                'factory' => Factories::FULFILMENTS,
                'system' => Systems::SHOPIFY,
            ],
        ],
        [
            'name' => ServiceNames::PRODUCTS,
            'source' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::DYNAMICS_NAV,
            ],
            'destination' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::SHOPIFY,
            ],
        ],
        [
            'name' => ServiceNames::STOCK,
            'source' => [
                'factory' => Factories::STOCK_LEVELS,
                'system' => Systems::DYNAMICS_NAV,
            ],
            'destination' => [
                'factory' => Factories::STOCK_LEVELS,
                'system' => Systems::SHOPIFY,
            ],
        ],
        [
            'name' => ServiceNames::ORDERS,
            'source' => [
                'factory' => Factories::ORDERS_REBOUND,
                'system' => Systems::SHOPIFY,
            ],
            'destination' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::REBOUND,
            ],
        ],
        [
            'name' => ServiceNames::ORDERS,
            'source' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::NETSUITE,
            ],
            'destination' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::PEOPLEVOX,
            ],
        ],
        [
            'name' => ServiceNames::SUPPLIERS,
            'source' => [
                'factory' => Factories::RECORDS,
                'system' => Systems::NETSUITE,
            ],
            'destination' => [
                'factory' => Factories::SUPPLIERS,
                'system' => Systems::PEOPLEVOX,
            ],
        ],
        [
            'name' => ServiceNames::PRODUCTS,
            'source' => [
                'factory' => Factories::PRODUCTS_REFACTORED,
                'system' => Systems::NETSUITE,
            ],
            'destination' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::PEOPLEVOX,
            ],
        ],
        [
            'name' => ServiceNames::DESPATCH_PACKAGES,
            'source' => [
                'factory' => Factories::DESPATCH_PACKAGES,
                'system' => Systems::PEOPLEVOX,
            ],
            'destination' => [
                'factory' => Factories::FULFILMENTS,
                'system' => Systems::NETSUITE,
            ],
        ],
        [
            'name' => ServiceNames::FULFILMENTS_SWEEPER,
            'source' => [
                'factory' => Factories::FULFILMENT_SWEEPER,
                'system' => Systems::PEOPLEVOX,
            ],
            'destination' => [
                'factory' => Factories::FULFILMENTS,
                'system' => Systems::NETSUITE,
            ],
        ],
        [
            'name' => ServiceNames::ITEM_RECEIPTS,
            'source' => [
                'factory' => Factories::ITEM_RECEIPTS,
                'system' => Systems::PEOPLEVOX,
            ],
            'destination' => [
                'factory' => Factories::ITEM_RECEIPTS_MR,
                'system' => Systems::NETSUITE,
            ],
        ],
        [
            'name' => ServiceNames::STOCK,
            'source' => [
                'factory' => Factories::STOCK,
                'system' => Systems::PEOPLEVOX,
            ],
            'destination' => [
                'factory' => Factories::INVENTORY_ADJUSTMENTS_MR,
                'system' => Systems::NETSUITE,
            ],
        ],
        [
            'name' => ServiceNames::RETURNS,
            'source' => [
                'factory' => Factories::RETURNS,
                'system' => Systems::REBOUND,
            ],
            'destination' => [
                'factory' => Factories::RETURNS,
                'system' => Systems::NETSUITE,
            ],
        ],
        [
            'name' => ServiceNames::ORDERS,
            'source' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::NETSUITE,
            ],
            'destination' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::REBOUND,
            ],
        ],
        [
            'name' => ServiceNames::PRODUCTS,
            'source' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::DYNAMICS_NAV,
            ],
            'destination' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::PEOPLEVOX,
            ],
        ],
        [
            'name' => ServiceNames::PURCHASE_ORDERS,
            'source' => [
                'factory' => Factories::PURCHASE_ORDERS,
                'system' => Systems::DYNAMICS_NAV,
            ],
            'destination' => [
                'factory' => Factories::PURCHASE_ORDERS,
                'system' => Systems::PEOPLEVOX,
            ],
        ],
        [
            'name' => ServiceNames::ITEM_RECEIPTS,
            'source' => [
                'factory' => Factories::ITEM_RECEIPTS,
                'system' => Systems::PEOPLEVOX,
            ],
            'destination' => [
                'factory' => Factories::ITEM_RECEIPTS,
                'system' => Systems::DYNAMICS_NAV,
            ],
        ],
        [
            'name' => ServiceNames::STOCK,
            'source' => [
                'factory' => Factories::STOCK,
                'system' => Systems::PEOPLEVOX,
            ],
            'destination' => [
                'factory' => Factories::INVENTORY_ADJUSTMENTS,
                'system' => Systems::DYNAMICS_NAV,
            ],
        ],
        [
            'name' => ServiceNames::ORDERS,
            'source' => [
                'factory' => Factories::SALES,
                'system' => Systems::VEND,
            ],
            'destination' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::LINNWORKS,
            ],
        ],
        [
            'name' => ServiceNames::PRODUCTS,
            'source' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::LINNWORKS,
            ],
            'destination' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::VEND,
            ],
        ],
        [
            'name' => ServiceNames::STOCK,
            'source' => [
                'factory' => Factories::STOCK,
                'system' => Systems::LINNWORKS,
            ],
            'destination' => [
                'factory' => Factories::STOCK,
                'system' => Systems::VEND,
            ],
        ],
        [
            'name' => ServiceNames::ORDERS,
            'source' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::BIGCOMMERCE
            ],
            'destination' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::PEOPLEVOX,
            ]
        ],
        [
            'name' => ServiceNames::ORDERS,
            'source' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::BIGCOMMERCE
            ],
            'destination' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::NETSUITE
            ]
        ],
        [
            'name' => ServiceNames::ORDERS,
            'source' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::BIGCOMMERCE
            ],
            'destination' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::DYNAMICS_NAV,
            ]
        ],
        [
            'name' => ServiceNames::CUSTOMERS,
            'source' => [
                'factory' => Factories::CUSTOMERS,
                'system' => Systems::BIGCOMMERCE
            ],
            'destination' => [
                'factory' => Factories::LEADS,
                'system' => Systems::NETSUITE
            ]
        ],
        [
            'name' => ServiceNames::RETURNS,
            'source' => [
                'factory' => Factories::REFUNDS,
                'system' => Systems::BIGCOMMERCE
            ],
            'destination' => [
                'factory' => Factories::RETURNS,
                'system' => Systems::NETSUITE
            ]
        ],
        [
            'name' => ServiceNames::PRODUCTS,
            'source' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::BIGCOMMERCE
            ],
            'destination' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::PEOPLEVOX,
            ]
        ],
        [
            'name' => ServiceNames::FULFILMENTS,
            'source' => [
                'factory' => Factories::FULFILMENTS,
                'system' => Systems::NETSUITE
            ],
            'destination' => [
                'factory' => Factories::FULFILMENTS,
                'system' => Systems::BIGCOMMERCE
            ]
        ],
        [
            'name' => ServiceNames::FULFILMENTS_SWEEPER,
            'source' => [
                'factory' => Factories::FULFILMENT_SWEEPER,
                'system' => Systems::PEOPLEVOX
            ],
            'destination' => [
                'factory' => Factories::FULFILMENTS,
                'system' => Systems::BIGCOMMERCE
            ]
        ],
        [
            'name' => ServiceNames::PRODUCTS,
            'source' => [
                'factory' => Factories::PRODUCTS_REFACTORED,
                'system' => Systems::NETSUITE
            ],
            'destination' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::BIGCOMMERCE
            ]
        ],
        [
            'name' => ServiceNames::PRODUCTS,
            'source' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::DYNAMICS_NAV,
            ],
            'destination' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::BIGCOMMERCE
            ]
        ],
        [
            'name' => ServiceNames::CUSTOMERS,
            'source' => [
                'factory' => Factories::CUSTOMERS,
                'system' => Systems::NETSUITE,
            ],
            'destination' => [
                'factory' => Factories::CUSTOMERS,
                'system' => Systems::BIGCOMMERCE
            ]
        ],
        [
            'name' => ServiceNames::STOCK,
            'source' => [
                'factory' => Factories::STOCK_REFACTORED,
                'system' => Systems::NETSUITE,
            ],
            'destination' => [
                'factory' => Factories::STOCK_LEVELS,
                'system' => Systems::BIGCOMMERCE
            ]
        ],
        [
            'name' => ServiceNames::STOCK,
            'source' => [
                'factory' => Factories::STOCK_LEVELS,
                'system' => Systems::DYNAMICS_NAV,
            ],
            'destination' => [
                'factory' => Factories::STOCK_LEVELS,
                'system' => Systems::BIGCOMMERCE
            ]
        ],
        [
            'name' => ServiceNames::STOCK,
            'source' => [
                'factory' => Factories::TIMESTAMP_STOCK,
                'system' => Systems::PEOPLEVOX,
            ],
            'destination' => [
                'factory' => Factories::STOCK_LEVELS,
                'system' => Systems::BIGCOMMERCE
            ]
        ],
        [
            'name' => ServiceNames::ORDERS,
            'source' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::MAGENTO_2
            ],
            'destination' => [
                'factory' => Factories::NE_ORDERS,
                'system' => Systems::DYNAMICS_NAV,
            ]
        ],
        [
            'name' => ServiceNames::ORDERS,
            'source' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::MAGENTO_2
            ],
            'destination' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::PEOPLEVOX,
            ]
        ],
        [
            'name' => ServiceNames::ORDERS,
            'source' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::MAGENTO_2
            ],
            'destination' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::NETSUITE
            ]
        ],
        [
            'name' => ServiceNames::PRODUCTS,
            'source' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::MAGENTO_2
            ],
            'destination' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::PEOPLEVOX,
            ]
        ],
        [
            'name' => ServiceNames::RETURNS,
            'source' => [
                'factory' => Factories::REFUNDS,
                'system' => Systems::MAGENTO_2
            ],
            'destination' => [
                'factory' => Factories::RETURNS,
                'system' => Systems::NETSUITE,
            ]
        ],
        [
            'name' => ServiceNames::CUSTOMERS,
            'source' => [
                'factory' => Factories::CUSTOMERS,
                'system' => Systems::MAGENTO_2
            ],
            'destination' => [
                'factory' => Factories::CUSTOMERS,
                'system' => Systems::NETSUITE,
            ]
        ],
        [
            'name' => ServiceNames::CUSTOMERS,
            'source' => [
                'factory' => Factories::CUSTOMERS,
                'system' => Systems::MAGENTO_2
            ],
            'destination' => [
                'factory' => Factories::NE_CUSTOMERS,
                'system' => Systems::DYNAMICS_NAV
            ]
        ],
        [
            'name' => ServiceNames::PRODUCTS,
            'source' => [
                'factory' => Factories::PRODUCTS_REFACTORED,
                'system' => Systems::NETSUITE
            ],
            'destination' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::MAGENTO_2
            ]
        ],
        [
            'name' => ServiceNames::PRODUCTS,
            'source' => [
                'factory' => Factories::NE_PRODUCTS,
                'system' => Systems::DYNAMICS_NAV
            ],
            'destination' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::MAGENTO_2
            ]
        ],
        [
            'name' => ServiceNames::FULFILMENTS,
            'source' => [
                'factory' => Factories::FULFILMENTS,
                'system' => Systems::NETSUITE,
            ],
            'destination' => [
                'factory' => Factories::FULFILMENTS,
                'system' => Systems::MAGENTO_2
            ]
        ],
        [
            'name' => ServiceNames::FULFILMENTS,
            'source' => [
                'factory' => Factories::FULFILMENT_SWEEPER,
                'system' => Systems::PEOPLEVOX,
            ],
            'destination' => [
                'factory' => Factories::FULFILMENTS,
                'system' => Systems::MAGENTO_2
            ]
        ],
        [
            'name' => ServiceNames::DESPATCH_PACKAGES,
            'source' => [
                'factory' => Factories::DESPATCH_PACKAGES,
                'system' => Systems::PEOPLEVOX,
            ],
            'destination' => [
                'factory' => Factories::FULFILMENTS,
                'system' => Systems::MAGENTO_2
            ]
        ],
        [
            'name' => ServiceNames::STOCK,
            'source' => [
                'factory' => Factories::STOCK_REFACTORED,
                'system' => Systems::NETSUITE,
            ],
            'destination' => [
                'factory' => Factories::STOCK_LEVELS,
                'system' => Systems::MAGENTO_2,
            ]
        ],
        [
            'name' => ServiceNames::STOCK,
            'source' => [
                'factory' => Factories::TIMESTAMP_STOCK,
                'system' => Systems::PEOPLEVOX
            ],
            'destination' => [
                'factory' => Factories::STOCK_LEVELS,
                'system' => Systems::MAGENTO_2
            ]
        ],
        [
            'name' => ServiceNames::STOCK,
            'source' => [
                'factory' => Factories::NE_STOCK_LEVELS,
                'system' => Systems::DYNAMICS_NAV
            ],
            'destination' => [
                'factory' => Factories::STOCK_LEVELS,
                'system' => Systems::MAGENTO_2
            ]
        ],
        [
            'name' => ServiceNames::RETURNS,
            'source' => [
                'factory' => Factories::RETURNS_SWEEPER,
                'system' => Systems::PEOPLEVOX,
            ],
            'destination' => [
                'factory' => Factories::RETURNS,
                'system' => Systems::MAGENTO_2
            ]
        ],
        [
            'name' => ServiceNames::CUSTOMERS,
            'source' => [
                'factory' => Factories::NE_CUSTOMERS,
                'system' => Systems::DYNAMICS_NAV
            ],
            'destination' => [
                'factory' => Factories::CUSTOMERS,
                'system' => Systems::MAGENTO_2
            ]
        ],
        [
            'name' => ServiceNames::PRODUCTS,
            'source' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::COMMERCETOOLS,
            ],
            'destination' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::PEOPLEVOX,
            ],
        ],
        [
            'name' => ServiceNames::ORDERS,
            'source' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::COMMERCETOOLS,
            ],
            'destination' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::PEOPLEVOX,
            ],
        ],
        [
            'name' => ServiceNames::ORDERS,
            'source' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::COMMERCETOOLS,
            ],
            'destination' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::NETSUITE,
            ],
        ],
        [
            'name' => ServiceNames::REFUNDS,
            'source' => [
                'factory' => Factories::REFUNDS,
                'system' => Systems::COMMERCETOOLS,
            ],
            'destination' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::NETSUITE,
            ],
        ],
        [
            'name' => ServiceNames::RETURNS,
            'source' => [
                'factory' => Factories::RETURNS,
                'system' => Systems::COMMERCETOOLS,
            ],
            'destination' => [
                'factory' => Factories::RETURNS,
                'system' => Systems::NETSUITE,
            ],
        ],
        [
            'name' => ServiceNames::STOCK,
            'source' => [
                'factory' => Factories::STOCK,
                'system' => Systems::PEOPLEVOX,
            ],
            'destination' => [
                'factory' => Factories::STOCK_LEVELS,
                'system' => Systems::COMMERCETOOLS,
            ],
        ],
        [
            'name' => ServiceNames::PRODUCTS,
            'source' => [
                'factory' => Factories::PRODUCTS_REFACTORED,
                'system' => Systems::NETSUITE,
            ],
            'destination' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::COMMERCETOOLS,
            ],
        ],
        [
            'name' => ServiceNames::PRODUCTS,
            'source' => [
                'factory' => Factories::PRODUCTS_REFACTORED,
                'system' => Systems::NETSUITE,
            ],
            'destination' => [
                'factory' => Factories::PRICES,
                'system' => Systems::COMMERCETOOLS,
            ],
        ],
        [
            'name' => ServiceNames::STOCK,
            'source' => [
                'factory' => Factories::STOCK_LEVELS,
                'system' => Systems::NETSUITE,
            ],
            'destination' => [
                'factory' => Factories::STOCK_LEVELS,
                'system' => Systems::COMMERCETOOLS,
            ],
        ],
        [
            'name' => ServiceNames::FULFILMENTS,
            'source' => [
                'factory' => Factories::FULFILMENTS,
                'system' => Systems::NETSUITE,
            ],
            'destination' => [
                'factory' => Factories::FULFILMENTS,
                'system' => Systems::COMMERCETOOLS,
            ],
        ],
        [
            'name' => ServiceNames::PRODUCTS,
            'source' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::DYNAMICS_NAV,
            ],
            'destination' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::WOOCOMMERCE,
            ],
        ],
        [
            'name' => ServiceNames::FULFILMENTS,
            'source' => [
                'factory' => Factories::FULFILMENTS,
                'system' => Systems::DYNAMICS_NAV,
            ],
            'destination' => [
                'factory' => Factories::FULFILMENTS,
                'system' => Systems::WOOCOMMERCE,
            ],
        ],
        [
            'name' => ServiceNames::ORDERS,
            'source' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::WOOCOMMERCE,
            ],
            'destination' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::DYNAMICS_NAV,
            ],
        ],
        [
            'name' => ServiceNames::CUSTOMERS,
            'source' => [
                'factory' => Factories::CUSTOMERS,
                'system' => Systems::WOOCOMMERCE,
            ],
            'destination' => [
                'factory' => Factories::CUSTOMERS,
                'system' => Systems::DYNAMICS_NAV,
            ],
        ],
        [
            'name' => ServiceNames::ORDERS,
            'source' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::WOOCOMMERCE,
            ],
            'destination' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::NETSUITE,
            ],
        ],
        [
            'name' => ServiceNames::FULFILMENTS,
            'source' => [
                'factory' => Factories::FULFILMENTS,
                'system' => Systems::NETSUITE,
            ],
            'destination' => [
                'factory' => Factories::FULFILMENTS,
                'system' => Systems::WOOCOMMERCE,
            ],
        ],
        [
            'name' => ServiceNames::PRODUCTS,
            'source' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::NETSUITE,
            ],
            'destination' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::WOOCOMMERCE,
            ],
        ],
        [
            'name' => ServiceNames::FULFILMENTS,
            'source' => [
                'factory' => Factories::FULFILMENTS,
                'system' => Systems::PEOPLEVOX,
            ],
            'destination' => [
                'factory' => Factories::FULFILMENTS,
                'system' => Systems::WOOCOMMERCE,
            ],
        ],
        [
            'name' => ServiceNames::PRODUCTS,
            'source' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::PEOPLEVOX,
            ],
            'destination' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::WOOCOMMERCE,
            ],
        ],
        [
            'name' => ServiceNames::ORDERS,
            'source' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::WOOCOMMERCE,
            ],
            'destination' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::PEOPLEVOX,
            ],
        ],
        [
            'name' => ServiceNames::ORDERS,
            'source' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::MIRAKL,
            ],
            'destination' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::NETSUITE,
            ]
        ],
        [
            'name' => ServiceNames::ORDERS,
            'source' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::MIRAKL,
            ],
            'destination' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::PEOPLEVOX,
            ]
        ],
        [
            'name' => ServiceNames::ORDERS,
            'source' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::MIRAKL,
            ],
            'destination' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::LINNWORKS,
            ]
        ],
        [
            'name' => ServiceNames::OFFERS,
            'source' => [
                'factory' => Factories::PRODUCTS_REFACTORED,
                'system' => Systems::NETSUITE,
            ],
            'destination' => [
                'factory' => Factories::OFFERS,
                'system' => Systems::MIRAKL,
            ]
        ],
        [
            'name' => ServiceNames::REFUNDS,
            'source' => [
                'factory' => Factories::CREDIT_MEMOS,
                'system' => Systems::NETSUITE,
            ],
            'destination' => [
                'factory' => Factories::REFUNDS,
                'system' => Systems::MIRAKL,
            ]
        ],
        [
            'name' => ServiceNames::REFUNDS,
            'source' => [
                'factory' => Factories::RETURNS_SWEEPER,
                'system' => Systems::PEOPLEVOX,
            ],
            'destination' => [
                'factory' => Factories::REFUNDS,
                'system' => Systems::MIRAKL,
            ]
        ],
        [
            'name' => ServiceNames::TRACKING,
            'source' => [
                'factory' => Factories::FULFILMENTS,
                'system' => Systems::NETSUITE,
            ],
            'destination' => [
                'factory' => Factories::ORDER_TRACKING,
                'system' => Systems::MIRAKL,
            ]
        ],
        [
            'name' => ServiceNames::FULFILMENTS,
            'source' => [
                'factory' => Factories::FULFILMENT_SWEEPER,
                'system' => Systems::PEOPLEVOX,
            ],
            'destination' => [
                'factory' => Factories::ORDER_TRACKING,
                'system' => Systems::MIRAKL,
            ]
        ],
        [
            'name' => ServiceNames::DESPATCH_PACKAGES,
            'source' => [
                'factory' => Factories::DESPATCH_PACKAGES,
                'system' => Systems::PEOPLEVOX,
            ],
            'destination' => [
                'factory' => Factories::ORDER_TRACKING,
                'system' => Systems::MIRAKL,
            ]
        ],
        [
            'name' => ServiceNames::ORDERS,
            'source' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::SHOPIFY,
            ],
            'destination' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::BRIGHTPEARL,
            ]
        ],
        [
            'name' => ServiceNames::PRODUCTS,
            'source' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::BRIGHTPEARL,
            ],
            'destination' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::PEOPLEVOX,
            ]
        ],
        [
            'name' => ServiceNames::PRODUCTS,
            'source' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::SHOPIFY,
            ],
            'destination' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::BRIGHTPEARL,
            ]
        ],
        [
            'name' => ServiceNames::PRODUCTS,
            'source' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::LINNWORKS,
            ],
            'destination' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::BRIGHTPEARL,
            ]
        ],
        [
            'name' => ServiceNames::FULFILMENTS,
            'source' => [
                'factory' => Factories::FULFILMENT_SWEEPER,
                'system' => Systems::PEOPLEVOX,
            ],
            'destination' => [
                'factory' => Factories::SHIPMENTS,
                'system' => Systems::BRIGHTPEARL,
            ]
        ],
        [
            'name' => ServiceNames::RETURNS,
            'source' => [
                'factory' => Factories::RETURNS_SWEEPER,
                'system' => Systems::PEOPLEVOX,
            ],
            'destination' => [
                'factory' => Factories::RETURNS,
                'system' => Systems::BRIGHTPEARL,
            ]
        ],
        [
            'name' => ServiceNames::CUSTOMERS,
            'source' => [
                'factory' => Factories::CUSTOMERS,
                'system' => Systems::SHOPIFY,
            ],
            'destination' => [
                'factory' => Factories::CUSTOMERS,
                'system' => Systems::BRIGHTPEARL,
            ]
        ],
        [
            'name' => ServiceNames::FULFILMENTS,
            'source' => [
                'factory' => Factories::SHIPPED_SALES,
                'system' => Systems::BRIGHTPEARL,
            ],
            'destination' => [
                'factory' => Factories::FULFILMENTS,
                'system' => Systems::SHOPIFY,
            ]
        ],
        [
            'name' => ServiceNames::FULFILMENTS,
            'source' => [
                'factory' => Factories::FULFILMENTS,
                'system' => Systems::KHAOS,
            ],
            'destination' => [
                'factory' => Factories::FULFILMENTS,
                'system' => Systems::SHOPIFY
            ]
        ],
        [
            'name' => ServiceNames::STOCK,
            'source' => [
                'factory' => Factories::STOCK_LEVELS,
                'system' => Systems::KHAOS,
            ],
            'destination' => [
                'factory' => Factories::STOCK_LEVELS,
                'system' => Systems::MAGENTO_2
            ]
        ],
        [
            'name' => ServiceNames::STOCK,
            'source' => [
                'factory' => Factories::STOCK_LEVELS,
                'system' => Systems::KHAOS,
            ],
            'destination' => [
                'factory' => Factories::STOCK_LEVELS,
                'system' => Systems::SHOPIFY
            ]
        ],
        [
            'name' => ServiceNames::ORDERS,
            'source' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::SHOPIFY,
            ],
            'destination' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::KHAOS
            ]
        ],
        [
            'name' => ServiceNames::FULFILMENTS,
            'source' => [
                'factory' => Factories::FULFILMENTS,
                'system' => Systems::KHAOS,
            ],
            'destination' => [
                'factory' => Factories::FULFILMENTS,
                'system' => Systems::MAGENTO_2
            ]
        ],
        [
            'name' => ServiceNames::PRODUCTS,
            'source' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::KHAOS,
            ],
            'destination' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::SHOPIFY
            ]
        ],
        [
            'name' => ServiceNames::PRODUCTS,
            'source' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::KHAOS,
            ],
            'destination' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::MAGENTO_2
            ]
        ],
        [
            'name' => ServiceNames::CUSTOMERS,
            'source' => [
                'factory' => Factories::CUSTOMERS,
                'system' => Systems::BIGCOMMERCE,
            ],
            'destination' => [
                'factory' => Factories::CUSTOMERS,
                'system' => Systems::EMARSYS,
            ],
        ],
        [
            'name' => ServiceNames::ORDERS,
            'source' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::BIGCOMMERCE,
            ],
            'destination' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::EMARSYS,
            ],
        ],
        [
            'name' => ServiceNames::PRODUCTS,
            'source' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::BIGCOMMERCE,
            ],
            'destination' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::EMARSYS,
            ],
        ],
        [
            'name' => ServiceNames::CUSTOMERS,
            'source' => [
                'factory' => Factories::CUSTOMERS,
                'system' => Systems::NETSUITE,
            ],
            'destination' => [
                'factory' => Factories::CUSTOMERS,
                'system' => Systems::EMARSYS,
            ],
        ],
        [
            'name' => ServiceNames::ORDERS,
            'source' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::NETSUITE,
            ],
            'destination' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::EMARSYS,
            ],
        ],
        [
            'name' => ServiceNames::PRODUCTS,
            'source' => [
                'factory' => Factories::PRODUCTS_REFACTORED,
                'system' => Systems::NETSUITE,
            ],
            'destination' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::EMARSYS,
            ],
        ],
        [
            'name' => ServiceNames::STOCK,
            'source' => [
                'factory' => Factories::STOCK_LEVELS,
                'system' => Systems::NETSUITE,
            ],
            'destination' => [
                'factory' => Factories::STOCK_LEVELS,
                'system' => Systems::VISUALSOFT,
            ],
        ],
        [
            'name' => ServiceNames::FULFILMENTS,
            'source' => [
                'factory' => Factories::FULFILMENTS,
                'system' => Systems::NETSUITE,
            ],
            'destination' => [
                'factory' => Factories::ORDER_STATUSES,
                'system' => Systems::VISUALSOFT,
            ],
        ],
        [
            'name' => ServiceNames::REFUNDS,
            'source' => [
                'factory' => Factories::CREDIT_MEMOS,
                'system' => Systems::NETSUITE,
            ],
            'destination' => [
                'factory' => Factories::ORDER_STATUSES,
                'system' => Systems::VISUALSOFT,
            ],
        ],
        [
            'name' => ServiceNames::PRODUCTS,
            'source' => [
                'factory' => Factories::PRODUCTS_REFACTORED,
                'system' => Systems::NETSUITE,
            ],
            'destination' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::VISUALSOFT,
            ],
        ],
        [
            'name' => ServiceNames::ORDERS,
            'source' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::VISUALSOFT,
            ],
            'destination' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::NETSUITE,
            ],
        ],
        [
            'name' => ServiceNames::CUSTOMERS,
            'source' => [
                'factory' => Factories::CUSTOMERS,
                'system' => Systems::VISUALSOFT,
            ],
            'destination' => [
                'factory' => Factories::RECORD_UPDATES_MR,
                'system' => Systems::NETSUITE,
            ],
        ],
        [
            'name' => ServiceNames::PRODUCTS,
            'source' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::VEEQO,
            ],
            'destination' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::EPOSNOW,
            ],
        ],
        [
            'name' => ServiceNames::ORDERS,
            'source' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::EPOSNOW,
            ],
            'destination' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::VEEQO,
            ],
        ],
        [
            'name' => ServiceNames::STOCK,
            'source' => [
                'factory' => Factories::STOCK_LEVELS,
                'system' => Systems::EPOSNOW,
            ],
            'destination' => [
                'factory' => Factories::STOCK_LEVELS,
                'system' => Systems::VEEQO,
            ],
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        foreach (self::SERVICE_TEMPLATES as $serviceTemplate) {
            [$sourceFactorySystem, $destinationFactorySystem] = $this->getData($serviceTemplate);

            if (!$sourceFactorySystem || !$destinationFactorySystem) {
                continue;
            }

            ServiceTemplate::updateOrCreate(
                [
                    'source_factory_system_id' => $sourceFactorySystem->id,
                    'destination_factory_system_id' => $destinationFactorySystem->id
                ],
                [
                    'name' => $serviceTemplate['name'],
                    'source_factory_system_id' => $sourceFactorySystem->id,
                    'destination_factory_system_id' => $destinationFactorySystem->id
                ]
            );
        }
    }

    protected function getData(array $serviceTemplate): array
    {
        $sourceFactorySystem = FactorySystem::firstWhere([
            'direction' => 'pull',
            'factory_id' => Factory::firstWhere('name', Arr::get($serviceTemplate, 'source.factory'))->id,
            'system_id' => System::firstWhere('name', Arr::get($serviceTemplate, 'source.system'))->id
        ]);

        $destinationFactorySystem = FactorySystem::firstWhere([
            'direction' => 'push',
            'factory_id' => Factory::firstWhere('name', Arr::get($serviceTemplate, 'destination.factory'))->id,
            'system_id' => System::firstWhere('name', Arr::get($serviceTemplate, 'destination.system'))->id
        ]);

        return [
            $sourceFactorySystem,
            $destinationFactorySystem,
        ];
    }
}
