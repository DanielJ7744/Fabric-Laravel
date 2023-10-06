<?php

use App\Enums\Systems;
use App\Models\Fabric\System;
use App\Models\Fabric\EventType;
use Illuminate\Database\Seeder;

class EventTypesSeeder extends Seeder
{
    private const SYSTEMS = [
        Systems::PEOPLEVOX => [
            'AvailabilityChanges' => [
                'name' => 'Availability Change',
                'schema_values' => null,
            ],
            'SalesOrderStatusChanges' => [
                'name' => 'Sales Order Status Changes',
                'schema_values' => null,
            ],
            'GoodsReceived' => [
                'name' => 'Goods Received',
                'schema_values' => null,
            ],
            'TrackingNumberReceived' => [
                'name' => 'Tracking Number Received',
                'schema_values' => null,
            ],
            'IncrementalChanges' => [
                'name' => 'Incremental Changes',
                'schema_values' => null,
            ],
            'Returns' => [
                'name' => 'Returns',
                'schema_values' => null,
            ],
            'DespatchPackageTrackingNumberReceived' => [
                'name' => 'Package Tracking Number Received',
                'schema_values' => null,
            ],
            'DespatchPackageDespatched' => [
                'name' => 'Package Despatched',
                'schema_values' => null,
            ],
        ],
        Systems::SHOPIFY => [
            'customers/create' => [
                'name' => 'Customers Create',
                'schema_values' => null,
            ],
            'customers/delete' => [
                'name' => 'Customers Delete',
                'schema_values' => null,
            ],
            'customers/disable' => [
                'name' => 'Customers Disable',
                'schema_values' => null,
            ],
            'customers/enable' => [
                'name' => 'Customers Enable',
                'schema_values' => null,
            ],
            'customers/update' => [
                'name' => 'Customers Update',
                'schema_values' => null,
            ],
            'customers_marketing_consent/update' => [
                'name' => 'Customers Marketing Consent Update',
                'schema_values' => null,
            ],
            'fulfillments/create' => [
                'name' => 'Fulfillments Create',
                'schema_values' => null,
            ],
            'fulfillments/update' => [
                'name' => 'Fulfillments Update',
                'schema_values' => null,
            ],
            'inventory_items/create' => [
                'name' => 'Inventory Items Create',
                'schema_values' => null,
            ],
            'inventory_items/delete' => [
                'name' => 'Inventory Items Delete',
                'schema_values' => null,
            ],
            'inventory_items/update' => [
                'name' => 'Inventory Items Update',
                'schema_values' => null,
            ],
            'inventory_levels/connect' => [
                'name' => 'Inventory Levels Connect',
                'schema_values' => null,
            ],
            'inventory_levels/disconnect' => [
                'name' => 'Inventory Levels Disconnect',
                'schema_values' => null,
            ],
            'inventory_levels/update' => [
                'name' => 'Inventory Levels Update',
                'schema_values' => null,
            ],
            'orders/cancelled' => [
                'name' => 'Orders Cancelled',
                'schema_values' => null,
            ],
            'orders/create' => [
                'name' => 'Orders Create',
                'schema_values' => null,
            ],
            'orders/delete' => [
                'name' => 'Orders Delete',
                'schema_values' => null,
            ],
            'orders/edited' => [
                'name' => 'Orders Edited',
                'schema_values' => null,
            ],
            'orders/fulfilled' => [
                'name' => 'Orders Fulfilled',
                'schema_values' => null,
            ],
            'orders/paid' => [
                'name' => 'Orders Paid',
                'schema_values' => null,
            ],
            'orders/partially_fulfilled' => [
                'name' => 'Orders Partially Fulfilled',
                'schema_values' => null,
            ],
            'orders/updated' => [
                'name' => 'Orders Updated',
                'schema_values' => null,
            ],
            'products/create' => [
                'name' => 'Products Create',
                'schema_values' => null,
            ],
            'products/delete' => [
                'name' => 'Products Delete',
                'schema_values' => null,
            ],
            'products/update' => [
                'name' => 'Products Update',
                'schema_values' => null,
            ],
            'refunds/create' => [
                'name' => 'Refunds Create',
                'schema_values' => null,
            ],
        ]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (self::SYSTEMS as $name => $eventTypes) {
            $system = System::firstWhere('name', $name);
            if (!$system) {
                continue;
            }

            foreach ($eventTypes as $key => $data) {
                EventType::updateOrCreate(
                    [
                        'key' => $key,
                        'system_id' => $system->id
                    ],
                    [
                        'key' => $key,
                        'name' => $data['name'],
                        'schema_values' => json_encode($data['schema_values']),
                        'system_id' => $system->id,
                    ]
                );
            }
        }
    }
}
