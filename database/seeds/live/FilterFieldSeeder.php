<?php

use App\Enums\Factories;
use App\Enums\Systems;
use App\Models\Fabric\Factory;
use App\Models\Fabric\FactorySystem;
use App\Models\Fabric\FilterField;
use App\Models\Fabric\FilterOperator;
use App\Models\Fabric\FilterType;
use App\Models\Fabric\System;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class FilterFieldSeeder extends Seeder
{
    private const SHOPIFY_TIME_FILTERS = [
        ['name' => 'Created At Max', 'key' => 'created_at_max', 'default_value' => '-10 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '>'],
        ['name' => 'Created At Min', 'key' => 'created_at_min', 'default' => true, 'default_value' => '-10 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '>'],
        ['name' => 'Update At Max', 'key' => 'updated_at_max', 'default_value' => '-10 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '>'],
        ['name' => 'Updated At Min', 'key' => 'updated_at_min', 'default_value' => '-10 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '>'],
    ];

    private const SHOPIFY_COMMON_FILTERS = [
        ['name' => 'IDs', 'key' => 'ids', 'default_value' => '1234,1235,1236', 'default_type_id' => 'csv', 'default_operator_id' => '='],
        ['name' => 'Since ID', 'key' => 'since_id', 'default_value' => 123456789, 'default_type_id' => 'integer', 'default_operator_id' => '='],
        ['name' => 'Limit', 'key' => 'limit', 'default_value' => 50, 'default_type_id' => 'integer', 'default_operator_id' => '='],
    ];

    private const SHOPIFY_ORDER_FILTERS = [
        ['name' => 'Financial Status', 'key' => 'financial_status', 'default' => true, 'default_value' => 'paid', 'default_type_id' => 'string', 'default_operator_id' => '='],
        ['name' => 'Fulfillment Status', 'key' => 'fulfillment_status', 'default' => true, 'default_value' => 'unfulfilled', 'default_type_id' => 'string', 'default_operator_id' => '='],
        ['name' => 'Processed At Max', 'key' => 'processed_at_max', 'default_value' => '-10 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '>'],
        ['name' => 'Processed At Min', 'key' => 'processed_at_min', 'default_value' => '-10 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '>'],
        ['name' => 'Status', 'key' => 'status', 'default_value' => 'open', 'default_type_id' => 'string', 'default_operator_id' => '='],
    ];

    private const SHOPIFY_PRODUCT_FILTERS = [
        ['name' => 'Collection ID', 'key' => 'collection_id', 'default_value' => 12345678, 'default_type_id' => 'integer', 'default_operator_id' => '='],
        ['name' => 'Handle', 'key' => 'handle', 'default_value' => 'example-product', 'default_type_id' => 'string', 'default_operator_id' => '='],
        ['name' => 'Presentment Currencies', 'key' => 'presentment_currencies', 'default_value' => 'AUD,GBP,USD', 'default_type_id' => 'csv', 'default_operator_id' => '='],
        ['name' => 'Published At Max', 'key' => 'published_at_max', 'default_value' => '-10 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '>'],
        ['name' => 'Published At Min', 'key' => 'published_at_min', 'default_value' => '-10 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '>'],
        ['name' => 'Published Status', 'key' => 'published_status', 'default_value' => 'any', 'default_type_id' => 'string', 'default_operator_id' => '='],
        ['name' => 'Title', 'key' => 'title', 'default_value' => 'Example Product', 'default_type_id' => 'string', 'default_operator_id' => '='],
        ['name' => 'Vendor', 'key' => 'vendor', 'default_value' => 'example vendor', 'default_type_id' => 'string', 'default_operator_id' => '='],
    ];

    private const SHOPIFY_GIFTCARD_FILTERS = [
        ['name' => 'Limit', 'key' => 'limit', 'default_value' => 50, 'default_type_id' => 'integer', 'default_operator_id' => '='],
        ['name' => 'Since ID', 'key' => 'since_id', 'default_value' => 50, 'default_type_id' => 'integer', 'default_operator_id' => '='],
        ['name' => 'Status', 'key' => 'status', 'default_value' => 'open', 'default_type_id' => 'string', 'default_operator_id' => '='],
    ];

    private const NETSUITE_COMMON_FIELDS = [
        ['name' => 'Last Modified Date', 'key' => 'lastmodifieddate', 'default' => true, 'default_value' => '-10 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '>'],
        ['name' => 'Location', 'key' => 'location', 'default_value' => 3, 'default_type_id' => 'integer', 'default_operator_id' => '='],
        ['name' => 'Status', 'key' => 'status', 'default_value' => 'C', 'default_type_id' => 'string', 'default_operator_id' => '='],
        ['name' => 'Mainline', 'key' => 'mainline', 'default' => true, 'default_value' => true, 'default_type_id' => 'boolean', 'default_operator_id' => '='],
        ['name' => 'Trandate', 'key' => 'trandate', 'default_value' => '-10 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '>'],
        ['name' => 'Internal ID', 'key' => 'internalid', 'default_value' => 3, 'default_type_id' => 'integer', 'default_operator_id' => '='],
        ['name' => 'Subsidiary', 'key' => 'subsidiary', 'default_value' => 3, 'default_type_id' => 'integer', 'default_operator_id' => '='],
        ['name' => 'Date Created', 'key' => 'datecreated', 'default_value' => '-10 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '>'],
    ];

    private const NETSUITE_PRODUCT_FIELDS = [
        ['name' => 'Last Modified Date', 'key' => 'lastmodifieddate', 'default' => true, 'default_value' => '-10 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '>'],
        ['name' => 'Location', 'key' => 'location', 'default_value' => 3, 'default_type_id' => 'integer', 'default_operator_id' => '='],
        ['name' => 'Internal ID', 'key' => 'internalid', 'default_value' => 3, 'default_type_id' => 'integer', 'default_operator_id' => '='],
        ['name' => 'Item ID', 'key' => 'itemid', 'default_value' => 'ExampleSKU', 'default_type_id' => 'string', 'default_operator_id' => '=']
    ];

    private const NAV_FILTER = [
        ['name' => 'No', 'key' => 'No', 'default_value' => 'NO12345', 'default_type_id' => 'string', 'default_operator_id' => '='],
    ];

    private const PVX_FULFILMENT_FILTER = [
        ['name' => 'Sales Order Number', 'key' => 'Sales+order+number', 'default_value' => 'EG123456', 'default_type_id' => 'string', 'default_operator_id' => '='],
        ['name' => 'Salesorder Number', 'key' => 'Salesorder+number', 'default_value' => 'EG1234', 'default_type_id' => 'string', 'default_operator_id' => '='],
        ['name' => 'Despatch Number', 'key' => 'Despatch+number', 'default_value' => 'DE1234', 'default_type_id' => 'string', 'default_operator_id' => '='],
        ['name' => 'Despatch Date', 'key' => 'Despatch+date', 'default' => true, 'default_value' => '-10 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '>'],
        ['name' => 'Site reference', 'key' => 'Site+reference', 'default_value' => 'PrimarySite', 'default_type_id' => 'string', 'default_operator_id' => '='],
    ];

    private const PVX_STOCK_FILTER = [
        ['name' => 'Item Code', 'key' => 'Item+code', 'default_value' => 'ExampleSKU', 'default_type_id' => 'string', 'default_operator_id' => '='],
        ['name' => 'Item Name', 'key' => 'Item+name', 'default_value' => 'Example Product', 'default_type_id' => 'string', 'default_operator_id' => '='],
        ['name' => 'Item Barcode', 'key' => 'Item+barcode', 'default_value' => '12345678', 'default_type_id' => 'string', 'default_operator_id' => '='],
    ];

    private const PVX_ORDER_FILTER = [
        ['name' => 'Sales Order No.', 'key' => 'Sales+order+no.', 'default_value' => 'EG1234', 'default_type_id' => 'string', 'default_operator_id' => '='],
        ['name' => 'Status', 'key' => 'Status', 'default_value' => 'new', 'default_type_id' => 'string', 'default_operator_id' => '='],
        ['name' => 'Number of Items', 'key' => 'Number+of+items', 'default_value' => 5, 'default_type_id' => 'integer', 'default_operator_id' => '='],
        ['name' => 'Channel', 'key' => 'Channel', 'default_value' => 'Example Channel', 'default_type_id' => 'string', 'default_operator_id' => '='],
        ['name' => 'Date', 'key' => 'Date', 'default' => true, 'default_value' => '-10 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '>'],
    ];

    private const PVX_RETURN_FILTER = [
        ['name' => 'Return Code', 'key' => 'Return+code', 'default_value' => 'RET1234', 'default_type_id' => 'string', 'default_operator_id' => '='],
        ['name' => 'Return Date', 'key' => 'Return+date', 'default' => true, 'default_value' => '-10 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '>'],
        ['name' => 'Item Name', 'key' => 'Item+name', 'default_value' => 'Example Product', 'default_type_id' => 'string', 'default_operator_id' => '='],
        ['name' => 'Sales Order Number', 'key' => 'Sales+order+number', 'default_value' => 'EG1234', 'default_type_id' => 'string', 'default_operator_id' => '='],
        ['name' => 'Despatch Date', 'key' => 'Despatch+date', 'default_value' => '-10 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '>'],
        ['name' => 'Return Reason', 'key' => 'Return+reason', 'default_value' => 'Damaged', 'default_type_id' => 'string', 'default_operator_id' => '='],
        ['name' => 'Return Condition', 'key' => 'Return+condition', 'default_value' => 'Damaged', 'default_type_id' => 'string', 'default_operator_id' => '='],
        ['name' => 'Return Comments', 'key' => 'Return+comments', 'default_value' => 'Customer Damage', 'default_type_id' => 'string', 'default_operator_id' => '='],
        ['name' => 'Days Since Despatch', 'key' => 'Days+since+despatch', 'default_value' => '-10 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '>'],
        ['name' => 'Item Barcode', 'key' => 'Item+barcode', 'default_value' => '123456', 'default_type_id' => 'string', 'default_operator_id' => '='],
        ['name' => 'Customer Purchase Order Reference Number', 'key' => 'Customer+purchase+order+reference+number'],
        ['name' => 'Channel Name', 'key' => 'Channel+name', 'default_value' => 'Example Channel', 'default_type_id' => 'string', 'default_operator_id' => '='],
        ['name' => 'Site Reference', 'key' => 'Site+reference', 'default_value' => 'PrimarySite', 'default_type_id' => 'string', 'default_operator_id' => '='],
        ['name' => 'Item Code', 'key' => 'Item+code', 'default_value' => 'ExampleSKU', 'default_type_id' => 'string', 'default_operator_id' => '='],
    ];

    private const SYSTEM_FILTER_FIELDS = [
        Systems::REBOUND => [
            Factories::RETURNS => [
                ['name' => 'ID', 'key' => 'id', 'default_value' => 546788, 'default_type_id' => 'integer', 'default_operator_id' => '='],
            ],
        ],
        Systems::DYNAMICS_NAV => [
            Factories::CUSTOMERS => self::NAV_FILTER,
            Factories::FULFILMENTS => self::NAV_FILTER,
            Factories::PRODUCTS => self::NAV_FILTER,
            Factories::PURCHASE_ORDERS => self::NAV_FILTER,
            Factories::STOCK_LEVELS => self::NAV_FILTER,
        ],
        Systems::NETSUITE => [
            Factories::ASSEMBLY_BUILDS => [
                ['name' => 'Trandate', 'key' => 'trandate', 'default_value' => '-10 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '>'],
                ['name' => 'Mainline', 'key' => 'mainline', 'default' => true, 'default_value' => true, 'default_type_id' => 'boolean', 'default_operator_id' => '='],
                ['name' => 'Last Modified Date', 'key' => 'lastmodifieddate', 'default' => true, 'default_value' => '-10 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '>'],
            ],
            Factories::RETURNS => self::NETSUITE_COMMON_FIELDS,
            Factories::CREDIT_MEMOS => self::NETSUITE_COMMON_FIELDS,
            Factories::CUSTOMERS => self::NETSUITE_COMMON_FIELDS,
            Factories::FULFILMENTS => self::NETSUITE_COMMON_FIELDS,
            Factories::INVOICES => self::NETSUITE_COMMON_FIELDS,
            Factories::ITEM_RECEIPTS => self::NETSUITE_COMMON_FIELDS,
            Factories::KITS => self::NETSUITE_COMMON_FIELDS,
            Factories::PRICES => self::NETSUITE_PRODUCT_FIELDS,
            Factories::PRODUCTS_REFACTORED => self::NETSUITE_PRODUCT_FIELDS,
            Factories::PURCHASE_ORDERS => self::NETSUITE_COMMON_FIELDS,
            Factories::QUOTES => self::NETSUITE_COMMON_FIELDS,
            Factories::REFUNDS => self::NETSUITE_COMMON_FIELDS,
            Factories::TRANSFER_ORDERS => self::NETSUITE_COMMON_FIELDS,
            Factories::WORK_ORDERS => self::NETSUITE_COMMON_FIELDS,
            Factories::INBOUND_SHIPMENTS => [
                ['name' => 'Status', 'key' => 'status', 'default_value' => 'InTransit', 'default_type_id' => 'string', 'default_operator_id' => '='],
                ['name' => 'Created Date', 'key' => 'createddate', 'default' => true, 'default_value' => '-10 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '>'],
            ],
            Factories::ORDERS => [
                ['name' => 'Location', 'key' => 'location', 'default_value' => 12, 'default_type_id' => 'integer', 'default_operator_id' => '='],
                ['name' => 'Status', 'key' => 'status', 'default_value' => 'C', 'default_type_id' => 'string', 'default_operator_id' => '='],
                ['name' => 'Last Modified', 'key' => 'lastmodified', 'default_value' => '-10 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '>'],
                ['name' => 'Mainline', 'key' => 'mainline', 'default' => true, 'default_value' => true, 'default_type_id' => 'boolean', 'default_operator_id' => '='],
                ['name' => 'Trandate', 'key' => 'trandate', 'default_value' => '-10 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '>'],
                ['name' => 'Transfer Location', 'key' => 'transferlocation', 'default_value' => 12, 'default_type_id' => 'integer', 'default_operator_id' => '='],
                ['name' => 'Internal ID', 'key' => 'internalid', 'default_value' => 12, 'default_type_id' => 'integer', 'default_operator_id' => '='],
                ['name' => 'Item ID', 'key' => 'itemid', 'default_value' => 'ExampleSKU', 'default_type_id' => 'string', 'default_operator_id' => '='],
                ['name' => 'Tran ID', 'key' => 'tranid', 'default_value' => 'EG12345', 'default_type_id' => 'string', 'default_operator_id' => '='],
                ['name' => 'Subsidiary', 'key' => 'subsidiary', 'default_value' => 12, 'default_type_id' => 'integer', 'default_operator_id' => '='],
                ['name' => 'Date Created', 'key' => 'datecreated', 'default_value' => '-10 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '>'],
                ['name' => 'Last Modified Date', 'key' => 'lastmodifieddate', 'default' => true, 'default_value' => '-10 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '>'],
                ['name' => 'Department', 'key' => 'department', 'default_value' => 'Example Department', 'default_type_id' => 'string', 'default_operator_id' => '='],
                ['name' => 'Class', 'key' => 'class', 'default_value' => 'Example Class', 'default_type_id' => 'string', 'default_operator_id' => '='],
            ]
        ],
        Systems::PEOPLEVOX => [
            Factories::FULFILMENT_SWEEPER => self::PVX_FULFILMENT_FILTER,
            Factories::REVERSE_FULFILMENT_SWEEPER => self::PVX_FULFILMENT_FILTER,
            Factories::FULFILMENT => self::PVX_FULFILMENT_FILTER,
            Factories::EVENT_FULFILMENT => self::PVX_FULFILMENT_FILTER,
            Factories::DESPATCH_PACKAGES => self::PVX_FULFILMENT_FILTER,
            Factories::EVENT_TRACKING_NO => self::PVX_FULFILMENT_FILTER,
            Factories::ITEM_RECEIPTS => [
                ['name' => 'Goods In Reference Number', 'key' => 'Goods+in+reference+number', 'default_value' => 'ABC123', 'default_type_id' => 'string', 'default_operator_id' => '='],
                ['name' => 'Delivery Date', 'key' => 'Delivery+date', 'default' => true, 'default_value' => '-10 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '>'],
                ['name' => 'Reconciled Date', 'key' => 'Reconciled+date', 'default_value' => '-10 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '>'],
                ['name' => 'Item Code', 'key' => 'Item+code', 'default_value' => 'ExampleSKU', 'default_type_id' => 'string', 'default_operator_id' => '='],
                ['name' => 'Item Name', 'key' => 'Item+name', 'default_value' => 'Example Product', 'default_type_id' => 'string', 'default_operator_id' => '='],
                ['name' => 'Item Barcode', 'key' => 'Item+barcode', 'default_value' => '12345678', 'default_type_id' => 'string', 'default_operator_id' => '='],
                ['name' => 'Site Reference', 'key' => 'Site+reference', 'default_value' => 'PrimarySite', 'default_type_id' => 'string', 'default_operator_id' => '='],
                ['name' => 'Purchase Order Number', 'key' => 'Purchase+order+number', 'default_value' => 'PO540', 'default_type_id' => 'string', 'default_operator_id' => '='],
            ],
            Factories::STOCK => self::PVX_STOCK_FILTER,
            Factories::STOCK_CSV => self::PVX_STOCK_FILTER,
            Factories::ORDERS => self::PVX_ORDER_FILTER,
            Factories::ORDERS_REFACTORED => self::PVX_ORDER_FILTER,
            Factories::PRODUCTS => [
                ['name' => 'Item Code', 'key' => 'Item+code', 'default_value' => 'ExampleSKU', 'default_type_id' => 'string', 'default_operator_id' => '='],
                ['name' => 'Item Name', 'key' => 'Item+name', 'default_value' => 'Example Product', 'default_type_id' => 'string', 'default_operator_id' => '='],
                ['name' => 'Item Barcode', 'key' => 'Item+barcode', 'default_value' => '12345678', 'default_type_id' => 'string', 'default_operator_id' => '='],
            ],
            Factories::RETURNS => self::PVX_RETURN_FILTER,
            Factories::RETURNS_SWEEPER => self::PVX_RETURN_FILTER,
            Factories::TIMESTAMP_STOCK => [
                ['name' => 'Timestamp', 'key' => 'Timestamp', 'default' => true, 'default_value' => '-10 minutes', 'default_type_id' => 'TIMESTAMP', 'default_operator_id' => '>'],
                ['name' => 'Site Reference', 'key' => 'Site+reference', 'default_value' => 'PrimarySite', 'default_type_id' => 'string', 'default_operator_id' => '='],
                ['name' => 'Item Code', 'key' => 'Item+code', 'default_value' => 'ExampleSKU', 'default_type_id' => 'string', 'default_operator_id' => '='],
            ],
        ],
        Systems::SEKO_API => [
            Factories::PRODUCTS => [
                ['name' => 'Update At Max', 'key' => 'updated_at_max', 'default_value' => 'TIME:-2 hours'],
                ['name' => 'Updated At Min', 'key' => 'updated_at_min', 'default_value' => 'TIME:-6 hours'],
            ]
        ],
        Systems::SEKO => [
            Factories::PRODUCTS => [
                ['name' => 'Update At Max', 'key' => 'updated_at_max', 'default_value' => 'TIME:-2 hours'],
                ['name' => 'Updated At Min', 'key' => 'updated_at_min', 'default_value' => 'TIME:-6 hours'],
            ]
        ],
        Systems::VEND => [
            Factories::SALES => [
                [
                    'name' => 'Type',
                    'key' => 'type',
                    'default' => true,
                    'default_value' => 'sales',
                    'default_type_id' => 'string',
                    'default_operator_id' => '=',
                ],
                [
                    'name' => 'Date From',
                    'key' => 'date_from',
                    'default' => true,
                    'default_value' => '-30 mins',
                    'default_type_id' => 'TIME',
                    'default_operator_id' => '>',
                ],
                ['name' => 'Status', 'key' => 'status', 'default_value' => 'CLOSED', 'default_type_id' => 'string', 'default_operator_id' => '='],
            ],
        ],
        Systems::BIGCOMMERCE => [
            Factories::ORDERS => [
                ['name' => 'Min Date Created', 'key' => 'min_date_created', 'default' => true, 'default_value' => '-10 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '>'],
                ['name' => 'Max Date Created', 'key' => 'max_date_created', 'default_value' => '-10 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '>'],
                ['name' => 'Min Date Modified', 'key' => 'min_date_modified', 'default_value' => '-10 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '>'],
                ['name' => 'Max Date Modified', 'key' => 'max_date_modified', 'default_value' => '-10 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '>'],
                ['name' => 'Cart ID', 'key' => 'cart_id', 'default_value' => 'Cart ID', 'default_type_id' => 'string', 'default_operator_id' => '='],
                ['name' => 'Payment Method', 'key' => 'payment_method', 'default_value' => 'Credit Card', 'default_type_id' => 'string', 'default_operator_id' => '='],
                ['name' => 'Sort', 'key' => 'sort', 'default_value' => 'id', 'default_type_id' => 'string', 'default_operator_id' => '='],
                ['name' => 'Channel ID', 'key' => 'channel_id', 'default_value' => 1, 'default_type_id' => 'integer', 'default_operator_id' => '='],
                ['name' => 'Customer ID', 'key' => 'customer_id', 'default_value' => 1, 'default_type_id' => 'integer', 'default_operator_id' => '='],
                ['name' => 'Limit', 'key' => 'limit', 'default_value' => 50, 'default_type_id' => 'integer', 'default_operator_id' => '='],
                ['name' => 'Max ID', 'key' => 'max_id', 'default_value' => 1, 'default_type_id' => 'integer', 'default_operator_id' => '='],
                ['name' => 'Max Total', 'key' => 'max_total', 'default_value' => 1, 'default_type_id' => 'integer', 'default_operator_id' => '='],
                ['name' => 'Min ID', 'key' => 'min_id', 'default_value' => 1, 'default_type_id' => 'integer', 'default_operator_id' => '='],
                ['name' => 'Min Total', 'key' => 'min_total', 'default_value' => 1, 'default_type_id' => 'integer', 'default_operator_id' => '='],
                ['name' => 'Page', 'key' => 'page', 'default_value' => 1, 'default_type_id' => 'integer', 'default_operator_id' => '='],
                ['name' => 'Status ID', 'key' => 'status_id', 'default_value' => 1, 'default_type_id' => 'integer', 'default_operator_id' => '='],
                ['name' => 'Is Deleted', 'key' => 'is_deleted', 'default_value' => false, 'default_type_id' => 'boolean', 'default_operator_id' => '='],
            ],
            Factories::REFUNDS => [
                ['name' => 'Created At Max', 'key' => 'created:max', 'default_value' => '-10 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '>'],
                ['name' => 'Created At Min', 'key' => 'created:min', 'default' => true, 'default_value' => '-10 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '>'],
                ['name' => 'IDs', 'key' => 'id:in', 'default_value' => '1,2,3', 'default_type_id' => 'csv', 'default_operator_id' => '='],
                ['name' => 'Limit', 'key' => 'limit', 'default_value' => 1, 'default_type_id' => 'integer', 'default_operator_id' => '='],
                ['name' => 'Order IDs', 'key' => 'order_id:in', 'default_value' => '1,2,3', 'default_type_id' => 'csv', 'default_operator_id' => '='],
                ['name' => 'Page', 'key' => 'page', 'default_value' => 1, 'default_type_id' => 'integer', 'default_operator_id' => '='],
            ],
            Factories::PRODUCTS => [
                ['name' => 'Store Hash', 'key' => 'store_hash', 'default_value' => 'xb345dsa', 'default_type_id' => 'string', 'default_operator_id' => '='],
                ['name' => 'Availability', 'key' => 'availability', 'default_value' => 'available', 'default_type_id' => 'string', 'default_operator_id' => '='],
                ['name' => 'Brand ID', 'key' => 'brand_id', 'default_value' => 23, 'default_type_id' => 'integer', 'default_operator_id' => '='],
                ['name' => 'Categories', 'key' => 'categories', 'default_value' => 23, 'default_type_id' => 'integer', 'default_operator_id' => '='],
                ['name' => 'Categories In (Multiple)', 'key' => 'categories:in', 'default_value' => 23, 'default_type_id' => 'integer', 'default_operator_id' => '='],
                ['name' => 'Condition', 'key' => 'condition', 'default_value' => 'new', 'default_type_id' => 'string', 'default_operator_id' => '='],
                ['name' => 'Date Last Imported', 'key' => 'date_last_imported', 'default_value' => '-10 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '='],
                ['name' => 'Date Last Imported Max', 'key' => 'date_last_imported:max', 'default_value' => '-10 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '>'],
                ['name' => 'Date Last Imported Min', 'key' => 'date_last_imported:min', 'default' => true, 'default_value' => '-10 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '>'],
                ['name' => 'Date Modified', 'key' => 'date_modified', 'default_value' => '-10 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '='],
                ['name' => 'Date Modified Max', 'key' => 'date_modified:max', 'default_value' => '-10 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '>'],
                ['name' => 'Date Modified Min', 'key' => 'date_modified:min', 'default_value' => '-10 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '>'],
                ['name' => 'Direction', 'key' => 'direction', 'default_value' => 'asc', 'default_type_id' => 'string', 'default_operator_id' => '='],
                ['name' => 'Exclude Fields', 'key' => 'exclude_fields', 'default_value' => 'example_field', 'default_type_id' => 'string', 'default_operator_id' => '='],
                ['name' => 'ID', 'key' => 'id', 'default_value' => 1, 'default_type_id' => 'integer', 'default_operator_id' => '='],
                ['name' => 'ID Greater Than', 'key' => 'id:greater', 'default_value' => '1234,1235,1236', 'default_type_id' => 'csv', 'default_operator_id' => '='],
                ['name' => 'ID In', 'key' => 'id:in', 'default_value' => '1234,1235,1236', 'default_type_id' => 'csv', 'default_operator_id' => '='],
                ['name' => 'ID Less Than', 'key' => 'id:less', 'default_value' => '1234,1235,1236', 'default_type_id' => 'csv', 'default_operator_id' => '='],
                ['name' => 'ID Max', 'key' => 'id:max', 'default_value' => '1234,1235,1236', 'default_type_id' => 'csv', 'default_operator_id' => '='],
                ['name' => 'ID Min', 'key' => 'id:min', 'default_value' => '1234,1235,1236', 'default_type_id' => 'csv', 'default_operator_id' => '='],
                ['name' => 'ID Not In', 'key' => 'id:not_in', 'default_value' => '1234,1235,1236', 'default_type_id' => 'csv', 'default_operator_id' => '='],
                ['name' => 'Include', 'key' => 'include', 'default_value' => 'variants', 'default_type_id' => 'string', 'default_operator_id' => '='],
                ['name' => 'Include Fields', 'key' => 'include_fields', 'default_value' => 'variant_field', 'default_type_id' => 'string', 'default_operator_id' => '='],
                ['name' => 'Inventory Level', 'key' => 'inventory_level', 'default_value' => 50, 'default_type_id' => 'integer', 'default_operator_id' => '='],
                ['name' => 'Inventory Level Greater Than', 'key' => 'inventory_level:greater', 'default_value' => 50, 'default_type_id' => 'integer', 'default_operator_id' => '='],
                ['name' => 'Inventory Level In', 'key' => 'inventory_level:in', 'default_value' => 50, 'default_type_id' => 'integer', 'default_operator_id' => '='],
                ['name' => 'Inventory Level Less Than', 'key' => 'inventory_level:less', 'default_value' => 50, 'default_type_id' => 'integer', 'default_operator_id' => '='],
                ['name' => 'Inventory Level Max', 'key' => 'inventory_level:max', 'default_value' => 50, 'default_type_id' => 'integer', 'default_operator_id' => '='],
                ['name' => 'Inventory Level Min', 'key' => 'inventory_level:min', 'default_value' => 50, 'default_type_id' => 'integer', 'default_operator_id' => '='],
                ['name' => 'Inventory Level Not In', 'key' => 'inventory_level:not_in', 'default_value' => 50, 'default_type_id' => 'integer', 'default_operator_id' => '='],
                ['name' => 'Inventory Low', 'key' => 'inventory_low', 'default_value' => 50, 'default_type_id' => 'integer', 'default_operator_id' => '='],
                ['name' => 'Is Featured', 'key' => 'is_featured', 'default_value' => 1, 'default_type_id' => 'integer', 'default_operator_id' => '='],
                ['name' => 'Is Free Shipping', 'key' => 'is_free_shipping', 'default_value' => 1, 'default_type_id' => 'integer', 'default_operator_id' => '='],
                ['name' => 'Is Visible', 'key' => 'is_visible', 'default_value' => true, 'default_type_id' => 'boolean', 'default_operator_id' => '='],
                ['name' => 'Keyword', 'key' => 'keyword', 'default_value' => 'keyword', 'default_type_id' => 'string', 'default_operator_id' => '='],
                ['name' => 'Keyword Context', 'key' => 'keyword_context', 'default_value' => 'keyword_context', 'default_type_id' => 'string', 'default_operator_id' => '='],
                ['name' => 'Limit', 'key' => 'limit', 'default_value' => 50, 'default_type_id' => 'integer', 'default_operator_id' => '='],
                ['name' => 'Name', 'key' => 'name', 'default_value' => 'Example Product', 'default_type_id' => 'string', 'default_operator_id' => '='],
                ['name' => 'Out of Stock', 'key' => 'out_of_stock', 'default_value' => 1, 'default_type_id' => 'integer', 'default_operator_id' => '='],
                ['name' => 'Page', 'key' => 'page', 'default_value' => 1, 'default_type_id' => 'integer', 'default_operator_id' => '='],
                ['name' => 'Price', 'key' => 'price', 'default_value' => 50, 'default_type_id' => 'integer', 'default_operator_id' => '='],
                ['name' => 'SKU', 'key' => 'sku', 'default_value' => 'ExampleSKU', 'default_type_id' => 'string', 'default_operator_id' => '='],
                ['name' => 'SKU In', 'key' => 'sku:in', 'default_value' => '1234,1235,1236', 'default_type_id' => 'csv', 'default_operator_id' => '='],
                ['name' => 'Sort', 'key' => 'sort', 'default_value' => 'id', 'default_type_id' => 'string', 'default_operator_id' => '='],
                ['name' => 'Status', 'key' => 'status', 'default_value' => 1, 'default_type_id' => 'integer', 'default_operator_id' => '='],
                ['name' => 'Total Sold', 'key' => 'total_sold', 'default_value' => 100, 'default_type_id' => 'integer', 'default_operator_id' => '='],
                ['name' => 'Type', 'key' => 'type', 'default_value' => 'physical', 'default_type_id' => 'string', 'default_operator_id' => '='],
                ['name' => 'UPC', 'key' => 'upc', 'default_value' => '123445667', 'default_type_id' => 'string', 'default_operator_id' => '='],
                ['name' => 'Weight', 'key' => 'weight', 'default_value' => 50, 'default_type_id' => 'integer', 'default_operator_id' => '='],
            ],
        ],
        Systems::MAGENTO_2 => [
            Factories::ORDERS => [
                ['name' => 'Created At', 'key' => 'created_at', 'default' => true, 'default_value' => '-10 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '>'],
                ['name' => 'Store ID', 'key' => 'store_id', 'default_value' => '5', 'default_type_id' => 'string', 'default_operator_id' => '='],
                ['name' => 'Status', 'key' => 'status', 'default_value' => 'processing', 'default_type_id' => 'string', 'default_operator_id' => '='],
                ['name' => 'Customer Group ID', 'key' => 'customer_group_id', 'default_value' => '5', 'default_type_id' => 'string', 'default_operator_id' => '='],
                ['name' => 'Updated At', 'key' => 'updated_at', 'default_value' => '-10 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '>'],
            ],
            Factories::CUSTOMERS => [
                ['name' => 'Updated At', 'key' => 'updated_at', 'default_value' => '-10 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '>'],
                ['name' => 'Created At', 'key' => 'created_at', 'default' => true, 'default_value' => '-10 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '>'],
            ],
            Factories::PRODUCTS => [
                ['name' => 'Type ID', 'key' => 'type_id', 'default_value' => '5', 'default_type_id' => 'string', 'default_operator_id' => '='],
                ['name' => 'Updated At', 'key' => 'updated_at', 'default_value' => '-10 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '>'],
                ['name' => 'Created At', 'key' => 'created_at', 'default' => true, 'default_value' => '-10 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '>'],
            ],
            Factories::REFUNDS => [
                ['name' => 'Created At', 'key' => 'created_at', 'default' => true, 'default_value' => '-10 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '>'],
                ['name' => 'Updated At', 'key' => 'updated_at', 'default_value' => '-10 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '>'],
                ['name' => 'Store ID', 'key' => 'store_id', 'default_value' => '5', 'default_type_id' => 'string', 'default_operator_id' => '='],
            ]
        ],
        Systems::COMMERCETOOLS => [
            Factories::ORDERS => [
                ['name' => 'Last Modified At', 'key' => 'lastModifiedAt', 'default' => true, 'default_value' => '-10 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '>'],
                ['name' => 'Order State', 'key' => 'orderState', 'default_value' => 'Open', 'default_type_id' => 'string', 'default_operator_id' => '='],
                ['name' => 'Order Number', 'key' => 'order_number', 'default_value' => '12345678', 'default_type_id' => 'string', 'default_operator_id' => '='],
            ],
            Factories::PRODUCTS => [
                ['name' => 'Last Modified At', 'key' => 'lastModifiedAt', 'default' => true, 'default_value' => '-10 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '>'],
                ['name' => 'Key', 'key' => 'key', 'default_value' => 'ExampleSKU', 'default_type_id' => 'string', 'default_operator_id' => '='],
            ],
        ],
        Systems::MIRAKL => [
            Factories::ORDERS => [
                ['name' => 'Start Date', 'key' => 'start_date', 'default' => true, 'default_value' => '-10 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '>'],
                ['name' => 'Order Status', 'key' => 'order_state_codes'],
                ['name' => 'Order Ids', 'key' => 'order_ids', 'default_value' => '1234,1235,1236', 'default_type_id' => 'csv', 'default_operator_id' => '=']
            ]
        ],
        Systems::KHAOS => [
            'Fulfilments' => [
                ['name' => 'Show Items', 'key' => 'ShowItems', 'default' => true, 'default_value' => 'true', 'default_type_id' => 'string', 'default_operator_id' => '='],
                ['name' => 'Web Only', 'key' => 'WebOnly', 'default' => true, 'default_value' => 'true', 'default_type_id' => 'string', 'default_operator_id' => '='],
                ['name' => 'Start From Date', 'key' => 'DateFrom', 'default' => true, 'default_value' => '-30 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '>'],
                ['name' => 'End At Date', 'key' => 'DateTo', 'default' => true, 'default_value' => '-30 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '>'],
                ['name' => 'Invoice Stage IDs', 'key' => 'InvoiceStageIDs', 'default_value' => '1234,1235,1236', 'default_type_id' => 'csv', 'default_operator_id' => '='],
                ['name' => 'Mapping Type', 'key' => 'MappingType', 'default_value' => 1, 'default_type_id' => 'integer', 'default_operator_id' => '='],
            ],
            Factories::STOCK_LEVELS => [
                ['name' => 'Start From Date', 'key' => 'DateValue', 'default' => true, 'default_value' => '-30 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '>'],
                ['name' => 'Publish To Web Only', 'key' => 'PublishToWebOnly', 'default' => true, 'default_value' => true, 'default_type_id' => 'boolean', 'default_operator_id' => '='],
            ],
            Factories::PRODUCTS => [
                ['name' => 'Start From Date', 'key' => 'DateValue', 'default' => true, 'default_value' => '-30 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '>'],
                ['name' => 'Publish To Web Only', 'key' => 'PublishToWebOnly', 'default' => true, 'default_value' => true, 'default_type_id' => 'boolean', 'default_operator_id' => '='],
            ],
        ],
        Systems::WOOCOMMERCE => [
            Factories::ORDERS => [
                ['name' => 'Date Created', 'key' => 'date_created', 'default' => true, 'default_value' => '-15 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '>'],
                ['name' => 'Order Key', 'key' => 'order_key', 'default_value' => 'wc_order_58d2d042d1d', 'default_type_id' => 'string', 'default_operator_id' => '='],
                ['name' => 'Status', 'key' => 'status'],
                ['name' => 'Date Modified', 'key' => 'date_modified', 'default' => true, 'default_value' => '-15 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '>'],
            ],
            Factories::PRODUCTS => [
                ['name' => 'Date Created', 'key' => 'date_created', 'default' => true, 'default_value' => '-15 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '>'],
                ['name' => 'Date Modified', 'key' => 'date_modified', 'default' => true, 'default_value' => '-15 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '>'],
                ['name' => 'Type', 'key' => 'type', 'default_value' => 'simple', 'default_type_id' => 'string', 'default_operator_id' => '=']
            ],
            Factories::REFUNDS => [
                ['name' => 'Date Created', 'key' => 'date_created', 'default' => true, 'default_value' => '-15 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '>']
            ]
        ],
        Systems::BRIGHTPEARL => [
            Factories::ORDERS => [
                ['name' => 'Created On', 'key' => 'createdOn', 'default_value' => '-10 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '>'],
                ['name' => 'Updated On', 'key' => 'updatedOn', 'default_value' => '-10 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '>'],
                ['name' => 'Delivery Date', 'key' => 'deliveryDate', 'default_value' => '-10 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '>'],
                ['name' => 'Order Status ID', 'key' => 'orderStatusId', 'default_value' => '3', 'default_type_id' => 'string', 'default_operator_id' => '='],
                ['name' => 'Customer Ref', 'key' => 'customerRef', 'default_value' => 'UK1099', 'default_type_id' => 'string', 'default_operator_id' => '='],
                ['name' => 'Order Payment Status', 'key' => 'orderPaymentStatus'],
                ['name' => 'Order Shipping Status', 'key' => 'orderShippingStatus', 'default_value' => '2', 'default_type_id' => 'string', 'default_operator_id' => '='],
                ['name' => 'Sales Order ID', 'key' => 'salesOrderId', 'default_value' => '1191', 'default_type_id' => 'string', 'default_operator_id' => '=']
            ],
            Factories::TRANSFER_ORDERS => [
                ['name' => 'Created On', 'key' => 'createdOn', 'default_value' => '-10 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '>'],
                ['name' => 'Updated On', 'key' => 'updatedOn', 'default_value' => '-10 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '>'],
                ['name' => 'Delivery Date', 'key' => 'deliveryDate', 'default_value' => '-10 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '>']
            ],
            Factories::PURCHASE_ORDERS => [
                ['name' => 'Created On', 'key' => 'createdOn', 'default_value' => '-10 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '>'],
                ['name' => 'Updated On', 'key' => 'updatedOn', 'default_value' => '-10 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '>'],
                ['name' => 'Placed On', 'key' => 'placedOn', 'default_value' => '-10 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '>', 'default' => true],
                ['name' => 'Order Type ID', 'key' => 'orderTypeId', 'default_value' => '2', 'default_type_id' => 'integer', 'default_operator_id' => '=', 'default' => true]
            ],
            Factories::SUPPLIERS => [
                ['name' => 'Created On', 'key' => 'createdOn', 'default_value' => '-10 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '>'],
                ['name' => 'Updated On', 'key' => 'updatedOn', 'default_value' => '-10 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '>'],
            ],
            Factories::GOODS_RECEIPTS => [
                ['name' => 'Purchase Order Id', 'key' => 'PurchaseOrderId', 'default_value' => '1186', 'default_type_id' => 'string', 'default_operator_id' => '='],
                ['name' => 'Warehouse ID', 'key' => 'WarehouseId', 'default_value' => '2', 'default_type_id' => 'string', 'default_operator_id' => '='],
                ['name' => 'Order Status ID', 'key' => 'orderStatusId', 'default_value' => '3', 'default_type_id' => 'string', 'default_operator_id' => '='],
                ['name' => 'Created Date', 'key' => 'createdDate', 'default_value' => '-10 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '>', 'default' => true],
                ['name' => 'Received Date', 'key' => 'receivedDate', 'default_value' => '-10 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '>']
            ],
            Factories::PRODUCTS => [
                ['name' => 'SKU', 'key' => 'SKU', 'default_value' => 'basket', 'default_type_id' => 'string', 'default_operator_id' => '='],
                ['name' => 'Barcode', 'key' => 'barcode', 'default_value' => 'barcode', 'default_type_id' => 'string', 'default_operator_id' => '='],
                ['name' => 'Product Name', 'key' => 'productName', 'default_value' => 'basket', 'default_type_id' => 'string', 'default_operator_id' => '='],
                ['name' => 'Created On', 'key' => 'createdOn', 'default_value' => '-10 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '>'],
                ['name' => 'Updated On', 'key' => 'updatedOn', 'default_value' => '-10 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '>', 'default' => true],
                ['name' => 'Product Id', 'key' => 'productId', 'default_value' => '1289', 'default_type_id' => 'string', 'default_operator_id' => '=']
            ],
            Factories::STOCK => [
                ['name' => 'SKU', 'key' => 'SKU', 'default_value' => 'basket', 'default_type_id' => 'string', 'default_operator_id' => '='],
                ['name' => 'Barcode', 'key' => 'barcode', 'default_value' => 'barcode', 'default_type_id' => 'string', 'default_operator_id' => '='],
                ['name' => 'Product Name', 'key' => 'productName', 'default_value' => 'basket', 'default_type_id' => 'string', 'default_operator_id' => '='],
                ['name' => 'Created On', 'key' => 'createdOn', 'default_value' => '-10 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '>'],
                ['name' => 'Updated On', 'key' => 'updatedOn', 'default_value' => '-10 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '>', 'default' => true],
                ['name' => 'Product Id', 'key' => 'productId', 'default_value' => '1289', 'default_type_id' => 'string', 'default_operator_id' => '=']
            ]
        ],
        Systems::VEEQO => [
            Factories::PRODUCTS => [
                ['name' => 'Created At Min', 'key' => 'created_at_min', 'default' => true, 'default_value' => '-30 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '='],
            ],
        ],
        Systems::EPOSNOW => [
            Factories::ORDERS => [
                ['name' => 'Start Date', 'key' => 'startDate', 'default' => true, 'default_value' => '-30 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '='],
                ['name' => 'End Date', 'key' => 'endDate', 'default' => true, 'default_value' => '+2 hours', 'default_type_id' => 'TIME', 'default_operator_id' => '='],
                ['name' => 'Status', 'key' => 'status', 'default' => true, 'default_value' => '1', 'default_type_id' => 'integer', 'default_operator_id' => '='],
            ],
            Factories::STOCK_LEVELS => [
                ['name' => 'Location', 'key' => 'location'],
            ],
        ],
        Systems::VISUALSOFT => [
            Factories::ORDERS => [
                ['name' => 'From Date', 'key' => 'from_date', 'default' => true, 'default_value' => '-90 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '='],
            ],
            Factories::CUSTOMERS => [
                ['name' => 'Timestamp', 'key' => 'timestamp', 'default' => true, 'default_value' => '-90 minutes', 'default_type_id' => 'TIME', 'default_operator_id' => '='],
            ],
        ],
    ];

    /**
     * This array should contain the system then an array of entities and then field arrays to merge together
     * E.g. Shopify->Address then becomes one large array instead of an array of arrays
     *
     * @var array
     */
    private array $filtersToMerge = [
        Systems::SHOPIFY => [
            Factories::ADDRESSES => [
                self::SHOPIFY_TIME_FILTERS,
                self::SHOPIFY_COMMON_FILTERS,
            ],
            Factories::CASH_REFUNDS => [
                self::SHOPIFY_TIME_FILTERS,
                self::SHOPIFY_COMMON_FILTERS,
                self::SHOPIFY_ORDER_FILTERS,
            ],
            Factories::CUSTOMERS => [
                self::SHOPIFY_TIME_FILTERS,
                self::SHOPIFY_COMMON_FILTERS,
            ],
            Factories::ORDERS => [
                self::SHOPIFY_TIME_FILTERS,
                self::SHOPIFY_COMMON_FILTERS,
                self::SHOPIFY_ORDER_FILTERS,
            ],
            Factories::PRODUCTS => [
                self::SHOPIFY_TIME_FILTERS,
                self::SHOPIFY_COMMON_FILTERS,
                self::SHOPIFY_PRODUCT_FILTERS,
            ],
            Factories::INVENTORY_ITEMS => [
                self::SHOPIFY_TIME_FILTERS,
                self::SHOPIFY_COMMON_FILTERS,
                self::SHOPIFY_PRODUCT_FILTERS,
            ],
            Factories::RETURNS => [
                self::SHOPIFY_TIME_FILTERS,
                self::SHOPIFY_COMMON_FILTERS,
                self::SHOPIFY_ORDER_FILTERS,
            ],
            Factories::GIFT_CARDS => [
                self::SHOPIFY_GIFTCARD_FILTERS
            ],
        ],
    ];

    private array $filters = [];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->mergeFilterFields();
        foreach ($this->filters as $system => $factories) {
            $system = System::firstWhere('name', $system);
            if (is_null($system)) {
                continue;
            }
            foreach ($factories as $factory => $filterFields) {
                $factory = Factory::firstWhere('name', $factory);
                if (is_null($factory)) {
                    continue;
                }

                $factorySystem = FactorySystem::firstWhere([
                    'factory_id' => $factory->id,
                    'system_id' => $system->id,
                    'direction' => 'pull'
                ]);
                if (is_null($factorySystem)) {
                    continue;
                }

                foreach ($filterFields as $field) {

                    $fieldData = [
                        'name' => $field['name'],
                        'key' => $field['key'],
                        'factory_system_id' => $factorySystem->id
                    ];

                    if (
                        Arr::has($field, ['default_value', 'default_type_id', 'default_operator_id'])
                    ) {
                        $field['default_type_id'] = FilterType::firstWhere('key', $field['default_type_id'])->id;
                        $field['default_operator_id'] = FilterOperator::firstWhere('key', '=', $field['default_operator_id'])->id;
                        $fieldData = array_merge($field, $fieldData);
                    }

                    FilterField::updateOrCreate(
                        ['key' => $field['key'], 'factory_system_id' => $factorySystem->id],
                        $fieldData
                    );
                }
            }
        }
    }

    /**
     * Merge all filter fields together for processing
     *
     * @return void
     */
    private function mergeFilterFields(): void
    {
        foreach ($this->filtersToMerge as $systemName => $fieldsAndEntities) {
            foreach ($fieldsAndEntities as $entityName => $fieldSet) {
                $merged = [];
                foreach ($fieldSet as $fields) {
                    $merged = array_merge($merged, $fields);
                }
                $this->filters[$systemName][$entityName] = $merged;
            }
        }

        $this->filters = array_merge($this->filters, self::SYSTEM_FILTER_FIELDS);
    }
}
