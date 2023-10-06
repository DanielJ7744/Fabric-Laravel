<?php

use App\Enums\Systems;
use App\Models\Fabric\Entity;
use App\Models\Fabric\System;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;

class SystemEntitySeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->getSystemEntities() as $system => $entities) {
            $system = System::firstWhere('name', $system);
            if (!$system) {
                continue;
            }

            $pullEntities = Entity::hydrate(collect($entities['pull'])->map(function ($entity) {
                [$databaseName, $factoryName] = $entity;
                return Entity::where(['database_name' => $databaseName, 'factory_name' => $factoryName])->first(); // TODO: Entity -> factory_name
            })->filter()->toArray());

            $pushEntities = Entity::hydrate(collect($entities['push'])->map(function ($entity) {
                [$databaseName, $factoryName] = $entity;
                return Entity::where(['database_name' => $databaseName, 'factory_name' => $factoryName])->first(); // TODO: Entity -> factory_name
            })->filter()->toArray());

            $this->attachWhereNotExists($system, $pullEntities, 'pull');
            $this->attachWhereNotExists($system, $pushEntities, 'push');
        }
    }

    public function attachWhereNotExists(System $system, Collection $entities, string $direction): void
    {
        $existingEntities = $system->entities()
            ->where('direction', $direction)
            ->whereIn('entities.id', $entities->pluck('id'))
            ->get();
        $newEntities = $entities->diff($existingEntities);
        if ($newEntities->isNotEmpty()) {
            $system->entities()->attach($newEntities, ['direction' => $direction]);
        }
    }

    public function getSystemEntities()
    {
        return [
            // database_name, factory_name
            'Shopify' => [
                'pull' => [
                    ['Address', 'Addresses'],
                    ['Return', 'CashRefunds'],
                    ['Customer', 'Customers'],
                    ['Fulfilment', 'Fulfilments'],
                    ['GiftCard', 'GiftCards'],
                    ['Location', 'Locations'],
                    ['Order', 'Orders'],
                    ['Order', 'OrdersRebound'],
                    ['Product', 'Products'],
                    ['Return', 'Refunds'],
                    ['InventoryItem', 'Stocklevels'],
                ],
                'push' => [
                    ['Customer', 'Customers'],
                    ['Fulfilment', 'Fulfilments'],
                    ['InventoryAdjustment', 'InventoryAdjustments'],
                    ['Inventoryitem', 'InventoryItems'],
                    ['Order', 'OrderUpdates'],
                    ['Product', 'Prices'],
                    ['Product', 'Products'],
                    ['Refund', 'Refunds'],
                    ['Return', 'RefundsRefactored'],
                    ['Stocklevel', 'StockAdjustment'],
                    ['Stocklevel', 'Stocklevels'],
                    ['Stocklevel', 'StocklevelsMultiSku'],
                    ['Stocklevel', 'StockMultiLocation'],
                ]
            ],
            'Peoplevox' => [
                'pull' => [
                    ['Fulfilment', 'DespatchPackages'],
                    ['Fulfilment', 'EventFulfilment'],
                    ['Fulfilment', 'EventTrackingNo'],
                    ['Fulfilment', 'Fulfilment'],
                    ['Fulfilment', 'FulfilmentSweeper'],
                    ['ItemReceipt', 'ItemReceipts'],
                    ['Stocklevels', 'KitStock'],
                    ['Order', 'Orders'],
                    ['Order', 'OrdersRefactored'],
                    ['Product', 'Products'],
                    ['Return', 'Returns'],
                    ['Return', 'ReturnsSweeper'],
                    ['Fulfilment', 'ReverseFulfilmentSweeper'],
                    ['Stocklevels', 'Stock'],
                    ['Stocklevels', 'StockCsv'],
                    ['Stocklevels', 'TimestampStock'],
                ],
                'push' => [
                    ['Product', 'Kits'],
                    ['Order', 'Orders'],
                    ['Product', 'Products'],
                    ['Supplier', 'ProductSuppliers'],
                    ['PurchaseOrder', 'PurchaseOrders'],
                    ['Supplier', 'Suppliers'],
                ]
            ],
            Systems::NETSUITE => [
                'pull' => [
                    ['AssemblyBuild', 'AssemblyBuilds'],
                    ['CashRefunds', 'CashRefunds'],
                    ['CashSale', 'CashSales'],
                    ['CreditMemo', 'CreditMemos'],
                    ['Customer', 'Customers'],
                    ['Fulfilment', 'Fulfilments'],
                    ['InboundShipment', 'InboundShipments'],
                    ['Invoice', 'Invoices'],
                    ['ItemReceipt', 'ItemReceipts'],
                    ['Kit', 'Kits'],
                    ['Order', 'Orders'],
                    ['Product', 'Prices'],
                    ['Product', 'ProductsRefactored'],
                    ['PurchaseOrder', 'PurchaseOrders'],
                    ['Quote', 'Quotes'],
                    ['Refunds', 'Refunds'],
                    ['Returns', 'Returns'],
                    ['Stocklevel', 'Stocklevels'],
                    ['Stocklevel', 'StockRefactored'],
                    ['TransferOrder', 'TransferOrders'],
                    ['WorkOrder', 'WorkOrders'],
                ],
                'push' => [
                    ['CashRefund', 'CashRefunds'],
                    ['Order', 'CashSales'],
                    ['CashSale', 'CashSales'],
                    ['Customer', 'Customers'],
                    ['Estimate', 'Estimates'],
                    ['Fulfilment', 'Fulfilments'],
                    ['InboundShipmentReceipt', 'InboundShipmentReceiptMR'],
                    ['InventoryAdjustment', 'InventoryAdjustments'],
                    ['InventoryAdjustment', 'InventoryAdjustmentsMR'],
                    ['ItemReceipt', 'ItemReceipts'],
                    ['ItemReceipt', 'ItemReceiptsMR'],
                    ['Order', 'Orders'],
                    ['Product', 'Products'],
                    ['PurchaseOrder', 'PurchaseOrders'],
                    ['PurchaseOrder', 'PurchaseOrdersMR'],
                    ['Refund', 'Refunds'],
                    ['Return', 'Returns'],
                    ['Fulfilment', 'TransferOrderFulfilments'],
                ]
            ],
            'Rebound' => [
                'pull' => [
                    ['Return', 'Returns'],
                    ['Return', 'ReturnUpdates'],
                    ['ReboundUpdate', 'Cancellations'],
                ],
                'push' => [
                    ['Order', 'Orders'],
                ]
            ],
            'Dynamics Nav' => [
                'pull' => [
                    ['Customer', 'Customers'],
                    ['Fulfilment', 'Fulfilments'],
                    ['Customer', 'NEContacts'],
                    ['Customer', 'NECustomers'],
                    ['Product', 'NEProducts'],
                    ['Order', 'NESalesDocument'],
                    ['Stocklevel', 'NEStocklevels'],
                    ['Product', 'Products'],
                    ['PurchaseOrder', 'PurchaseOrders'],
                    ['Return', 'Returns'],
                    ['Stocklevel', 'Stocklevels'],
                ],
                'push' => [
                    ['InventoryAdjustment', 'InventoryAdjustments'],
                    ['ItemReceipt', 'ItemReceipts'],
                    ['Customer', 'NECustomers'],
                    ['Order', 'NEOrders'],
                    ['Order', 'Orders'],
                    ['Return', 'Returns'],
                ]
            ],
            'Lightspeed' => [
                'pull' => [
                    ['Inventory', 'InventoryLogs'],
                    ['Refund', 'Refunds'],
                    ['CashSale', 'Sales'],
                ],
                'push' => [
                    ['Product', 'Items'],
                ]
            ],
            'Vend' => [
                'pull' => [
                    'Inventory',
                    'Products',
                    'Refunds',
                    'Sales',
                ],
                'push' => [
                    'Fulfilment',
                    'Products',
                    'Stock',
                ]
            ],
            'Linnworks' => [
                'pull' => [
                    'Products',
                    'Stock',
                ],
                'push' => [
                    'Orders',
                    'Refunds',
                    'Stock',
                ]
            ],
        ];
    }
}
