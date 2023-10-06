<?php

use App\Enums\Factories;
use App\Enums\Systems;
use App\Models\Fabric\Factory;
use App\Models\Fabric\FactorySystem;
use App\Models\Fabric\FilterField;
use App\Models\Fabric\FilterType;
use App\Models\Fabric\System;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FilterFieldFilterTypeSeeder extends Seeder
{
    private const FILTER_TYPES = [
        'netsuite_string' => [
            Systems::NETSUITE => [
                Factories::RETURNS => [
                    'status'
                ],
                Factories::CREDIT_MEMOS => [
                    'status'
                ],
                Factories::CUSTOMERS => [
                    'status'
                ],
                Factories::FULFILMENTS => [
                    'status'
                ],
                Factories::INVOICES => [
                    'status'
                ],
                Factories::ITEM_RECEIPTS => [
                    'status'
                ],
                Factories::KITS => [
                    'status'
                ],
                Factories::PRICES => [
                    'status'
                ],
                Factories::PRODUCTS_REFACTORED => [
                    'status'
                ],
                Factories::PURCHASE_ORDERS => [
                    'status'
                ],
                Factories::QUOTES => [
                    'status'
                ],
                Factories::REFUNDS => [
                    'status'
                ],
                Factories::TRANSFER_ORDERS => [
                    'status'
                ],
                Factories::WORK_ORDERS => [
                    'status'
                ],
                Factories::INBOUND_SHIPMENTS => [
                    'status'
                ],
                Factories::ORDERS => [
                    'status', 'tranid', 'department',
                    'class', 'entity', 'memorized',
                    'itemid'
                ]
            ]
        ],
        'string' => [
            Systems::SHOPIFY => [
                Factories::ORDERS => [
                    'attribution_app_id', 'financial_status', 'fulfillment_status', 'status', 'order_id'
                ]
            ],
            Systems::VEND => [
                'Sales' => [
                    'type', 'status'
                ]
            ],
            Systems::BIGCOMMERCE => [
                Factories::ORDERS => [
                    'cart_id', 'email', 'payment_method',
                    'sort'
                ],
                Factories::PRODUCTS => [
                    'store_hash', 'availability', 'condition',
                    'direction', 'exclude_fields', 'include',
                    'include_fields', 'is_featured', 'is_visible',
                    'keyword', 'keyword_context', 'name',
                    'sku', 'sort', 'type', 'upc'
                ],
                Factories::CUSTOMERS => [
                    'id:in'
                ],
            ],
            Systems::MAGENTO_2 => [
                Factories::ORDERS => [
                    'status'
                ]
            ],
            Systems::KHAOS => [
                Factories::FULFILMENTS => [
                    'ShowItems', 'WebOnly'
                ]
            ],
            Systems::PEOPLEVOX => [
                Factories::PRODUCTS => [
                    'Item+code', 'Item+name', 'Item+barcode'
                ],
                Factories::RETURNS => [
                    'Return+code', 'Item+name', 'Sales+order+number', 'Return+reason', 'Return+condition', 'Return+comments', 'Item+barcode', 'Channel+name', 'Site+reference', 'Item+code'
                ],
                Factories::RETURNS_SWEEPER => [
                    'Return+code', 'Item+name', 'Sales+order+number', 'Return+reason', 'Return+condition', 'Return+comments', 'Item+barcode', 'Channel+name', 'Site+reference', 'Item+code'
                ],
                Factories::FULFILMENT_SWEEPER => [
                    'Sales+order+number', 'Salesorder+number', 'Despatch+number', 'Site+reference'
                ]
            ],
            Systems::COMMERCETOOLS => [
                Factories::ORDERS => [
                    'order_number', 'state', 'orderState'
                ],
                Factories::PRODUCTS => [
                    'key'
                ]
            ],
            Systems::MIRAKL => [
                Factories::ORDERS => [
                    'order_state_codes'
                ]
            ],
            Systems::WOOCOMMERCE => [
                Factories::ORDERS => [
                    'status', 'order_key'
                ],
                Factories::PRODUCTS => [
                    'type'
                ]
            ],
            Systems::BRIGHTPEARL => [
                Factories::FULFILLED_SALES => [
                    'orderStockStatusId'
                ],
                Factories::ORDERS => [
                    'customerRef', 'orderPaymentStatus', 'orderShippingStatus', 'externalRef'
                ],
                Factories::PRODUCTS => [
                    'SKU', 'barcode', 'productStatus', 'productName'
                ],
                Factories::STOCK => [
                    'SKU', 'barcode', 'productStatus', 'productName'
                ]
            ],
        ],
        'TIMESTAMP' => [
            Systems::PEOPLEVOX => [
                Factories::TIMESTAMP_STOCK => [
                    'Timestamp'
                ],
            ],
        ],
        'netsuite_time' => [
            Systems::NETSUITE => [
                Factories::ASSEMBLY_BUILDS => [
                    'lastmodifieddate'
                ],
                Factories::RETURNS => [
                    'lastmodifieddate', 'trandate', 'datecreated'
                ],
                Factories::CREDIT_MEMOS => [
                    'lastmodifieddate', 'trandate', 'datecreated'
                ],
                Factories::CUSTOMERS => [
                    'lastmodifieddate', 'trandate', 'datecreated'
                ],
                Factories::FULFILMENTS => [
                    'lastmodifieddate', 'trandate', 'datecreated'
                ],
                Factories::INVOICES => [
                    'lastmodifieddate', 'trandate', 'datecreated'
                ],
                Factories::ITEM_RECEIPTS => [
                    'lastmodifieddate', 'trandate', 'datecreated'
                ],
                Factories::KITS => [
                    'lastmodifieddate', 'trandate', 'datecreated'
                ],
                Factories::PRICES => [
                    'lastmodifieddate', 'trandate', 'datecreated'
                ],
                Factories::PRODUCTS_REFACTORED => [
                    'lastmodifieddate', 'trandate', 'datecreated'
                ],
                Factories::PURCHASE_ORDERS => [
                    'lastmodifieddate', 'trandate', 'datecreated'
                ],
                Factories::QUOTES => [
                    'lastmodifieddate', 'trandate', 'datecreated'
                ],
                Factories::REFUNDS => [
                    'lastmodifieddate', 'trandate', 'datecreated'
                ],
                Factories::TRANSFER_ORDERS => [
                    'lastmodifieddate', 'trandate', 'datecreated'
                ],
                Factories::WORK_ORDERS => [
                    'lastmodifieddate', 'trandate', 'datecreated'
                ],
                Factories::INBOUND_SHIPMENTS => [
                    'createddate'
                ],
                Factories::ORDERS => [
                    'lastmodified', 'trandate', 'datecreated',
                    'lastmodifieddate'
                ],
            ],
        ],
        'TIME' => [
            Systems::SHOPIFY => [
                Factories::ORDERS => [
                    'created_at_max', 'created_at_min', 'processed_at_max',
                    'processed_at_min', 'updated_at_max', 'updated_at_min'
                ],
                Factories::PRODUCTS => [
                    'created_at_max', 'created_at_min', 'processed_at_max',
                    'processed_at_min', 'updated_at_max', 'updated_at_min'
                ],
                Factories::CUSTOMERS => [
                    'created_at_max', 'created_at_min', 'processed_at_max',
                    'processed_at_min', 'updated_at_max', 'updated_at_min'
                ]
            ],
            Systems::KHAOS => [
                Factories::FULFILMENTS => [
                    'DateFrom', 'DateTo'
                ],
                Factories::STOCK_LEVELS => [
                    'DateValue'
                ],
                Factories::PRODUCTS => [
                    'DateValue'
                ]
            ],
            Systems::VEND => [
                Factories::SALES => [
                    'date_from'
                ]
            ],
            Systems::BIGCOMMERCE => [
                Factories::ORDERS => [
                    'min_date_created', 'max_date_created', 'min_date_modified',
                    'max_date_modified'
                ],
                Factories::REFUNDS => [
                    'created:max', 'created:min'
                ],
                Factories::PRODUCTS => [
                    'date_last_imported', 'date_last_imported:max', 'date_last_imported:min',
                    'date_modified', 'date_modified:max', 'date_modified:min'
                ],
                Factories::CUSTOMERS => [
                    'date_created:min'
                ],
            ],
            Systems::MAGENTO_2 => [
                Factories::ORDERS => [
                    'created_at', 'updated_at'
                ],
                Factories::CUSTOMERS => [
                    'created_at', 'updated_at'
                ],
                Factories::PRODUCTS => [
                    'created_at', 'updated_at'
                ],
                Factories::REFUNDS => [
                    'created_at', 'updated_at'
                ]
            ],
            Systems::PEOPLEVOX => [
                Factories::RETURNS => [
                    'Return+date', 'Despatch+date', 'Days+since+despatch'
                ],
                Factories::RETURNS_SWEEPER => [
                    'Return+date', 'Despatch+date', 'Days+since+despatch'
                ],
                Factories::ORDERS => [
                    'Date'
                ],
                Factories::ORDERS_REFACTORED => [
                    'Date'
                ],
                Factories::FULFILMENT_SWEEPER => [
                    'Despatch+date'
                ],
                Factories::REVERSE_FULFILMENT_SWEEPER => [
                    'Despatch+date'
                ],
                Factories::FULFILMENT => [
                    'Despatch+date'
                ],
                Factories::EVENT_FULFILMENT => [
                    'Despatch+date'
                ],
                Factories::DESPATCH_PACKAGES => [
                    'Despatch+date'
                ],
                Factories::EVENT_TRACKING_NO => [
                    'Despatch+date'
                ],
                Factories::ITEM_RECEIPTS => [
                    'Delivery+date'
                ],
                Factories::TIMESTAMP_STOCK => [
                    'Timestamp'
                ]
            ],
            Systems::COMMERCETOOLS => [
                Factories::ORDERS => [
                    'lastModifiedAt'
                ],
                Factories::PRODUCTS => [
                    'lastModifiedAt'
                ]
            ],
            Systems::MIRAKL => [
                Factories::ORDERS => [
                    'start_date'
                ]
            ],
            Systems::BRIGHTPEARL => [
                Factories::GOODS_RECEIPTS => [
                    'createdDate', 'receivedDate'
                ],
                Factories::ORDERS => [
                    'createdOn', 'updatedOn', 'deliveryDate'
                ],
                Factories::TRANSFER_ORDERS => [
                    'createdOn', 'updatedOn', 'deliveryDate'
                ],
                Factories::PURCHASE_ORDERS => [
                    'createdOn', 'updatedOn', 'deliveryDate'
                ],
                Factories::PRODUCTS => [
                    'createdOn', 'updatedOn'
                ],
                Factories::STOCK => [
                    'createdOn', 'updatedOn'
                ],
                Factories::SUPPLIERS => [
                    'createdOn', 'updatedOn'
                ]
            ],
            Systems::WOOCOMMERCE => [
                Factories::ORDERS => [
                    'date_created', 'date_modified'
                ],
                Factories::PRODUCTS => [
                    'date_created', 'date_modified'
                ],
                Factories::REFUNDS => [
                    'date_created', 'date_modified'
                ]
            ],
            Systems::VEEQO => [
                Factories::PRODUCTS => [
                    'created_at_min'
                ]
            ],
            Systems::EPOSNOW => [
                Factories::ORDERS => [
                    'startDate', 'endDate'
                ]
            ],
            Systems::VISUALSOFT => [
                Factories::ORDERS => [
                    'from_date'
                ],
                Factories::CUSTOMERS => [
                    'timestamp'
                ]
            ],
        ],
        'csv' => [
            Systems::SHOPIFY => [
                Factories::ORDERS => [
                    'fields', 'ids'
                ]
            ],
            Systems::BIGCOMMERCE => [
                Factories::REFUNDS => [
                    'id:in', 'order_id:in'
                ],
                Factories::PRODUCTS => [
                    'id:greater', 'id:in', 'id:less',
                    'id:max', 'id:min', 'id:not_in'
                ]
            ],
            Systems::KHAOS => [
                Factories::FULFILMENTS => [
                    'InvoiceStageIDs'
                ]
            ],
            Systems::MIRAKL => [
                Factories::ORDERS => [
                    'order_ids'
                ]
            ]
        ],
        'netsuite_numeric' => [
            Systems::NETSUITE => [
                Factories::RETURNS => [
                    'internalid'
                ],
                Factories::CREDIT_MEMOS => [
                    'internalid'
                ],
                Factories::CUSTOMERS => [
                    'internalid'
                ],
                Factories::FULFILMENTS => [
                    'internalid'
                ],
                Factories::INVOICES => [
                    'internalid'
                ],
                Factories::ITEM_RECEIPTS => [
                    'internalid'
                ],
                Factories::KITS => [
                    'internalid'
                ],
                Factories::PRICES => [
                    'internalid'
                ],
                Factories::PRODUCTS_REFACTORED => [
                    'internalid'
                ],
                Factories::PURCHASE_ORDERS => [
                    'internalid'
                ],
                Factories::QUOTES => [
                    'internalid'
                ],
                Factories::REFUNDS => [
                    'internalid'
                ],
                Factories::TRANSFER_ORDERS => [
                    'internalid'
                ],
                Factories::WORK_ORDERS => [
                    'internalid'
                ],
                Factories::ORDERS => [
                    'internalid'
                ]
            ],
        ],
        'integer' => [
            Systems::MIRAKL => [
                Factories::ORDERS => [
                    'since_id'
                ]
            ],
            Systems::KHAOS => [
                Factories::FULFILMENTS => [
                    'MappingType'
                ]
            ],
            Systems::BIGCOMMERCE => [
                Factories::ORDERS => [
                    'channel_id', 'customer_id', 'limit',
                    'max_id', 'max_total', 'min_id',
                    'min_total', 'page', 'status_id'
                ],
                Factories::REFUNDS => [
                    'limit', 'page'
                ],
                Factories::PRODUCTS => [
                    'brand_id', 'categories', 'categories:in',
                    'id', 'inventory_level', 'inventory_level:greater',
                    'inventory_level:in', 'inventory_level:less', 'inventory_level:max',
                    'inventory_level:min', 'inventory_level:not_in', 'inventory_low',
                    'is_free_shipping', 'limit', 'out_of_stock',
                    'page', 'price', 'status',
                    'total_sold', 'weight'
                ]
            ],
            Systems::MAGENTO_2 => [
                Factories::ORDERS => [
                    'store_id', 'customer_group_id'
                ],
                Factories::PRODUCTS => [
                    'type_id'
                ],
                Factories::REFUNDS => [
                    'store_id'
                ]
            ],
            Systems::BRIGHTPEARL => [
                Factories::GOODS_RECEIPTS => [
                    'PurchaseOrderId', 'ProductID', 'WarehouseId'
                ],
                Factories::ORDERS => [
                    'orderStatusId', 'salesOrderId'
                ],
                Factories::PRODUCTS => [
                    'productId', 'brandId', 'productTypeId', 'primarySupplierId'
                ],
                Factories::STOCK => [
                    'productId', 'brandId', 'productTypeId', 'primarySupplierId'
                ],
                'ShippedSales' => [
                    'orderTypeId', 'orderShippingStatusId'
                ],
                'PurchaseOrders' => [
                    'orderTypeId'
                ]
            ],
            Systems::EPOSNOW => [
                Factories::ORDERS => [
                    'status',
                ],
                Factories::STOCK_LEVELS => [
                    'location',
                ]
            ],
        ],
        'array' => [
            Systems::NETSUITE => [
                Factories::RETURNS => [
                    'location', 'internalid', 'subsidiary'
                ],
                Factories::CREDIT_MEMOS => [
                    'location', 'internalid', 'subsidiary'
                ],
                Factories::CUSTOMERS => [
                    'location', 'internalid', 'subsidiary'
                ],
                Factories::FULFILMENTS => [
                    'location', 'internalid', 'subsidiary'
                ],
                Factories::INVOICES => [
                    'location', 'internalid', 'subsidiary'
                ],
                Factories::ITEM_RECEIPTS => [
                    'location', 'internalid', 'subsidiary'
                ],
                Factories::KITS => [
                    'location', 'internalid', 'subsidiary'
                ],
                Factories::PRICES => [
                    'location', 'internalid', 'subsidiary'
                ],
                Factories::PRODUCTS_REFACTORED => [
                    'location', 'internalid', 'subsidiary'
                ],
                Factories::PURCHASE_ORDERS => [
                    'location', 'internalid', 'subsidiary'
                ],
                Factories::QUOTES => [
                    'location', 'internalid', 'subsidiary'
                ],
                Factories::REFUNDS => [
                    'location', 'internalid', 'subsidiary'
                ],
                Factories::TRANSFER_ORDERS => [
                    'location', 'internalid', 'subsidiary'
                ],
                Factories::WORK_ORDERS => [
                    'location', 'internalid', 'subsidiary'
                ],
                Factories::ORDERS => [
                    'location', 'internalid', 'transferlocation', 'subsidiary'
                ]
            ]
        ],
        'netsuite_boolean' => [
            Systems::NETSUITE => [
                Factories::ASSEMBLY_BUILDS => [
                    'mainline'
                ],
                Factories::RETURNS => [
                    'mainline'
                ],
                Factories::CREDIT_MEMOS => [
                    'mainline'
                ],
                Factories::CUSTOMERS => [
                    'mainline'
                ],
                Factories::FULFILMENTS => [
                    'mainline'
                ],
                Factories::INVOICES => [
                    'mainline'
                ],
                Factories::ITEM_RECEIPTS => [
                    'mainline'
                ],
                Factories::KITS => [
                    'mainline'
                ],
                Factories::PRICES => [
                    'mainline'
                ],
                Factories::PRODUCTS_REFACTORED => [
                    'mainline'
                ],
                Factories::QUOTES => [
                    'mainline'
                ],
                Factories::REFUNDS => [
                    'mainline'
                ],
                Factories::TRANSFER_ORDERS => [
                    'mainline'
                ],
                Factories::WORK_ORDERS => [
                    'mainline'
                ],
                Factories::ORDERS => [
                    'mainline'
                ],
                Factories::PURCHASE_ORDERS => [
                    'mainline'
                ]
            ]
        ],
        'boolean' => [
            Systems::BIGCOMMERCE => [
                Factories::ORDERS => [
                    'is_deleted'
                ]
            ],
            Systems::KHAOS => [
                Factories::STOCK_LEVELS => [
                    'PublishToWebOnly'
                ],
                Factories::PRODUCTS => [
                    'PublishToWebOnly'
                ]
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
        foreach (self::FILTER_TYPES as $type => $systems) {
            $filterType = FilterType::firstWhere('key', $type);
            if (!$filterType) {
                continue;
            }
            foreach ($systems as $system => $factories) {
                $system = System::firstWhere(['name' => $system]);
                if (!$system) {
                    continue;
                }
                foreach ($factories as $factory => $fields) {
                    $factory = Factory::firstWhere(['name' => $factory]);
                    if (!$factory) {
                        continue;
                    }
                    $factorySystem = FactorySystem::firstWhere([
                        'system_id' => $system->id,
                        'factory_id' => $factory->id
                    ]);
                    if (!$factorySystem) {
                        continue;
                    }

                    foreach ($fields as $field) {
                        $filterField = FilterField::firstWhere([
                            'key' => $field,
                            'factory_system_id' => $factorySystem->id
                        ]);
                        if (!$filterField) {
                            continue;
                        }

                        if (DB::table('filter_field_filter_type')->where([
                            'filter_field_id' => $filterField->id,
                            'filter_type_id' => $filterType->id
                        ])->doesntExist()) {
                            DB::table('filter_field_filter_type')->insert([
                                'filter_field_id' => $filterField->id,
                                'filter_type_id' => $filterType->id
                            ]);
                        }
                    }
                }
            }
        }
    }
}
