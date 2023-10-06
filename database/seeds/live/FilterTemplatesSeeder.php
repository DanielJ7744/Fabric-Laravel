<?php

use App\Enums\Factories;
use App\Enums\Systems;
use App\Models\Fabric\Factory;
use App\Models\Fabric\FactorySystem;
use App\Models\Fabric\FilterTemplate;
use App\Models\Fabric\System;
use Illuminate\Database\Seeder;

class FilterTemplatesSeeder extends Seeder
{
    private const FILTER_TEMPLATES = [
        Systems::SHOPIFY => [
            Factories::CUSTOMERS => [
                'name' => 'Shopify Customers',
                'filter_key' => 'id',
                'template' => '{"id":"%s"}',
                'pw_value_field' => 'id',
                'note' => null,
            ],
            Factories::ORDERS => [
                'name' => 'Shopify Orders',
                'filter_key' => 'id',
                'template' => '{"id":"%s"}',
                'pw_value_field' => 'id',
                'note' => null,
            ],
            Factories::ORDERS_REBOUND => [
                'name' => 'Shopify Orders Rebound',
                'filter_key' => 'id',
                'template' => '{"id":"%s"}',
                'pw_value_field' => 'id',
                'note' => null,
            ],
            Factories::PRODUCTS => [
                'name' => 'Shopify Products',
                'filter_key' => 'id',
                'template' => '{"id":"%s"}',
                'pw_value_field' => 'id',
                'note' => null,
            ],
            Factories::REFUNDS => [
                'name' => 'Shopify Refunds',
                'filter_key' => 'id',
                'template' => '{"id":"%s"}',
                'pw_value_field' => 'id',
                'note' => 'The IDs supplied should be of the orders the refunds exist against',
            ],
        ],
        Systems::DYNAMICS_NAV => [
            Factories::CUSTOMERS => [
                'name' => 'Dynamics Nav Customers',
                'filter_key' => 'No',
                'template' => '{"No":"%s"}',
                'pw_value_field' => 'No',
                'note' => NULL,
            ],
            Factories::NE_CONTACTS => [
                'name' => 'Dynamics Nav NE Contacts',
                'filter_key' => 'No',
                'template' => '{"No":"%s"}',
                'pw_value_field' => 'No',
                'note' => NULL,
            ],
            Factories::NE_CUSTOMERS => [
                'name' => 'Dynamics Nav NE Customers',
                'filter_key' => 'No',
                'template' => '{"No":"%s"}',
                'pw_value_field' => 'No',
                'note' => NULL,
            ],
            Factories::FULFILMENTS => [
                'name' => 'Dynamics Nav Fulfilments',
                'filter_key' => 'No',
                'template' => '{"No":"%s"}',
                'pw_value_field' => 'No',
                'note' => NULL,
            ],
            Factories::PURCHASE_ORDERS => [
                'name' => 'Dynamics Nav Purchase Orders',
                'filter_key' => 'No',
                'template' => '{"No":"%s"}',
                'pw_value_field' => 'No',
                'note' => NULL,
            ],
            Factories::NE_STOCK_LEVELS => [
                'name' => 'Dynamics Nav NE Stock',
                'filter_key' => 'No',
                'template' => '{"No":"%s"}',
                'pw_value_field' => 'No',
                'note' => NULL,
            ],
            Factories::STOCK_LEVELS => [
                'name' => 'Dynamics Nav Stock',
                'filter_key' => 'No',
                'template' => '{"No":"%s"}',
                'pw_value_field' => 'No',
                'note' => NULL,
            ],
        ],
        Systems::NETSUITE => [
            Factories::CUSTOMERS => [
                'name' => 'NetSuite Customers',
                'filter_key' => 'internalid',
                'template' => '{"internalid anyof":"[%s]"}',
                'pw_value_field' => 'id',
                'note' => NULL,
            ],
            Factories::ITEM_RECEIPTS => [
                'name' => 'NetSuite Receipts',
                'filter_key' => 'internalid',
                'template' => '{"internalid anyof":"[%s]"}',
                'pw_value_field' => 'internalid',
                'note' => NULL,
            ],
            Factories::STOCK_REFACTORED => [
                'name' => 'NetSuite Stock',
                'filter_key' => 'internalid',
                'template' => '{"internalid anyof":"[%s]"}',
                'pw_value_field' => 'internalid',
                'note' => NULL,
            ],
            Factories::STOCK_LEVELS => [
                'name' => 'NetSuite Stock',
                'filter_key' => 'internalid',
                'template' => '{"internalid anyof":"[%s]"}',
                'pw_value_field' => 'internalid',
                'note' => NULL,
            ],
            Factories::ORDERS => [
                'name' => 'NetSuite Orders',
                'filter_key' => 'internalid',
                'template' => '{"internalid anyof":"[%s]"}',
                'pw_value_field' => 'id',
                'note' => NULL,
            ],
            Factories::RETURNS => [
                'name' => 'NetSuite Returns',
                'filter_key' => 'internalid',
                'template' => '{"internalid anyof":"[%s]"}',
                'pw_value_field' => 'id',
                'note' => NULL,
            ],
            Factories::INVOICES => [
                'name' => 'NetSuite Invoices',
                'filter_key' => 'internalid',
                'template' => '{"internalid anyof":"[%s]"}',
                'pw_value_field' => 'id',
                'note' => NULL,
            ],
            Factories::PRODUCTS_REFACTORED => [
                'name' => 'NetSuite Products',
                'filter_key' => 'itemid',
                'template' => '{"key":"itemid=%s=startswith"}',
                'pw_value_field' => 'itemid',
                'note' => NULL,
            ],
            Factories::PRICES => [
                'name' => 'NetSuite Products',
                'filter_key' => 'itemid',
                'template' => '{"key":"itemid=%s=startswith"}',
                'pw_value_field' => 'itemid',
                'note' => NULL,
            ],
            Factories::KITS => [
                'name' => 'NetSuite Products',
                'filter_key' => 'itemid',
                'template' => '{"key":"itemid=%s=startswith"}',
                'pw_value_field' => 'itemid',
                'note' => NULL,
            ],
            Factories::PURCHASE_ORDERS => [
                'name' => 'NetSuite Purchase Orders',
                'filter_key' => 'internalid',
                'template' => '{"internalid anyof":"[%s]"}',
                'pw_value_field' => 'id',
                'note' => NULL,
            ],
        ],
        Systems::PEOPLEVOX => [
            Factories::STOCK => [
                'name' => 'PeopleVox Stock',
                'filter_key' => 'Item+code',
                'template' => '{"Item+code":"%s"}',
                'pw_value_field' => 'Item+code',
                'note' => NULL,
            ],
            Factories::STOCK_CSV => [
                'name' => 'PeopleVox Stock',
                'filter_key' => 'Item+code',
                'template' => '{"Item+code":"%s"}',
                'pw_value_field' => 'Item+code',
                'note' => NULL,
            ],
            Factories::TIMESTAMP_STOCK => [
                'name' => 'PeopleVox Stock',
                'filter_key' => 'Item+code',
                'template' => '{"Item+code":"%s"}',
                'pw_value_field' => 'Item+code',
                'note' => NULL,
            ],
            Factories::KIT_STOCK => [
                'name' => 'PeopleVox Stock',
                'filter_key' => 'Item+code',
                'template' => '{"Item+code":"%s"}',
                'pw_value_field' => 'Item+code',
                'note' => NULL,
            ],
            Factories::DESPATCH_PACKAGES => [
                'name' => 'PeopleVox Fulfilments',
                'filter_key' => 'Despatch+number',
                'template' => '{"Despatch+number":"%s"}',
                'pw_value_field' => 'Despatch+number',
                'note' => NULL,
            ],
            Factories::EVENT_FULFILMENT => [
                'name' => 'PeopleVox Fulfilments',
                'filter_key' => 'Despatch+number',
                'template' => '{"Despatch+number":"%s"}',
                'pw_value_field' => 'Despatch+number',
                'note' => NULL,
            ],
            Factories::EVENT_TRACKING_NO => [
                'name' => 'PeopleVox Fulfilments',
                'filter_key' => 'Despatch+number',
                'template' => '{"Despatch+number":"%s"}',
                'pw_value_field' => 'Despatch+number',
                'note' => NULL,
            ],
            Factories::FULFILMENT => [
                'name' => 'PeopleVox Fulfilments',
                'filter_key' => 'Despatch+number',
                'template' => '{"Despatch+number":"%s"}',
                'pw_value_field' => 'Despatch+number',
                'note' => NULL,
            ],
            Factories::FULFILMENT_SWEEPER => [
                'name' => 'PeopleVox Fulfilments',
                'filter_key' => 'Despatch+number',
                'template' => '{"Despatch+number":"%s"}',
                'pw_value_field' => 'Despatch+number',
                'note' => NULL,
            ],
            Factories::REVERSE_FULFILMENT_SWEEPER => [
                'name' => 'PeopleVox Fulfilments',
                'filter_key' => 'Despatch+number',
                'template' => '{"Despatch+number":"%s"}',
                'pw_value_field' => 'Despatch+number',
                'note' => NULL,
            ],
        ],
        Systems::KHAOS => [
            Factories::STOCK_LEVELS => [
                'name' => 'Khaos Stock',
                'filter_key' => 'STOCK_CODE',
                'template' => '{"STOCK_CODE":"%s"}',
                'pw_value_field' => 'STOCK_CODE',
                'note' => 'The SKU supplied should be the CHILD SKU not the parent SKU',
            ],
            Factories::PRICES => [
                'name' => 'Khaos Products',
                'filter_key' => 'STOCK_CODE',
                'template' => '{"STOCK_CODE":"%s"}',
                'pw_value_field' => 'STOCK_CODE',
                'note' => 'The SKU supplied should be the PARENT SKU not the child SKU',
            ],
            Factories::PRODUCTS => [
                'name' => 'Khaos Products',
                'filter_key' => 'STOCK_CODE',
                'template' => '{"STOCK_CODE":"%s"}',
                'pw_value_field' => 'STOCK_CODE',
                'note' => 'The SKU supplied should be the PARENT SKU not the child SKU',
            ],
        ],
        Systems::MAGENTO_2 => [
            Factories::ORDERS => [
                'name' => 'Magento 2 Orders',
                'filter_key' => 'increment_id',
                'template' => '{"increment_id":"%s"}',
                'pw_value_field' => 'increment_id',
                'note' => NULL,
            ],
            Factories::PRODUCTS => [
                'name' => 'Magento 2 Products',
                'filter_key' => 'sku',
                'template' => '{"sku":"%s"}',
                'pw_value_field' => 'sku',
                'note' => NULL,
            ]
        ],
        Systems::LINNWORKS => [
            Factories::PRODUCTS => [
                'name' => 'Linnworks Products',
                'filter_key' => 'sku',
                'template' => '{"sku":"%s"}',
                'pw_value_field' => 'sku',
                'note' => NULL,
            ],
            Factories::STOCK => [
                'name' => 'Linnworks Stock',
                'filter_key' => 'sku',
                'template' => '{"sku":"%s"}',
                'pw_value_field' => 'sku',
                'note' => NULL,
            ],
        ],
        Systems::VEND => [
            Factories::SALES => [
                'name' => 'Vend Sales',
                'filter_key' => 'invoice_number',
                'template' => '{"invoice_number": "%s","type":"sales"}',
                'pw_value_field' => 'invoice_number',
                'note' => NULL,
            ],
            Factories::PRODUCTS => [
                'name' => 'Vend Products',
                'filter_key' => 'sku',
                'template' => '	{"sku": "%s","type":"products"}',
                'pw_value_field' => 'sku',
                'note' => NULL,
            ],
        ],
        Systems::COMMERCETOOLS => [
            Factories::ORDERS => [
                'name' => 'CommerceTools Orders',
                'filter_key' => 'order_number',
                'template' => '{"order_number":"%s"}',
                'pw_value_field' => 'order_number',
                'note' => NULL,
            ],
            Factories::PRODUCTS => [
                'name' => 'CommerceTools Products',
                'filter_key' => 'key',
                'template' => '{"key":"%s"}',
                'pw_value_field' => 'key',
                'note' => NULL,
            ],
            Factories::INVENTORY_ITEMS => [
                'name' => 'CommerceTools Inventory Items',
                'filter_key' => 'key',
                'template' => '{"key":"%s"}',
                'pw_value_field' => 'key',
                'note' => NULL,
            ],
        ],
        Systems::MIRAKL => [
            Factories::ORDERS => [
                'name' => 'Mirakl Orders',
                'filter_key' => 'order_ids',
                'template' => '{"order_ids":"%s"}',
                'pw_value_field' => 'order_ids'
            ]
        ],
        Systems::WOOCOMMERCE => [
            Factories::ORDERS => [
                'name' => 'Woocommerce Orders',
                'filter_key' => 'order_key',
                'template' => '{"order_key":"%s"}',
                'pw_value_field' => 'order_key',
                'note' => NULL,
            ],
            Factories::PRODUCTS => [
                'name' => 'Woocommerce Products',
                'filter_key' => 'slug',
                'template' => '{"slug":"%s"}',
                'pw_value_field' => 'slug',
                'note' => NULL,
            ]
        ],
        Systems::BRIGHTPEARL => [
            Factories::ORDERS => [
                'name' => 'Brightpearl Orders',
                'filter_key' => 'customerRef',
                'template' => '{"customerRef":"%s"}',
                'pw_value_field' => 'customerRef',
                'note' => NULL,
            ],
            Factories::PRODUCTS => [
                'name' => 'Brightpearl Products',
                'filter_key' => 'SKU',
                'template' => '{"SKU":"%s"}',
                'pw_value_field' => 'SKU',
                'note' => NULL,
            ],
            Factories::STOCK => [
                'name' => 'Brightpearl Stock',
                'filter_key' => 'SKU',
                'template' => '{"SKU":"%s"}',
                'pw_value_field' => 'SKU',
                'note' => NULL,
            ],
        ],
        Systems::BIGCOMMERCE => [
            Factories::CUSTOMERS => [
                'name' => 'Bigcommerce Customers',
                'filter_key' => 'id:in',
                'template' => '{"id:in":"%s"}',
                'pw_value_field' => 'id:in',
                'note' => NULL
            ],
        ],
        Systems::VISUALSOFT => [
            Factories::ORDERS => [
                'name' => 'VisualSoft Orders',
                'filter_key' => 'order_ref',
                'template' => '{"order_ref":"%s"}',
                'pw_value_field' => 'order_ref',
                'note' => NULL
            ],
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (self::FILTER_TEMPLATES as $system => $factories) {
            $system = System::firstWhere('name', $system);
            if (is_null($system)) {
                continue;
            }
            foreach ($factories as $factory => $filterTemplate) {
                $factory = Factory::firstWhere('name', $factory);
                if (is_null($factory)) {
                    continue;
                }

                $factorySystem = FactorySystem::firstWhere([
                    'factory_id' => $factory->id,
                    'system_id' => $system->id
                ]);
                if (is_null($factorySystem)) {
                    continue;
                }

                $filterTemplate['factory_system_id'] = $factorySystem->id;

                FilterTemplate::updateOrCreate(
                    [
                        'factory_system_id' => $filterTemplate['factory_system_id'],
                        'filter_key' => $filterTemplate['filter_key']
                    ],
                    $filterTemplate
                );
            }
        }
    }
}
