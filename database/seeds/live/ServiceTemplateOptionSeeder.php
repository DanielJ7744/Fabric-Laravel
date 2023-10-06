<?php

use App\Enums\Factories;
use App\Enums\Systems;
use App\Models\Fabric\Factory;
use App\Models\Fabric\FactorySystem;
use App\Models\Fabric\ServiceOption;
use App\Models\Fabric\ServiceTemplateOption;
use App\Models\Fabric\ServiceTemplate;
use App\Models\Fabric\System;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class ServiceTemplateOptionSeeder extends Seeder
{
    private const SERVICE_TEMPLATE_OPTIONS = [
        [
            'source' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::SHOPIFY,
            ],
            'destination' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::NETSUITE,
            ],
            'options' => [
                'source' => [
                    'page_size' => ['value' => 250],
                    'inject_meta_data' => ['value' => true],
                    'inject_customer_meta_data' => ['value' => true],
                    'get_order_risks' => ['value' => true],
                ],
                'destination' => [
                    'origin' => ['value' => 'Shopify', 'user_configurable' => true],
                ],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::CUSTOMERS,
                'system' => Systems::SHOPIFY,
            ],
            'destination' => [
                'factory' => Factories::CUSTOMERS,
                'system' => Systems::NETSUITE,
            ],
            'options' => [
                'source' => [
                    'page_size' => ['value' => 250],
                ],
                'destination' => [
                    'origin' => ['value' => 'Shopify', 'user_configurable' => true],
                    'payment_type' => ['value' => 'Deposit'],
                    'use_gross_price' => ['value' => true],
                    'use_net_price' => ['value' => false],
                    'record_array_name' => ['value' => 'orders'],
                    'db_type' => ['value' => 'Order'],
                    'common_ref_field' => ['value' => 'order_number'],
                    'source_id_field' => ['value' => 'order_id'],
                    'record_mode' => ['value' => 'dynamic'],
                    'sku_field' => ['value' => 'itemid'],
                ],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::REFUNDS,
                'system' => Systems::SHOPIFY,
            ],
            'destination' => [
                'factory' => Factories::RETURNS,
                'system' => Systems::NETSUITE,
            ],
            'options' => [
                'source' => [
                    'page_size' => ['value' => 250]
                ],
                'destination' => [
                    'record_mode' => ['value' => 'dynamic'],
                    'record_type' => ['value' => 'creditmemo'],
                    'returns_type' => ['value' => 'refunds'],
                    'create_creditmemo' => ['value' => true],
                    'create_itemreceipt' => ['value' => true],
                    'skip_customer_refund_after_credit_memo' => ['value' => false],
                    'use_gross_price' => ['value' => true],
                    'use_net_price' => ['value' => false],
                    'set_gross_cm_price' => ['value' => true],
                    'set_gross_ra_price' => ['value' => true],
                ],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::SHOPIFY,
            ],
            'destination' => [
                'factory' => Factories::CASH_SALES,
                'system' => Systems::NETSUITE,
            ],
            'options' => [
                'source' => [
                    'filter_source' => ['value' => ['pos']]
                ],
                'destination' => [
                    'record_mode' => ['value' => 'dynamic'],
                    'record_array_name' => ['value' => 'orders'],
                    'db_type' => ['value' => 'Order'],
                    'common_ref_field' => ['value' => 'order_number'],
                    'source_id_field' => ['value' => 'order_id'],
                    'use_gross_price' => ['value' => true],
                    'use_net_price' => ['value' => false],
                ],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::STOCK_REFACTORED,
                'system' => Systems::NETSUITE,
            ],
            'destination' => [
                'factory' => Factories::STOCK_LEVELS,
                'system' => Systems::SHOPIFY,
            ],
            'options' => [
                'source' => [
                    'use_pagination' => ['value' => true],
                ],
                'destination' => [
                    'skip_download_remote_records' => ['value' => true],
                    'fetch_missing_inventory_ids' => ['value' => true],
                ],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::RETURNS,
                'system' => Systems::NETSUITE,
            ],
            'destination' => [
                'factory' => Factories::REFUNDS_REFACTORED,
                'system' => Systems::SHOPIFY,
            ],
            'options' => [
                'source' => [
                    'database_record_type' => ['value' => 'Return'],
                ],
                'destination' => [
                    'notify_customer' => ['value' => true, 'user_configurable' => true],
                ],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::CUSTOMERS,
                'system' => Systems::NETSUITE,
            ],
            'destination' => [
                'factory' => Factories::CUSTOMERS,
                'system' => Systems::SHOPIFY,
            ],
            'options' => [
                'source' => [
                    // N/A
                ],
                'destination' => [
                    // N/A
                ],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::FULFILMENTS,
                'system' => Systems::NETSUITE,
            ],
            'destination' => [
                'factory' => Factories::FULFILMENTS,
                'system' => Systems::SHOPIFY,
            ],
            'options' => [
                'source' => [
                    // N/A
                ],
                'destination' => [
                    'notify_customer' => ['value' => true, 'user_configurable' => true]
                ],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::INVOICES,
                'system' => Systems::NETSUITE,
            ],
            'destination' => [
                'factory' => Factories::ORDER_UPDATES,
                'system' => Systems::SHOPIFY,
            ],
            'options' => [
                'source' => [
                    // N/A
                ],
                'destination' => [
                    'join_character' => ['value' => ','],
                    'process_tags' => ['value' => true]
                ],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::KITS,
                'system' => Systems::NETSUITE,
            ],
            'destination' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::SHOPIFY,
            ],
            'options' => [
                'source' => [
                    // N/A
                ],
                'destination' => [
                    // N/A
                ],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::NETSUITE,
            ],
            'destination' => [
                'factory' => Factories::ORDER_UPDATES,
                'system' => Systems::SHOPIFY,
            ],
            'options' => [
                'source' => [
                    // N/A
                ],
                'destination' => [
                    'join_character' => ['value' => ','],
                    'process_tags' => ['value' => true]
                ],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::PRODUCTS_REFACTORED,
                'system' => Systems::NETSUITE,
            ],
            'destination' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::SHOPIFY,
            ],
            'options' => [
                'source' => [
                    // N/A
                ],
                'destination' => [
                    'skip_metafields_update' => ['value' => true],
                    'update_inventory_items' => ['value' => true]
                ],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::SHOPIFY,
            ],
            'destination' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::PEOPLEVOX,
            ],
            'options' => [
                'source' => [
                    'page_size' => ['value' => 250],
                    'inject_meta_data' => ['value' => true],
                    'inject_customer_meta_data' => ['value' => true],
                    'get_order_risks' => ['value' => true],
                ],
                'destination' => [
                    'process_as_translated_batches' => ['value' => true],
                    'skip_customer_import' => ['value' => false],
                    'alert_on_sync_failure' => ['value' => true],
                ],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::SHOPIFY,
            ],
            'destination' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::PEOPLEVOX,
            ],
            'options' => [
                'source' => [
                    'page_size' => ['value' => 250],
                    'get_inventory_item_data' => ['value' => true],
                    'inject_meta_data' => ['value' => true],
                    'skip_products_with_no_sku' => ['value' => true],
                    'explode_tags' => ['value' => ':'],
                ],
                'destination' => [
                    'template_name' => ['value' => 'Item types'],
                ],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::TIMESTAMP_STOCK,
                'system' => Systems::PEOPLEVOX,
            ],
            'destination' => [
                'factory' => Factories::PVX_STOCK_LEVELS,
                'system' => Systems::SHOPIFY,
            ],
            'options' => [
                'source' => [
                    'page_size' => ['value' => 250],
                    'template_name' => ['value' => 'Incremental Changes Report v1'],
                    'static_system_chain' => ['value' => 'Shopify_Peoplevox'],
                    'combine_skus' => ['value' => true],
                    'include_columns' => ['value' => '[Item code],[Item current available quantity],[Item Barcode]'],
                    'pre_set_filters' => ['value' => true],
                ],
                'destination' => [
                    'skip_download_remote_records' => ['value' => true],
                    'fetch_missing_inventory_ids' => ['value' => true],
                    'static_system_chain' => ['value' => 'Shopify_Peoplevox'],
                ],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::RETURNS_SWEEPER,
                'system' => Systems::PEOPLEVOX,
            ],
            'destination' => [
                'factory' => Factories::REFUNDS_REFACTORED,
                'system' => Systems::SHOPIFY,
            ],
            'options' => [
                'source' => [
                    'template_name' => ['value' => 'Returns Summary'],
                    'include_columns' => ['value' => '[Is Reusable],[Return code],[Return date],[Quantity],[Item name],
                    [Sales order number],[Despatch date],[Return reason],[Return reason code],[Return condition],
                    [Item code],[Item barcode],[Channel name],[Site reference]'],
                ],
                'destination' => [
                    'notify_customer' => ['value' => true],
                    'common_ref' => ['value' => 'order_number'],
                ],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::FULFILMENT_SWEEPER,
                'system' => Systems::PEOPLEVOX,
            ],
            'destination' => [
                'factory' => Factories::FULFILMENTS,
                'system' => Systems::SHOPIFY,
            ],
            'options' => [
                'source' => [
                    'template_name' => ['value' => 'Despatch summary'],
                    'include_columns' => ['value' => '[Salesorder number],[Despatch number],[Tracking number],[Despatch date],
                    [Service],[Carrier],[Carrier reference],[Channel name],[No of items],[No. of parent items despatched],
                    [Parent],[Item code],[Item barcode],[Line],[Site reference]'],
                    'remove_fulfilments_without_tracking' => ['value' => true],
                ],
                'destination' => [
                    'notify_customer' => ['value' => true],
                    'common_ref' => ['value' => 'order_id'],
                ],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::SHOPIFY,
            ],
            'destination' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::DYNAMICS_NAV,
            ],
            'options' => [
                'source' => [
                    // N/A
                ],
                'destination' => [
                    'skip_customer_update' => ['value' => true],
                    'date_format' => ['value' => 'Y-m-d']
                ],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::REFUNDS,
                'system' => Systems::SHOPIFY,
            ],
            'destination' => [
                'factory' => Factories::RETURNS,
                'system' => Systems::DYNAMICS_NAV,
            ],
            'options' => [
                'source' => [
                    'filter_postage_refund' => ['value' => true],
                    'refund_item_properties' => ['value' => [
                        'Return code',
                        'Return reason',
                        'Return condition',
                        'Return date',
                        'Is Reusable'
                    ]]
                ],
                'destination' => [
                    'page' => ['value' => 'SalesReturnOrder']
                ],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::FULFILMENTS,
                'system' => Systems::DYNAMICS_NAV,
            ],
            'destination' => [
                'factory' => Factories::FULFILMENTS,
                'system' => Systems::SHOPIFY,
            ],
            'options' => [
                'source' => [
                    'action' => ['value' => 'ReadMultiple']
                ],
                'destination' => [
                    'force_non_partial_fulfilment' => ['value' => true],
                    'notify_customer' => ['value' => true]
                ],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::DYNAMICS_NAV,
            ],
            'destination' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::SHOPIFY,
            ],
            'options' => [
                'source' => [
                    'page' => ['value' => 'ItemCard'],
                    'action' => ['value' => 'ReadMultiple'],
                    'date_format' => ['value' => 'Y-m-d']
                ],
                'destination' => [
                    'update_inventory_items' => ['value' => true],
                    'fetch_missing_inventory_ids' => ['value' => true]
                ],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::STOCK_LEVELS,
                'system' => Systems::DYNAMICS_NAV,
            ],
            'destination' => [
                'factory' => Factories::STOCK_LEVELS,
                'system' => Systems::SHOPIFY,
            ],
            'options' => [
                'source' => [
                    'page' => ['value' => 'ItemList'],
                    'action' => ['value' => 'ReadMultiple']
                ],
                'destination' => [
                    'fetch_missing_inventory_ids' => ['value' => true]
                ],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::ORDERS_REBOUND,
                'system' => Systems::SHOPIFY,
            ],
            'destination' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::REBOUND,
            ],
            'options' => [
                'source' => [
                    'get_hs_code' => ['value' => true],
                    'get_coo_value' => ['value' => true],
                    'get_inventory_item_data' => ['value' => true],
                    'get_product_images' => ['value' => true],
                ],
                'destination' => [
                    'log_rebound_success' => ['value' => true]
                ],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::NETSUITE,
            ],
            'destination' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::PEOPLEVOX,
            ],
            'options' => [
                'source' => [
                    // N/A
                ],
                'destination' => [
                    'process_as_translated_batches' => ['value' => true]
                ],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::RECORDS,
                'system' => Systems::NETSUITE,
            ],
            'destination' => [
                'factory' => Factories::SUPPLIERS,
                'system' => Systems::PEOPLEVOX,
            ],
            'options' => [
                'source' => [
                    'netsuite_record_type' => ['value' => 'vendor'],
                    'ids_record_type' => ['value' => 'Supplier']
                ],
                'destination' => [
                    // N/A
                ],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::PRODUCTS_REFACTORED,
                'system' => Systems::NETSUITE,
            ],
            'destination' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::PEOPLEVOX,
            ],
            'options' => [
                'source' => [
                    'page_size' => ['value' => 100],
                    'timezone' => ['value' => 'GMT'],
                    'use_legacy_script' => ['value' => false]
                ],
                'destination' => [
                    'template_name' => ['value' => 'Item types']
                ],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::DESPATCH_PACKAGES,
                'system' => Systems::PEOPLEVOX,
            ],
            'destination' => [
                'factory' => Factories::FULFILMENTS,
                'system' => Systems::NETSUITE,
            ],
            'options' => [
                'source' => [
                    // None
                ],
                'destination' => [
                    'origin' => ['value' => 'Peoplevox', 'user_configurable' => true]
                ],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::FULFILMENT_SWEEPER,
                'system' => Systems::PEOPLEVOX,
            ],
            'destination' => [
                'factory' => Factories::FULFILMENTS,
                'system' => Systems::NETSUITE,
            ],
            'options' => [
                'source' => [
                    'template_name' => ['value' => 'Despatch summary'],
                    'include_columns' => ['value' => '[Salesorder number],[Despatch number],[Tracking number],[Despatch date],[Service],[Carrier],[Carrier reference],[Channel name],[No of items],[Item code],[Item barcode],[Line],[Site reference]'],
                    'match_fulfilment_using_system_chain' => ['value' => true]
                ],
                'destination' => [
                    'origin' => ['value' => 'Peoplevox', 'user_configurable' => true]
                ],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::ITEM_RECEIPTS,
                'system' => Systems::PEOPLEVOX,
            ],
            'destination' => [
                'factory' => Factories::ITEM_RECEIPTS_MR,
                'system' => Systems::NETSUITE,
            ],
            'options' => [
                'source' => [
                    'template_name' => ['value' => 'Goods in summary'],
                    'join_character' => ['value' => '-']
                ],
                'destination' => [
                    'origin' => ['value' => 'Peoplevox', 'user_configurable' => true],
                    'record_array_name' => ['value' => 'goodsreceipts'],
                    'source_id_field' => ['value' => 'unique_ref'],
                    'common_ref_field' => ['value' => 'unique_ref'],
                    'db_type' => ['value' => 'ItemReceipt'],
                ],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::STOCK,
                'system' => Systems::PEOPLEVOX,
            ],
            'destination' => [
                'factory' => Factories::INVENTORY_ADJUSTMENTS_MR,
                'system' => Systems::NETSUITE,
            ],
            'options' => [
                'source' => [
                    'template_name' => ['value' => 'Item movement history'],
                    'make_multiples' => ['value' => true],
                ],
                'destination' => [
                    'origin' => ['value' => 'Peoplevox', 'user_configurable' => true]
                ],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::RETURNS,
                'system' => Systems::REBOUND,
            ],
            'destination' => [
                'factory' => Factories::RETURNS,
                'system' => Systems::NETSUITE,
            ],
            'options' => [
                'source' => [
                    'source_record_id_field' => ['value' => 'common_ref'],
                    'source_record_type' => ['value' => 'Order'],
                    'return_field' => ['value' => 'source_id']
                ],
                'destination' => [
                    'origin' => ['value' => 'Rebound', 'user_configurable' => true],
                    'returns_type' => ['value' => 'returns'],
                    'create_itemreceipt' => ['value' => false],
                    'create_creditmemo' => ['value' => true],
                    'set_net_ra_price' => ['value' => false],
                    'set_net_cm_price' => ['value' => false],
                    'set_gross_ra_price' => ['value' => true],
                    'set_gross_cm_price' => ['value' => true],
                    'skip_customer_refund_after_credit_memo' => ['value' => true],
                    'restock' => ['value' => true],
                    'lookup_order_id' => ['value' => true],
                    'match_order_on_name' => ['value' => 'custbody_pwks_remote_order_name'],
                    'shipping_line_sku' => ['value' => 'Shipping and Handling charge']
                ],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::NETSUITE,
            ],
            'destination' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::REBOUND,
            ],
            'options' => [
                'source' => [
                    'date_format' => ['value' => 'd/m/Y'],
                    'netsuite_common_ref_field' => ['value' => 'tranid'],
                    'item_custom_fields' => ['value' => '{\"custcol_coo\": \"custitem_tf_countrycode\", \"custcol_item_hts_code\": \"custitem_commodity_code\"}'],
                    'discount_itemid' => ['value' => '43'],
                    'download_remote_items' => ['value' => true]
                ],
                'destination' => [
                    // None
                ],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::DYNAMICS_NAV,
            ],
            'destination' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::PEOPLEVOX,
            ],
            'options' => [
                'source' => [
                    'page' => ['value' => 'ItemCard'],
                    'action' => ['value' => 'ReadMultiple'],
                    'date_format' => ['value' => 'd/m/Y..'],
                    'configurable_on' => ['value' => 'size,colour'],
                    'skip_product_prices' => ['value' => true],
                    'product_tags' => ['value' => 'Description,Country_Region_of_Origin_Code,Tariff_No'],
                    'static_product_tags' => ['value' => 'fullprice'],
                    'currency_code' => ['value' => 'GBP'],
                ],
                'destination' => [
                    'template_name' => ['value' => 'Item types']
                ],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::PURCHASE_ORDERS,
                'system' => Systems::DYNAMICS_NAV,
            ],
            'destination' => [
                'factory' => Factories::PURCHASE_ORDERS,
                'system' => Systems::PEOPLEVOX,
            ],
            'options' => [
                'source' => [
                    'page' => ['value' => 'PurchaseOrder'],
                    'log_out_items_on_po' => ['value' => true],
                    'action' => ['value' => 'ReadMultiple'],
                    'date_format' => ['value' => 'd/m/Y..'],
                ],
                'destination' => [
                    'log_out_pvx_request' => ['value' => true]
                ],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::ITEM_RECEIPTS,
                'system' => Systems::PEOPLEVOX,
            ],
            'destination' => [
                'factory' => Factories::ITEM_RECEIPTS,
                'system' => Systems::DYNAMICS_NAV,
            ],
            'options' => [
                'source' => [
                    'store_individual_item_receipt_refs' => ['value' => true],
                    'template_name' => ['value' => 'Goods in summary'],
                    'site' => ['value' => 'PrimarySite']
                ],
                'destination' => [
                    'page' => ['value' => 'PurchaseOrder'],
                    'store_individual_item_receipt_refs' => ['value' => true],
                    'log_lines_being_sent_to_nav' => ['value' => true],
                    'drop_already_received_line_items' => ['value' => true],
                    'adjust_quantities_if_higher' => ['value' => true],
                    'store_remaining_items' => ['value' => true],
                    'remove_0_quantity_line_items' => ['value' => false]
                ],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::STOCK,
                'system' => Systems::PEOPLEVOX,
            ],
            'destination' => [
                'factory' => 'InventoryAdjustments',
                'system' => Systems::DYNAMICS_NAV,
            ],
            'options' => [
                'source' => [
                    'page_size' => ['value' => 250],
                    'template_name' => ['value' => 'Item movement history'],
                ],
                'destination' => [
                    'page' => ['value' => 'ItemJournal'],
                    'journal_template_name' => ['value' => 'ITEM'],
                    'journal_batch_name' => ['value' => 'DEFAULT'],
                    'entry_type' => ['value' => 'Negative_Adjmt'],
                    'document_number' => ['value' => '00015']
                ],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::LINNWORKS,
            ],
            'destination' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::VEND,
            ],
            'options' => [
                'source' => [
                    'filter_out_unchanged_products' => ['value' => true],
                ],
                'destination' => [],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::STOCK,
                'system' => Systems::LINNWORKS,
            ],
            'destination' => [
                'factory' => Factories::STOCK,
                'system' => Systems::VEND,
            ],
            'options' => [
                'source' => [
                    'filter_out_unchanged_stocklevels' => ['value' => true],
                ],
                'destination' => [],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::SALES,
                'system' => Systems::VEND,
            ],
            'destination' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::LINNWORKS,
            ],
            'options' => [
                'source' => [
                    'date_format' => ['value' => 'Y-m-d\\TH:i:s\\Z'],
                ],
                'destination' => [],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::BIGCOMMERCE
            ],
            'destination' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::PEOPLEVOX,
            ],
            'options' => [
                'source' => [
                    'skip_shipping_zone' => ['value' => 'EU'],
                    'store_failed_order' => ['value' =>  true],
                ],
                'destination' => [
                    'process_as_translated_batches' => ['value' => true],
                    'skip_customer_import' => ['value' => false],
                    'alert_on_sync_failure' => ['value' => true],
                ]
            ]
        ],
        [
            'source' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::BIGCOMMERCE
            ],
            'destination' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::NETSUITE
            ],
            'options' => [
                'source' => [
                    'skip_shipping_zone' => ['value' => 'EU'],
                    'store_failed_order' => ['value' =>  true],
                ],
                'destination' => [
                    'origin' => ['value' => 'BigCommerce', 'user_configurable' => true],
                ]
            ]
        ],
        [
            'source' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::BIGCOMMERCE
            ],
            'destination' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::DYNAMICS_NAV,
            ],
            'options' => [
                'source' => [
                    'skip_shipping_zone' => ['value' => 'EU'],
                    'store_failed_order' => ['value' =>  true],
                ],
                'destination' => [
                    'skip_customer_update' => ['value' => true],
                    'date_format' => ['value' => 'Y-m-d']
                ],
            ]
        ],
        [
            'source' => [
                'factory' => Factories::REFUNDS,
                'system' => Systems::BIGCOMMERCE
            ],
            'destination' => [
                'factory' => Factories::RETURNS,
                'system' => Systems::NETSUITE
            ],
            'options' => [
                'source' => [
                    'ignore_none_shipped' => ['value' => true],
                ],
                'destination' => [
                    'origin' => ['value' => 'BigCommerce', 'user_configurable' => true],
                ]
            ]
        ],
        [
            'source' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::BIGCOMMERCE
            ],
            'destination' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::PEOPLEVOX,
            ],
            'options' => [
                'source' => [
                    'get_custom_fields' => ['value' => true],
                    'get_brand_data' => ['value' => true]
                ],
                'destination' => [
                    'template_name' => ['value' => 'Item types'],
                ]
            ]
        ],
        [
            'source' => [
                'factory' => Factories::FULFILMENTS,
                'system' => Systems::NETSUITE
            ],
            'destination' => [
                'factory' => Factories::FULFILMENTS,
                'system' => Systems::BIGCOMMERCE
            ],
            'options' => [
                'source' => [],
                'destination' => [
                    'skip_no_items' => ['value' => true],
                    'handle_multi_line_sku' => ['value' => true],
                    'notify_customer' => ['value' => true]
                ]
            ]
        ],
        [
            'source' => [
                'factory' => Factories::FULFILMENT_SWEEPER,
                'system' => Systems::PEOPLEVOX
            ],
            'destination' => [
                'factory' => Factories::FULFILMENTS,
                'system' => Systems::BIGCOMMERCE
            ],
            'options' => [
                'source' => [
                    'template_name' => ['value' => 'Despatch summary'],
                    'include_columns' => ['value' => '[Salesorder number],[Despatch number],[Tracking number],[Despatch date],
                    [Service],[Carrier],[Carrier reference],[Channel name],[No of items],[No. of parent items despatched],
                    [Parent],[Item code],[Item barcode],[Line],[Site reference]'],
                    'remove_fulfilments_without_tracking' => ['value' => true],
                ],
                'destination' => [
                    'skip_no_items' => ['value' => true],
                    'handle_multi_line_sku' => ['value' => true],
                    'notify_customer' => ['value' => true]
                ]
            ]
        ],
        [
            'source' => [
                'factory' => Factories::PRODUCTS_REFACTORED,
                'system' => Systems::NETSUITE
            ],
            'destination' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::BIGCOMMERCE
            ],
            'options' => [
                'source' => [],
                'destination' => [
                    'delete_invalid_customfields' => ['value' => true]
                ]
            ]
        ],
        [
            'source' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::DYNAMICS_NAV,
            ],
            'destination' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::BIGCOMMERCE
            ],
            'options' => [
                'source' => [
                    'page' => ['value' => 'ItemCard'],
                    'action' => ['value' => 'ReadMultiple'],
                    'date_format' => ['value' => 'd/m/Y..'],
                    'configurable_on' => ['value' => 'size,colour'],
                    'skip_product_prices' => ['value' => true],
                    'product_tags' => ['value' => 'Description,Country_Region_of_Origin_Code,Tariff_No'],
                    'static_product_tags' => ['value' => 'fullprice'],
                    'currency_code' => ['value' => 'GBP'],
                ],
                'destination' => [
                    'delete_invalid_customfields' => ['value' => true]
                ]
            ]
        ],
        [
            'source' => [
                'factory' => Factories::CUSTOMERS,
                'system' => Systems::NETSUITE,
            ],
            'destination' => [
                'factory' => Factories::CUSTOMERS,
                'system' => Systems::BIGCOMMERCE
            ],
            'options' => [
                'source' => [],
                'destination' => [
                    'get_remote_records' => ['value' => true],
                    'process_addresses' => ['value' => true],
                    'force_password_reset' => ['value' => false]
                ]
            ]
        ],
        [
            'source' => [
                'factory' => Factories::STOCK_LEVELS,
                'system' => Systems::KHAOS,
            ],
            'destination' => [
                'factory' => Factories::STOCK_LEVELS,
                'system' => Systems::MAGENTO_2,
            ],
            'options' => [
                'source' => [
                    'no_item_filtering' => ['value' => true]
                ],
                'destination' => [
                    'fetch_missing_inventory_ids' => ['value' => true]
                ],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::FULFILMENTS,
                'system' => Systems::KHAOS,
            ],
            'destination' => [
                'factory' => Factories::FULFILMENTS,
                'system' => Systems::SHOPIFY,
            ],
            'options' => [
                'source' => [
                    'allow_multiple_invoices' => ['value' => true]
                ],
                'destination' => [
                    'notify_customer' => ['value' => true, 'user_configurable' => true],
                    'location_id' => ['value' => '12345', 'user_configurable' => true]
                ],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::STOCK_LEVELS,
                'system' => Systems::KHAOS,
            ],
            'destination' => [
                'factory' => Factories::STOCK_LEVELS,
                'system' => Systems::SHOPIFY,
            ],
            'options' => [
                'source' => [
                    'no_item_filtering' => ['value' => true]
                ],
                'destination' => [
                    'fetch_missing_inventory_ids' => ['value' => true],
                    'location_id' => ['value' => '12345', 'user_configurable' => true]
                ],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::SHOPIFY,
            ],
            'destination' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::KHAOS,
            ],
            'options' => [
                'source' => [
                    'page_size' => ['value' => 250],
                    'inject_meta_data' => ['value' => true],
                    'inject_customer_meta_data' => ['value' => true],
                    'get_order_risks' => ['value' => true],
                ],
                'destination' => [],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::FULFILMENTS,
                'system' => Systems::KHAOS,
            ],
            'destination' => [
                'factory' => Factories::FULFILMENTS,
                'system' => Systems::MAGENTO_2,
            ],
            'options' => [
                'source' => [
                    'page_size' => ['value' => 250],
                    'inject_meta_data' => ['value' => true],
                    'inject_customer_meta_data' => ['value' => true],
                    'get_order_risks' => ['value' => true],
                ],
                'destination' => [
                    'notify_customer' => ['value' => true]
                ],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::KHAOS,
            ],
            'destination' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::MAGENTO_2,
            ],
            'options' => [
                'source' => [],
                'destination' => [
                    'set_new_products_disabled' => ['value' => false, 'user_configurable' => true],
                    'scope' => ['value' => 'all', 'user_configurable' => true]
                ],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::KHAOS,
            ],
            'destination' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::SHOPIFY,
            ],
            'options' => [
                'source' => [],
                'destination' => [
                    'create_products_disabled' => ['value' => false, 'user_configurable' => true]
                ],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::STOCK_REFACTORED,
                'system' => Systems::NETSUITE,
            ],
            'destination' => [
                'factory' => Factories::STOCK_LEVELS,
                'system' => Systems::BIGCOMMERCE
            ],
            'options' => [
                'source' => [
                    'use_pagination' => ['value' => true],
                ],
                'destination' => [
                    'fetch_missing_inventory_ids' => ['value' => true],
                    'skip_download_remote_records' => ['value' => true]
                ]
            ]
        ],
        [
            'source' => [
                'factory' => Factories::STOCK_LEVELS,
                'system' => Systems::DYNAMICS_NAV,
            ],
            'destination' => [
                'factory' => Factories::STOCK_LEVELS,
                'system' => Systems::BIGCOMMERCE
            ],
            'options' => [
                'source' => [
                    'page' => ['value' => 'ItemList'],
                    'action' => ['value' => 'ReadMultiple']
                ],
                'destination' => [
                    'fetch_missing_inventory_ids' => ['value' => true],
                    'skip_download_remote_records' => ['value' => true]
                ]
            ]
        ],
        [
            'source' => [
                'factory' => Factories::TIMESTAMP_STOCK,
                'system' => Systems::PEOPLEVOX,
            ],
            'destination' => [
                'factory' => Factories::STOCK_LEVELS,
                'system' => Systems::BIGCOMMERCE
            ],
            'options' => [
                'source' => [
                    'page_size' => ['value' => 250],
                    'template_name' => ['value' => 'Incremental Changes Report v1'],
                    'static_system_chain' => ['value' => 'Shopify_Peoplevox'],
                    'combine_skus' => ['value' => true],
                    'include_columns' => ['value' => '[Item code],[Item current available quantity],[Item Barcode]'],
                    'pre_set_filters' => ['value' => true],
                ],
                'destination' => [
                    'fetch_missing_inventory_ids' => ['value' => true],
                    'skip_download_remote_records' => ['value' => true]
                ]
            ]
        ],
        [
            'source' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::MAGENTO_2
            ],
            'destination' => [
                'factory' => Factories::NE_ORDERS,
                'system' => Systems::DYNAMICS_NAV,
            ],
            'options' => [
                'source' => [
                    'get_order_comments' => ['value' => true]
                ],
                'destination' => [
                    'skip_customer_update' => ['value' => true],
                    'date_format' => ['value' => 'Y-m-d']
                ],
            ]
        ],
        [
            'source' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::MAGENTO_2
            ],
            'destination' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::PEOPLEVOX,
            ],
            'options' => [
                'source' => [
                    'get_order_comments' => ['value' => true]
                ],
                'destination' => [
                    'process_as_translated_batches' => ['value' => true],
                    'skip_customer_import' => ['value' => false],
                    'alert_on_sync_failure' => ['value' => true],
                ]
            ]
        ],
        [
            'source' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::MAGENTO_2
            ],
            'destination' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::NETSUITE
            ],
            'options' => [
                'source' => [
                    'get_order_comments' => ['value' => true]
                ],
                'destination' => [
                    'origin' => ['value' => 'BigCommerce', 'user_configurable' => true],
                ]
            ]
        ],
        [
            'source' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::MAGENTO_2
            ],
            'destination' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::PEOPLEVOX,
            ],
            'options' => [
                'source' => [
                    'option_attributes' => ['value' => 'color,size']
                ],
                'destination' => [
                    'template_name' => ['value' => 'Item types'],
                ]
            ]
        ],
        [
            'source' => [
                'factory' => Factories::REFUNDS,
                'system' => Systems::MAGENTO_2
            ],
            'destination' => [
                'factory' => Factories::RETURNS,
                'system' => Systems::NETSUITE,
            ],
            'options' => [
                'source' => [],
                'destination' => [
                    'record_mode' => ['value' => 'dynamic'],
                    'record_type' => ['value' => 'creditmemo'],
                    'returns_type' => ['value' => 'refunds'],
                    'create_creditmemo' => ['value' => true],
                    'create_itemreceipt' => ['value' => true],
                    'skip_customer_refund_after_credit_memo' => ['value' => false],
                    'use_gross_price' => ['value' => true],
                    'use_net_price' => ['value' => false],
                    'set_gross_cm_price' => ['value' => true],
                    'set_gross_ra_price' => ['value' => true],
                ],
            ]
        ],
        [
            'source' => [
                'factory' => Factories::CUSTOMERS,
                'system' => Systems::MAGENTO_2
            ],
            'destination' => [
                'factory' => Factories::CUSTOMERS,
                'system' => Systems::NETSUITE,
            ],
            'options' => [
                'source' => [],
                'destination' => [
                    'origin' => ['value' => 'Shopify', 'user_configurable' => true],
                    'payment_type' => ['value' => 'Deposit'],
                    'use_gross_price' => ['value' => true],
                    'use_net_price' => ['value' => false],
                    'record_array_name' => ['value' => 'orders'],
                    'db_type' => ['value' => 'Order'],
                    'common_ref_field' => ['value' => 'order_number'],
                    'source_id_field' => ['value' => 'order_id'],
                    'record_mode' => ['value' => 'dynamic'],
                    'sku_field' => ['value' => 'itemid'],
                ],
            ]
        ],
        [
            'source' => [
                'factory' => Factories::CUSTOMERS,
                'system' => Systems::MAGENTO_2
            ],
            'destination' => [
                'factory' => Factories::NE_CUSTOMERS,
                'system' => Systems::DYNAMICS_NAV
            ],
            'options' => [
                'source' => [],
                'destination' => [
                    'customer_page' => ['value' => 'Page/InboundCustomer']
                ]
            ]
        ],
        [
            'source' => [
                'factory' => Factories::PRODUCTS_REFACTORED,
                'system' => Systems::NETSUITE
            ],
            'destination' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::MAGENTO_2
            ],
            'options' => [
                'source' => [],
                'destination' => [
                    'set_new_products_disabled' => ['value' => true],
                    'add_or_update_website_assignment' => ['value' => true],
                    'scope' => ['value' => 'all'],
                    'product_visibility' => ['value' => 1],
                ]
            ]
        ],
        [
            'source' => [
                'factory' => Factories::NE_PRODUCTS,
                'system' => Systems::DYNAMICS_NAV
            ],
            'destination' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::MAGENTO_2
            ],
            'options' => [
                'source' => [
                    'page' => ['value' => 'OutboundItem'],
                    'action' => ['value' => 'ReadMultiple'],
                    'create_fake_variant_when_no_data' => ['value' => false],
                    'configurable_on' => ['value' => 'Colour,Size'],
                    'variant_code_delimiter' => ['value' => '-'],
                    'make_nav_attributes_global' => ['value' => false]
                ],
                'destination' => [
                    'set_new_products_disabled' => ['value' => true],
                    'add_or_update_website_assignment' => ['value' => true],
                    'scope' => ['value' => 'all'],
                    'product_visibility' => ['value' => 1],
                ]
            ]
        ],
        [
            'source' => [
                'factory' => Factories::FULFILMENTS,
                'system' => Systems::NETSUITE,
            ],
            'destination' => [
                'factory' => Factories::FULFILMENTS,
                'system' => Systems::MAGENTO_2
            ],
            'options' => [
                'source' => [],
                'destination' => [
                    'notify_customer' => ['value' => true]
                ]
            ]
        ],
        [
            'source' => [
                'factory' => Factories::FULFILMENT_SWEEPER,
                'system' => Systems::PEOPLEVOX,
            ],
            'destination' => [
                'factory' => Factories::FULFILMENTS,
                'system' => Systems::MAGENTO_2
            ],
            'options' => [
                'source' => [
                    'template_name' => ['value' => 'Despatch summary'],
                    'include_columns' => ['value' => '[Salesorder number],[Despatch number],[Tracking number],[Despatch date],
                    [Service],[Carrier],[Carrier reference],[Channel name],[No of items],[No. of parent items despatched],
                    [Parent],[Item code],[Item barcode],[Line],[Site reference]'],
                    'remove_fulfilments_without_tracking' => ['value' => true],
                ],
                'destination' => [
                    'notify_customer' => ['value' => true]
                ]
            ]
        ],
        [
            'source' => [
                'factory' => Factories::DESPATCH_PACKAGES,
                'system' => Systems::PEOPLEVOX,
            ],
            'destination' => [
                'factory' => Factories::FULFILMENTS,
                'system' => Systems::MAGENTO_2
            ],
            'options' => [
                'source' => [],
                'destination' => [
                    'notify_customer' => ['value' => true]
                ]
            ]
        ],
        [
            'source' => [
                'factory' => Factories::STOCK_REFACTORED,
                'system' => Systems::NETSUITE,
            ],
            'destination' => [
                'factory' => Factories::STOCK_LEVELS,
                'system' => Systems::MAGENTO_2,
            ],
            'options' => [
                'source' => [
                    'use_pagination' => ['value' => true],
                ],
                'destination' => [
                    'stock_channel' => ['value' => 'netsuite', 'user_configurable' => true]
                ],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::TIMESTAMP_STOCK,
                'system' => Systems::PEOPLEVOX
            ],
            'destination' => [
                'factory' => Factories::STOCK_LEVELS,
                'system' => Systems::MAGENTO_2
            ],
            'options' => [
                'source' => [
                    'page_size' => ['value' => 250],
                    'template_name' => ['value' => 'Incremental Changes Report v1'],
                    'static_system_chain' => ['value' => 'Shopify_Peoplevox'],
                    'combine_skus' => ['value' => true],
                    'include_columns' => ['value' => '[Item code],[Item current available quantity],[Item Barcode]'],
                    'pre_set_filters' => ['value' => true],
                ],
                'destination' => [
                    'stock_channel' => ['value' => 'netsuite', 'user_configurable' => true]
                ]
            ]
        ],
        [
            'source' => [
                'factory' => Factories::NE_STOCK_LEVELS,
                'system' => Systems::DYNAMICS_NAV
            ],
            'destination' => [
                'factory' => Factories::STOCK_LEVELS,
                'system' => Systems::MAGENTO_2
            ],
            'options' => [
                'source' => [
                    'page' => ['value' => 'OutboundStock'],
                    'action' => ['value' => 'ReadMultiple']
                ],
                'destination' => [
                    'stock_channel' => ['value' => 'netsuite', 'user_configurable' => true]
                ]
            ]
        ],
        [
            'source' => [
                'factory' => Factories::RETURNS_SWEEPER,
                'system' => Systems::PEOPLEVOX,
            ],
            'destination' => [
                'factory' => Factories::RETURNS,
                'system' => Systems::MAGENTO_2
            ],
            'options' => [
                'source' => [
                    'template_name' => ['value' => 'Returns Summary'],
                    'include_columns' => ['value' => '[Is Reusable],[Return code],[Return date],[Quantity],[Item name],
                    [Sales order number],[Despatch date],[Return reason],[Return reason code],[Return condition],
                    [Item code],[Item barcode],[Channel name],[Site reference]'],
                ],
                'destination' => [
                    'attempt_refund' => ['value' => true],
                    'refund_type' => ['value' => 'invoice']
                ]
            ]
        ],
        [
            'source' => [
                'factory' => Factories::NE_CUSTOMERS,
                'system' => Systems::DYNAMICS_NAV
            ],
            'destination' => [
                'factory' => Factories::CUSTOMERS,
                'system' => Systems::MAGENTO_2
            ],
            'options' => [
                'source' => [
                    'page' => ['value' => 'OutboundCustomer'],
                    'action' => ['value' => 'ReadMultiple'],
                    'skip_records_with_no_email' => ['value' => true]
                ],
                'destination' => []
            ]
        ],
        [
            'source' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::COMMERCETOOLS,
            ],
            'destination' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::PEOPLEVOX,
            ],
            'options' => [
                'source' => [
                    'origin' => ['value' => 'CommerceTools'],
                    'resync' => ['value' => true],
                    'skip_category_pull' => ['value' => true],
                    'date_format' => ['value' => 'Y-m-d\\TH:i:s'],
                ],
                'destination' => [
                    'template_name' => ['value' => 'Item types'],
                ],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::COMMERCETOOLS,
            ],
            'destination' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::PEOPLEVOX,
            ],
            'options' => [
                'source' => [
                    'origin' => ['value' => 'CommerceTools'],
                    'date_format' => ['value' => 'Y-m-d\\TH:i:s'],
                ],
                'destination' => [
                    'process_as_translated_batches' => ['value' => true],
                ],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::COMMERCETOOLS,
            ],
            'destination' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::NETSUITE,
            ],
            'options' => [
                'source' => [
                    'origin' => ['value' => 'CommerceTools'],
                    'date_format' => ['value' => 'Y-m-d\\TH:i:s'],
                ],
                'destination' => [
                    'origin' => ['value' => 'CommerceTools'],
                ],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::STOCK,
                'system' => Systems::PEOPLEVOX,
            ],
            'destination' => [
                'factory' => Factories::STOCK_LEVELS,
                'system' => Systems::COMMERCETOOLS,
            ],
            'options' => [
                'source' => [
                    'template_name' => ['value' => 'Incremental Changes Report v1'],
                    'static_system_chain' => ['value' => 'CommerceTools_Peoplevox'],
                    'combine_skus' => ['value' => true],
                    'include_columns' => ['value' => '[Item code],[Item current available quantity],[Item Barcode]'],
                ],
                'destination' => [
                    'static_system_chain' => ['value' => 'CommerceTools_Peoplevox'],
                    'skip_download_remote_records' => ['value' => true],
                    'skip_full_inventory_pull' => ['value' => true],
                    'fetch_missing_inventory_ids' => ['value' => true],
                ]
            ],
        ],
        [
            'source' => [
                'factory' => Factories::STOCK_LEVELS,
                'system' => Systems::NETSUITE,
            ],
            'destination' => [
                'factory' => Factories::STOCK_LEVELS,
                'system' => Systems::COMMERCETOOLS,
            ],
            'options' => [
                'source' => [
                    'date_format' => ['value' => 'd/m/Y'],
                ],
                'destination' => [
                    'skip_download_remote_records' => ['value' => true],
                    'full_inventory_update' => ['value' => false],
                    'fetch_missing_inventory_ids' => ['value' => true],
                ],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::FULFILMENTS,
                'system' => Systems::NETSUITE,
            ],
            'destination' => [
                'factory' => Factories::FULFILMENTS,
                'system' => Systems::COMMERCETOOLS,
            ],
            'options' => [
                'source' => [],
                'destination' => [
                    'notify_customer' => ['value' => true],
                    'record_type' => ['value' => 'Fulfilment'],
                    'data_array' => ['value' => 'fulfilments'],
                    'update_order_state' => ['value' => true],
                    'transition_status' => ['value' => 'order-state-fulfilled'],
                ],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::MIRAKL,
            ],
            'destination' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::NETSUITE,
            ],
            'options' => [
                'source' => [],
                'destination' => [
                    'origin' => ['value' => 'Mirakl', 'user_configurable' => true],
                ]
            ],
        ],
        [
            'source' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::MIRAKL,
            ],
            'destination' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::PEOPLEVOX,
            ],
            'options' => [
                'source' => [],
                'destination' => [
                    'process_as_translated_batches' => ['value' => true],
                    'skip_customer_import' => ['value' => false, 'user_configurable' => true],
                    'alert_on_sync_failure' => ['value' => true]
                ],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::SALES,
                'system' => Systems::VEND,
            ],
            'destination' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::LINNWORKS,
            ],
            'options' => [
                'source' => [
                    'date_format' => ['value' => 'Y-m-d\\TH:i:s\\Z'],
                ],
                'destination' => [],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::MIRAKL,
            ],
            'destination' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::LINNWORKS,
            ],
            'options' => [
                'source' => [],
                'destination' => [],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::PRODUCTS_REFACTORED,
                'system' => Systems::NETSUITE,
            ],
            'destination' => [
                'factory' => Factories::OFFERS,
                'system' => Systems::MIRAKL,
            ],
            'options' => [
                'source' => [],
                'destination' => [],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::CREDIT_MEMOS,
                'system' => Systems::NETSUITE,
            ],
            'destination' => [
                'factory' => Factories::REFUNDS,
                'system' => Systems::MIRAKL,
            ],
            'options' => [
                'source' => [],
                'destination' => [],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::RETURNS_SWEEPER,
                'system' => Systems::PEOPLEVOX,
            ],
            'destination' => [
                'factory' => Factories::REFUNDS,
                'system' => Systems::MIRAKL,
            ],
            'options' => [
                'source' => [],
                'destination' => [],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::FULFILMENTS,
                'system' => Systems::NETSUITE,
            ],
            'destination' => [
                'factory' => Factories::ORDER_TRACKING,
                'system' => Systems::MIRAKL,
            ],
            'options' => [
                'source' => [],
                'destination' => [],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::FULFILMENT_SWEEPER,
                'system' => Systems::PEOPLEVOX,
            ],
            'destination' => [
                'factory' => Factories::ORDER_TRACKING,
                'system' => Systems::MIRAKL,
            ],
            'options' => [
                'source' => [
                    'template_name' => ['value' => 'Despatch summary'],
                    'include_columns' => ['value' => '[Salesorder number],[Despatch number],[Tracking number],[Despatch date],
                    [Service],[Carrier],[Carrier reference],[Channel name],[No of items],[No. of parent items despatched],
                    [Parent],[Item code],[Item barcode],[Line],[Site reference]'],
                    'remove_fulfilments_without_tracking' => ['value' => true, 'user_configurable' => true],
                    'match_fulfilment_using_system_chain' => ['value' => true]
                ],
                'destination' => [],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::DESPATCH_PACKAGES,
                'system' => Systems::PEOPLEVOX,
            ],
            'destination' => [
                'factory' => Factories::ORDER_TRACKING,
                'system' => Systems::MIRAKL,
            ],
            'options' => [
                'source' => [
                    'remove_fulfilments_without_tracking' => ['value' => true, 'user_configurable' => true],
                    'match_fulfilment_using_system_chain' => ['value' => true]
                ],
                'destination' => [],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::TIMESTAMP_STOCK,
                'system' => Systems::PEOPLEVOX
            ],
            'destination' => [
                'factory' => Factories::STOCK_LEVELS,
                'system' => Systems::WOOCOMMERCE
            ],
            'options' => [
                'source' => [
                    'template_name' => ['value' => 'PWKS Incremental Changes Report v1 with grouping'],
                    'static_system_chain' => ['value' => 'Woocommerce_Peoplevox'],
                    'include_columns' => ['value' => '[Item code],[Item current available quantity],[Item Barcode]'],
                    'combine_skus' => ['value' => true],
                    'pre_set_filters' => ['value' => true],
                ],
                'destination' => [
                    'skip_download_remote_records' => ['value' => true],
                    'skip_fetch_from_woo' => ['value' => true]
                ],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::WOOCOMMERCE
            ],
            'destination' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::PEOPLEVOX
            ],
            'options' => [
                'source' => [],
                'destination' => [
                    'process_as_translated_batches' => ['value' => true],
                    'skip_customer_import' => ['value' => false],
                    'alert_on_sync_failure' => ['value' => true],
                ],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::NE_PRODUCTS,
                'system' => Systems::DYNAMICS_NAV
            ],
            'destination' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::WOOCOMMERCE
            ],
            'options' => [
                'source' => [
                    'page' => ['value' => 'OutboundItem'],
                    'action' => ['value' => 'ReadMultiple'],
                    'date_format' => ['value' => 'mdY Hi..'],
                ],
                'destination' => [
                    'fetch_missing_products' => ['value' => true],
                    'create_products_disabled' => ['value' => true],
                    'disabled_status' => ['value' => 'private'],
                    'set_image_flag' => ['value' => 'both'],
                    'match_categories_by_slug' => ['value' => true]
                ],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::NE_STOCK_LEVELS,
                'system' => Systems::DYNAMICS_NAV
            ],
            'destination' => [
                'factory' => Factories::STOCK_LEVELS,
                'system' => Systems::WOOCOMMERCE
            ],
            'options' => [
                'source' => [
                    'page' => ['value' => 'OutboundStock'],
                    'action' => ['value' => 'ReadMultiple']
                ],
                'destination' => [
                    'stock_channel' => ['value' => 'netsuite', 'user_configurable' => true]
                ],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::NE_SALES_DOCUMENT,
                'system' => Systems::DYNAMICS_NAV
            ],
            'destination' => [
                'factory' => Factories::FULFILMENTS,
                'system' => Systems::WOOCOMMERCE
            ],
            'options' => [
                'source' => [
                    'page' => ['value' => 'OutboundSalesDocument'],
                    'action' => ['value' => 'ReadMultiple']
                ],
                'destination' => [
                    'notify_customer' => ['value' => true],
                    'common_ref_field' => ['value' => 'order_id']
                ],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::WOOCOMMERCE
            ],
            'destination' => [
                'factory' => Factories::NE_ORDERS,
                'system' => Systems::DYNAMICS_NAV
            ],
            'options' => [
                'source' => [
                    'order_status_whitelist' => ['value' => ["processing"]],
                    'fetch_extended_customer_data' => ['value' => true],
                    'format_meta_data' => ['value' => true],
                    'max_attempts' => ['value' => 7]
                ],
                'destination' => [
                    'skip_customer_update' => ['value' => true],
                    'date_format' => ['value' => 'Y-m-d']
                ],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::SHOPIFY,
            ],
            'destination' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::BRIGHTPEARL,
            ],
            'options' => [
                'source' => [
                    'page_size' => ['value' => 250],
                    'inject_meta_data' => ['value' => true],
                    'inject_customer_meta_data' => ['value' => true],
                    'get_order_risks' => ['value' => true],
                ],
                'destination' => [],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::BRIGHTPEARL,
            ],
            'destination' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::PEOPLEVOX,
            ],
            'options' => [
                'source' => [],
                'destination' => [
                    'template_name' => ['value' => 'Item types'],
                ],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::SHOPIFY,
            ],
            'destination' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::BRIGHTPEARL,
            ],
            'options' => [
                'source' => [
                    'page_size' => ['value' => 250],
                    'get_inventory_item_data' => ['value' => true],
                    'inject_meta_data' => ['value' => true],
                    'skip_products_with_no_sku' => ['value' => true],
                    'explode_tags' => ['value' => ':'],
                ],
                'destination' => [],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::LINNWORKS,
            ],
            'destination' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::BRIGHTPEARL,
            ],
            'options' => [
                'source' => [
                    'filter_out_unchanged_products' => ['value' => true],

                ],
                'destination' => [],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::FULFILMENT_SWEEPER,
                'system' => Systems::PEOPLEVOX,
            ],
            'destination' => [
                'factory' => Factories::SHIPMENTS,
                'system' => Systems::BRIGHTPEARL,
            ],
            'options' => [
                'source' => [
                    'template_name' => ['value' => 'Despatch summary'],
                    'include_columns' => ['value' => '[Salesorder number],[Despatch number],[Tracking number],
                    [Despatch date],[Service],[Carrier],[Carrier reference],[Channel name],[No of items],[No. of parent items despatched],
                    [Parent],[Item code],[Item barcode],[Line],[Site reference]'],
                ],
                'destination' => [],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::RETURNS_SWEEPER,
                'system' => Systems::PEOPLEVOX,
            ],
            'destination' => [
                'factory' => Factories::RETURNS,
                'system' => Systems::BRIGHTPEARL,
            ],
            'options' => [
                'source' => [
                    'template_name' => ['value' => 'Returns Summary'],
                    'include_columns' => ['value' => '[Is Reusable],[Return code],[Return date],[Quantity],[Item name],
                    [Sales order number],[Despatch date],[Return reason],[Return reason code],[Return condition],
                    [Item code],[Item barcode],[Channel name],[Site reference]'],
                ],
                'destination' => [],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::CUSTOMERS,
                'system' => Systems::SHOPIFY,
            ],
            'destination' => [
                'factory' => Factories::CUSTOMERS,
                'system' => Systems::BRIGHTPEARL,
            ],
            'options' => [
                'source' => [
                    'page_size' => ['value' => 250],
                    'inject_meta_data' => ['value' => true],
                ],
                'destination' => [],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::SHIPPED_SALES,
                'system' => Systems::BRIGHTPEARL,
            ],
            'destination' => [
                'factory' => Factories::FULFILMENTS,
                'system' => Systems::SHOPIFY,
            ],
            'options' => [
                'source' => [],
                'destination' => [
                    'notify_customer' => ['value' => true, 'user_configurable' => true]
                ],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::BRIGHTPEARL,
            ],
            'destination' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::PEOPLEVOX,
            ],
            'options' => [
                'source' => [],
                'destination' => [
                    'process_as_translated_batches' => ['value' => true],
                    'skip_customer_import' => ['value' => false],
                    'alert_on_sync_failure' => ['value' => true],
                ],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::CUSTOMERS,
                'system' => Systems::BIGCOMMERCE,
            ],
            'destination' => [
                'factory' => Factories::CUSTOMERS,
                'system' => Systems::EMARSYS,
            ],
            'options' => [
                'source' => [
                    'get_customer_addresses' => ['value' => true],
                    'get_customer_attributes' => ['value' => true],
                    'get_customer_custom_fields' => ['value' => true],
                    'date_format' => ['value' => 'c'],
                ],
                'destination' => [
                    'remote_path' => ['value' => 'bigcommerce/customers/'],
                ],
            ]
        ],
        [
            'source' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::BIGCOMMERCE,
            ],
            'destination' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::EMARSYS,
            ],
            'options' => [
                'source' => [
                    'date_format' => ['value' => 'c'],
                ],
                'destination' => [
                    'remote_path' => ['value' => 'bigcommerce/orders/'],
                ],
            ]
        ],
        [
            'source' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::BIGCOMMERCE,
            ],
            'destination' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::EMARSYS,
            ],
            'options' => [
                'source' => [
                    'date_format' => ['value' => 'c'],
                ],
                'destination' => [
                    'remote_path' => ['value' => 'bigcommerce/products/'],
                ],
            ]
        ],
        [
            'source' => [
                'factory' => Factories::CUSTOMERS,
                'system' => Systems::NETSUITE,
            ],
            'destination' => [
                'factory' => Factories::CUSTOMERS,
                'system' => Systems::EMARSYS,
            ],
            'options' => [
                'source' => [
                    'date_format' => ['value' => 'd/n/Y h:i a'],
                ],
                'destination' => [
                    'remote_path' => ['value' => 'netsuite/customers/'],
                    'send_to_ftp' => ['value' => true],
                ],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::NETSUITE,
            ],
            'destination' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::EMARSYS,
            ],
            'options' => [
                'source' => [
                    'date_format' => ['value' => 'd/n/Y h:i a'],
                ],
                'destination' => [
                    'remote_path' => ['value' => 'netsuite/orders/'],
                    'channel' => ['value' => 'NetSuite'],
                ],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::PRODUCTS_REFACTORED,
                'system' => Systems::NETSUITE,
            ],
            'destination' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::EMARSYS,
            ],
            'options' => [
                'source' => [
                    'date_format' => ['value' => 'd/n/Y h:i a'],
                ],
                'destination' => [
                    'remote_path' => ['value' => 'netsuite/products/'],
                    'channel' => ['value' => 'NetSuite'],
                ],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::FULFILMENTS,
                'system' => Systems::SEKO
            ],
            'destination' => [
                'factory' => Factories::FULFILMENTS,
                'system' => Systems::NETSUITE
            ],
            'options' => [
                'source' => [
                    'file_prefix' => ['value' => '', 'user_configurable' => true],
                    'delete_remote' => ['value' => true],
                    'remote_path' => ['value' => '/Push/Dispatch_Con/', 'user_configurable' => true],
                    'database_record_type' => ['value' => 'Fulfilment'],
                    'filter_cancelled_despatches' => ['value' => true, 'user_configurable' => true],
                    'get_source_record_id' => ['value' => true],
                    'source_record_type' => ['value' => 'Order'],
                    'common_ref_key' => ['value' => 'order_id'],
                ],
                'destination' => [
                    'origin' => ['value' => 'Seko', 'user_configurable' => true],
                    'source_id_field' => ['value' => 'source_record_id'],
                    'common_ref_field' => ['value' => 'order_id'],
                    'from_record' => ['value' => 'salesorder'],
                    'add_inventory_detail' => ['value' => false],
                    'filter_zero_quantity' => ['value' => true],
                ]
            ]
        ],
        [
            'source' => [
                'factory' => Factories::GOODS_RECEIPTS,
                'system' => Systems::SEKO
            ],
            'destination' => [
                'factory' => Factories::RETURNS,
                'system' => Systems::NETSUITE
            ],
            'options' => [
                'source' => [
                    'file_prefix' => ['value' => '', 'user_configurable' => true],
                    'delete_remote' => ['value' => true],
                    'remote_path' => ['value' => '/Push/GRN_Con/', 'user_configurable' => true],
                    'record_array_name' => ['value' => 'returns'],
                    'database_record_type' => ['value' => 'Return'],
                    'get_source_record_id' => ['value' => true],
                    'source_record_type' => ['value' => 'Order'],
                    'common_ref_key' => ['value' => 'hb_ref'],
                ],
                'destination' => [
                    'origin' => ['value' => 'Seko', 'user_configurable' => true],
                    'source_id_field' => ['value' => 'order_number'],
                    'common_ref_field' => ['value' => 'order_number'],
                    'from_record' => ['value' => 'salesorder'],
                    'returns_type' => ['value' => 'returns'],
                    'restock' => ['value' => true],
                    'create_itemreceipt' => ['value' => true],
                    'create_creditmemo' => ['value' => true],
                    'set_gross_ra_price' => ['value' => true],
                    'set_gross_cm_price' => ['value' => true],
                    'use_original_order_price' => ['value' => true],
                    'skip_customer_refund_after_credit_memo' => ['value' => true],
                ],
            ]
        ],
        [
            'source' => [
                'factory' => Factories::GOODS_RECEIPTS,
                'system' => Systems::SEKO
            ],
            'destination' => [
                'factory' => Factories::ITEM_RECEIPTS_MR,
                'system' => Systems::NETSUITE
            ],
            'options' => [
                'source' => [
                    'file_prefix' => ['value' => '', 'user_configurable' => true],
                    'delete_remote' => ['value' => true],
                    'remote_path' => ['value' => '/Push/Stock_Adjustment/', 'user_configurable' => true],
                    'database_record_type' => ['value' => 'GoodsReceipt'],
                    'get_source_record_id' => ['value' => true],
                    'source_record_type' => ['value' => 'PurchaseOrder'],
                    'common_ref_key' => ['value' => 'delivery_number'],
                ],
                'destination' => [
                    'origin' => ['value' => 'Seko', 'user_configurable' => true],
                    'db_type' => ['value' => 'GoodsReceipt'],
                    'source_id_field' => ['value' => 'delivery_number'],
                    'common_ref_field' => ['value' => 'delivery_number'],
                ],
            ]
        ],
        [
            'source' => [
                'factory' => Factories::STOCK_ADJUSTMENT,
                'system' => Systems::SEKO
            ],
            'destination' => [
                'factory' => Factories::INVENTORY_ADJUSTMENTS_MR,
                'system' => Systems::NETSUITE
            ],
            'options' => [
                'source' => [
                    'file_prefix' => ['value' => '', 'user_configurable' => true],
                    'delete_remote' => ['value' => true],
                    'remote_path' => ['value' => '/Push/Stock_Adjustment/', 'user_configurable' => true],
                    'database_record_type' => ['value' => 'StockAdjustment'],
                ],
                'destination' => [
                    'origin' => ['value' => 'Seko', 'user_configurable' => true],
                    'record_array_name' => ['value' => 'stockadjustments'],
                    'db_type' => ['value' => 'StockAdjustment'],
                    'common_ref_field' => ['value' => 'adjustment_id'],
                    'source_id_field' => ['value' => 'adjustment_id'],
                    'add_inventory_detail' => ['value' => false],
                ],
            ]
        ],
        [
            'source' => [
                'factory' => Factories::STOCK_STATUS_MOVEMENT,
                'system' => Systems::SEKO
            ],
            'destination' => [
                'factory' => Factories::INVENTORY_ADJUSTMENTS_MR,
                'system' => Systems::NETSUITE
            ],
            'options' => [
                'source' => [
                    'file_prefix' => ['value' => '', 'user_configurable' => true],
                    'delete_remote' => ['value' => true],
                    'remote_path' => ['value' => '/Push/Stock_Status_Movement/', 'user_configurable' => true],
                    'database_record_type' => ['value' => 'StockAdjustment'],
                    'separate_adjustments' => ['value' => true],
                    'join_character' => ['value' => '-'],
                ],
                'destination' => [
                    'origin' => ['value' => 'Seko', 'user_configurable' => true],
                    'record_array_name' => ['value' => 'StockStatusMovement'],
                    'db_type' => ['value' => 'StockAdjustment'],
                    'common_ref_field' => ['value' => 'file_name'],
                    'source_id_field' => ['value' => 'file_name'],
                    'add_inventory_detail' => ['value' => false],
                ],
            ]
        ],
        [
            'source' => [
                'factory' => Factories::FULFILMENTS,
                'system' => Systems::SEKO
            ],
            'destination' => [
                'factory' => Factories::FULFILMENTS,
                'system' => Systems::SHOPIFY
            ],
            'options' => [
                'source' => [
                    'file_prefix' => ['value' => '', 'user_configurable' => true],
                    'delete_remote' => ['value' => true],
                    'remote_path' => ['value' => '/Push/Dispatch_Con/', 'user_configurable' => true],
                    'database_record_type' => ['value' => 'Fulfilment'],
                    'filter_cancelled_despatches' => ['value' => true, 'user_configurable' => true],
                ],
                'destination' => [
                    'use_short_shopify_order_id' => ['value' => true],
                    'notify_customer' => ['value' => true, 'user_configurable' => true],
                ]
            ]
        ],
        [
            'source' => [
                'factory' => Factories::STOCK_OVERNIGHT_SUMMARY,
                'system' => Systems::SEKO
            ],
            'destination' => [
                'factory' => Factories::STOCK_LEVELS,
                'system' => Systems::SHOPIFY
            ],
            'options' => [
                'source' => [
                    'file_prefix' => ['value' => '', 'user_configurable' => true],
                    'delete_remote' => ['value' => true],
                    'remote_path' => ['value' => '/Push/Stock_Overnight_Summary/', 'user_configurable' => true],
                    'database_record_type' => ['value' => 'Stock'],
                ],
                'destination' => [
                    'fetch_missing_inventory_ids' => ['value' => true],
                    'static_system_chain' => ['value' => 'Shopify_Seko'],
                ],
            ]
        ],
        [
            'source' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::NETSUITE
            ],
            'destination' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::SEKO
            ],
            'options' => [
                'source' => [
                    'netsuite_common_ref_field' => ['value' => 'tranid'],
                    'date_format' => ['value' => 'd/m/Y', 'user_configurable' => true],
                ],
                'destination' => [
                    'common_ref_key' => ['value' => 'order_number'],
                    'process_as_batches' => ['value' => true],
                    'remote_path' => ['value' => '/Load/Sales_Orders/', 'user_configurable' => true],
                    'file_prefix' => ['value' => '', 'user_configurable' => true],
                ]
            ]
        ],
        [
            'source' => [
                'factory' => Factories::PRODUCTS_REFACTORED,
                'system' => Systems::NETSUITE
            ],
            'destination' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::SEKO
            ],
            'options' => [
                'source' => [
                    'page_size' => ['value' => '100'],
                ],
                'destination' => [
                    'remote_path' => ['value' => '/Load/Product_Masters/', 'user_configurable' => true],
                    'common_ref_key' => ['value' => 'sku'],
                    'process_as_batches' => ['value' => true],
                    'file_prefix' => ['value' => '', 'user_configurable' => true],
                ],
            ]
        ],
        [
            'source' => [
                'factory' => Factories::PURCHASE_ORDERS,
                'system' => Systems::NETSUITE
            ],
            'destination' => [
                'factory' => Factories::PURCHASE_ORDERS,
                'system' => Systems::SEKO
            ],
            'options' => [
                'source' => [
                    'date_format' => ['value' => 'd/m/Y', 'user_configurable' => true],
                    'common_ref_key' => ['value' => 'tranid'],
                ],
                'destination' => [
                    'common_ref_key' => ['value' => 'transaction_id'],
                    'file_name_key' => ['value' => 'transaction_id'],
                    'process_as_batches' => ['value' => true],
                    'remote_path' => ['value' => '/Load/Receipts/', 'user_configurable' => true],
                    'file_prefix' => ['value' => '', 'user_configurable' => true],
                ],
            ]
        ],
        [
            'source' => [
                'factory' => Factories::RETURNS,
                'system' => Systems::NETSUITE
            ],
            'destination' => [
                'factory' => Factories::RETURNS,
                'system' => Systems::SEKO
            ],
            'options' => [
                'source' => [
                    'page_size' => ['value' => '100'],
                    'date_format' => ['value' => 'd/m/Y', 'user_configurable' => true],
                ],
                'destination' => [
                    'remote_path' => ['value' => '/Load/Receipts/', 'user_configurable' => true],
                    'file_prefix' => ['value' => '', 'user_configurable' => true],
                    'common_ref_key' => ['value' => 'transaction_id'],
                    'file_name_key' => ['value' => 'transaction_id'],
                    'process_as_batches' => ['value' => true],
                ],
            ]
        ],
        [
            'source' => [
                'factory' => Factories::TRANSFER_ORDERS,
                'system' => Systems::NETSUITE
            ],
            'destination' => [
                'factory' => Factories::TRANSFER_ORDERS,
                'system' => Systems::SEKO
            ],
            'options' => [
                'source' => [
                    'date_format' => ['value' => 'd/m/Y', 'user_configurable' => true],
                    'netsuite_common_ref_field' => ['value' => 'tranid'],
                    'max_attempts' => ['value' => '6'],
                    'record_type' => ['value' => 'TransferOrder'],
                ],
                'destination' => [
                    'remote_path' => ['value' => '/Load/Receipts/', 'user_configurable' => true],
                    'file_prefix' => ['value' => '', 'user_configurable' => true],
                    'common_ref_key' => ['value' => 'order_name'],
                    'file_name_key' => ['value' => 'transaction_id'],
                    'process_as_batches' => ['value' => true],
                ],
            ]
        ],
        [
            'source' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::SHOPIFY
            ],
            'destination' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::SEKO
            ],
            'options' => [
                'source' => [
                    'page_size' => ['value' => '250'],
                ],
                'destination' => [
                    'file_prefix' => ['value' => '', 'user_configurable' => true],
                    'remote_path' => ['value' => '/Load/Web_Sales_Orders/', 'user_configurable' => true],
                    'common_ref_key' => ['value' => 'order_number'],
                    'process_as_batches' => ['value' => true],
                ],
            ]
        ],
        [
            'source' => [
                'factory' => Factories::STOCK,
                'system' => Systems::SEKO_API
            ],
            'destination' => [
                'factory' => Factories::STOCK_LEVELS,
                'system' => Systems::SHOPIFY
            ],
            'options' => [
                'source' => [],
                'destination' => [
                    'fetch_missing_inventory_ids' => ['value' => true],
                ],
            ]
        ],
        [
            'source' => [
                'factory' => Factories::EVENT_FULFILMENT,
                'system' => Systems::SEKO_API
            ],
            'destination' => [
                'factory' => Factories::FULFILMENTS,
                'system' => Systems::SHOPIFY
            ],
            'options' => [
                'source' => [
                    'service_system_chain' => ['value' => 'Shopify_SekoAPI'],
                    'idx_source_id' => ['value' => 'Responses.0.Response.Dispatch.DispatchId'],
                    'idx_endpoint_id' => ['value' => 'Responses.0.Response.Dispatch.SalesOrderReference'],
                ],
                'destination' => [
                    'use_short_shopify_order_id' => ['value' => true],
                    'force_non_partial_fulfilment' => ['value' => true],
                    'use_items_hash_matching_to_add_missing_fulfilment_ids' => ['value' => true],
                    'notify_customer' => ['value' => true],
                    'common_ref' => ['value' => 'order_id'],
                ]
            ]
        ],
        [
            'source' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::SHOPIFY
            ],
            'destination' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::SEKO_API
            ],
            'options' => [
                'source' => [
                    'page_size' => ['value' => '250'],
                    'inject_meta_data' => ['value' => true],
                ],
                'destination' => [],
            ]
        ],
        [
            'source' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::SHOPIFY
            ],
            'destination' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::SEKO_API
            ],
            'options' => [
                'source' => [
                    'static_system_chain' => ['value' => 'Shopify_SekoAPI'],
                    'skip_products_with_no_sku' => ['value' => true],
                    'get_inventory_item_data' => ['value' => true],
                    'filter_products_by_status' => ['value' => true]
                ],
                'destination' => [],
            ]
        ],
        [
            'source' => [
                'factory' => Factories::STOCK_LEVELS,
                'system' => Systems::NETSUITE,
            ],
            'destination' => [
                'factory' => Factories::STOCK_LEVELS,
                'system' => Systems::VISUALSOFT,
            ],
            'options' => [
                'source' => [
                    'date_format' => ['value' => 'd/m/Y'],
                ],
                'destination' => [
                    'date_format' => ['value' => 'Y-m-d'],
                    'origin' => ['value' => 'NetSuite'],
                ],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::FULFILMENTS,
                'system' => Systems::NETSUITE,
            ],
            'destination' => [
                'factory' => Factories::ORDER_STATUSES,
                'system' => Systems::VISUALSOFT,
            ],
            'options' => [
                'source' => [],
                'destination' => [
                    'data_array' => ['value' => 'fulfilments'],
                    'notify_customer' => ['value' => true],
                    'record_type' => ['value' => 'Fulfilment'],
                ],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::CREDIT_MEMOS,
                'system' => Systems::NETSUITE,
            ],
            'destination' => [
                'factory' => Factories::ORDER_STATUSES,
                'system' => Systems::VISUALSOFT,
            ],
            'options' => [
                'source' => [
                    'date_format' => ['value' => 'd/m/Y'],
                    'ids_record_type' => ['value' => 'CreditMemo'],
                    'db_record_type' => ['value' => 'CreditMemo'],
                    'netsuite_common_ref_field' => ['value' => 'tranid'],
                ],
                'destination' => [
                    'data_array' => ['value' => 'creditmemos'],
                    'notify_customer' => ['value' => true],
                    'record_type' => ['value' => 'CreditMemo'],
                ],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::VISUALSOFT,
            ],
            'destination' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::NETSUITE,
            ],
            'options' => [
                'source' => [
                    'date_format' => ['value' => 'Y-m-d\\TH:i:s'],
                    'origin' => ['value' => 'VisualSoft'],
                ],
                'destination' => [
                    'origin' => ['value' => 'VisualSoft'],
                ],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::CUSTOMERS,
                'system' => Systems::VISUALSOFT,
            ],
            'destination' => [
                'factory' => Factories::RECORD_UPDATES_MR,
                'system' => Systems::NETSUITE,
            ],
            'options' => [
                'source' => [
                    'date_format' => ['value' => 'Y-m-d\\TH:i:s'],
                    'origin' => ['value' => 'VisualSoft'],
                ],
                'destination' => [
                    'origin' => ['value' => 'VisualSoft'],
                ],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::VEEQO,
            ],
            'destination' => [
                'factory' => Factories::PRODUCTS,
                'system' => Systems::EPOSNOW,
            ],
            'options' => [
                'source' => [],
                'destination' => [],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::EPOSNOW,
            ],
            'destination' => [
                'factory' => Factories::ORDERS,
                'system' => Systems::VEEQO,
            ],
            'options' => [
                'source' => [],
                'destination' => [],
            ],
        ],
        [
            'source' => [
                'factory' => Factories::STOCK_LEVELS,
                'system' => Systems::EPOSNOW,
            ],
            'destination' => [
                'factory' => Factories::STOCK_LEVELS,
                'system' => Systems::VEEQO,
            ],
            'options' => [
                'source' => [],
                'destination' => [],
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
        foreach (self::SERVICE_TEMPLATE_OPTIONS as $serviceTemplateOption) {
            [$serviceTemplate, $sourceOptions, $destinationOptions] = $this->getData($serviceTemplateOption);
            if (!$serviceTemplate) {
                continue;
            }

            foreach ($sourceOptions as $key => $valueArray) {
                $sourceServiceOption = ServiceOption::firstWhere('key', $key);

                $this->updateOrCreate(
                    $serviceTemplate,
                    $sourceServiceOption,
                    $valueArray['value'],
                    'source',
                    $valueArray['user_configurable'] ?? false
                );
            }

            foreach ($destinationOptions as $key => $valueArray) {
                $destinationServiceOption = ServiceOption::firstWhere('key', $key);
                $this->updateOrCreate(
                    $serviceTemplate,
                    $destinationServiceOption,
                    $valueArray['value'],
                    'destination',
                    $valueArray['user_configurable'] ?? false
                );
            }
        }
    }

    protected function updateOrCreate(
        ServiceTemplate $serviceTemplate,
        ServiceOption $serviceOption,
        $value,
        string $target,
        bool $userConfigurable = false
    ) {
        ServiceTemplateOption::updateOrCreate(
            [
                'service_option_id' => $serviceOption->id,
                'service_template_id' => $serviceTemplate->id,
                'target' => $target,
            ],
            [
                'service_option_id' => $serviceOption->id,
                'service_template_id' => $serviceTemplate->id,
                'target' => $target,
                'value' => $value,
                'user_configurable' => $userConfigurable
            ]
        );
    }

    protected function getData(array $serviceTemplateOption): array
    {
        $sourceFactorySystem = FactorySystem::firstWhere([
            'direction' => 'pull',
            'factory_id' => Factory::firstWhere('name', Arr::get($serviceTemplateOption, 'source.factory'))->id,
            'system_id' => System::firstWhere('name', Arr::get($serviceTemplateOption, 'source.system'))->id
        ]);

        $destinationFactorySystem = FactorySystem::firstWhere([
            'direction' => 'push',
            'factory_id' => Factory::firstWhere('name', Arr::get($serviceTemplateOption, 'destination.factory'))->id,
            'system_id' => System::firstWhere('name', Arr::get($serviceTemplateOption, 'destination.system'))->id
        ]);

        $serviceTemplate = ServiceTemplate::firstWhere([
            'source_factory_system_id' => $sourceFactorySystem->id,
            'destination_factory_system_id' => $destinationFactorySystem->id
        ]);

        return [
            $serviceTemplate,
            Arr::get($serviceTemplateOption, 'options.source'),
            Arr::get($serviceTemplateOption, 'options.destination'),
        ];
    }
}
