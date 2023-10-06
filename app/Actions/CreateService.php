<?php

namespace App\Actions;

use App\Http\Helpers\IntegrationHelper;
use App\Http\Helpers\ServiceHelper;
use App\Models\Fabric\Factory;
use App\Models\Fabric\Integration;
use App\Models\Fabric\ServiceTemplate;
use Exception;

class CreateService
{
    /**
     * @var ServiceHelper
     */
    private ServiceHelper $serviceHelper;

    /**
     * @var IntegrationHelper
     */
    private IntegrationHelper $integrationHelper;

    public function __construct(ServiceHelper $serviceHelper, IntegrationHelper $integrationHelper)
    {
        $this->serviceHelper = $serviceHelper;
        $this->integrationHelper = $integrationHelper;
    }

    /**
     * Run additional system specific functions when creating services.
     * Example would be creating NS import confirmation services.
     *
     * @param ServiceTemplate $serviceTemplate
     * @param Integration $integration
     * @param array $attributes
     *
     * @throws Exception
     */
    public function runSystemSpecificFunctions(ServiceTemplate $serviceTemplate, Integration $integration, array $attributes): void
    {
        $destinationSystem = $serviceTemplate->destinationSystem;
        $destinationFactory = $serviceTemplate->destinationFactory;

        if ($destinationSystem->name === 'Netsuite' && isset($attributes['to_options']['origin'])) {
            $this->createImportConfirmationService($destinationFactory, $integration, $attributes);
        }

        if (
            $destinationSystem->name === 'Mirakl'
            && $destinationFactory->name === 'OrderTracking'
            && !$this->doesUserHaveMiraklCarrierService($integration, $attributes['from_environment'])
        ) {
            $this->createMiraklCarrierService($integration, $attributes);
        }
    }

    /**
     * Create an import confirmation service for Netsuite
     *
     * @param Factory $destinationFactory
     * @param Integration $integration
     * @param array $attributes
     *
     * @return void
     *
     * @throws Exception
     */
    public function createImportConfirmationService(Factory $destinationFactory, Integration $integration, array $attributes): void
    {
        $recordType = self::getNetsuiteRecordType($destinationFactory);
        if (!$recordType) {
            throw new Exception('No matching record type found.');
        }
        $importConfirmationAttributes = [
            'description' => 'Netsuite Import Confirmation Service',
            'schedule' => '*/15 * * * *',
            'from_environment' => $attributes['from_environment'],
            'to_environment' => $attributes['to_environment'],
            'from_options' => json_encode([
                'record_type' => $recordType,
                'db_record_type' => self::getNetsuiteDBType($recordType),
                'filters' => [
                    ['custrecord_pwks_export_parent_source', $attributes['to_options']['origin'], 'is']
                ]
            ], JSON_PRETTY_PRINT),
            'to_options' => null,
            'status' => true,
            'from_factory' => 'Netsuite\Pull\ImportConfirmations',
            'to_factory' => 'nowhere',
            'billable' => false
        ];

        $this->integrationHelper->createService($integration->server, $integration->username, ['fields' => $importConfirmationAttributes]);
    }

    /**
     * Get the Netsuite Record Type for the Import Confirmation Service
     *
     * @param Factory $destinationFactory
     *
     * @return string|null
     */
    private function getNetsuiteRecordType(Factory $destinationFactory): ?string
    {
        switch ($destinationFactory->name) {
            case 'Orders':
                return 'salesorder';
            case 'EventFulfilment':
            case 'Fulfilment':
            case 'Fulfilments':
            case 'FulfilmentSweeper':
            case 'ReverseFulfilmentSweeper':
            case 'TransferOrderFulfilments':
                return 'itemfulfillment';
            case 'CashSales':
                return 'cashsale';
            case 'CashRefunds':
            case 'Refunds':
            case 'RefundsRefactored':
                return 'cashrefund';
            case 'Customers':
            case 'NECustomers':
                return 'customer';
            case 'Estimates':
                return 'estimate';
            case 'Returns':
            case 'ReturnsSweeper':
            case 'ReturnSweeper':
            case 'ReturnUpdates':
                return 'return';
            case 'ItemReceipts':
            case 'ItemReceiptsMR':
                return 'itemreceipt';
            case 'InventoryAdjustments':
            case 'InventoryAdjustmentsMR':
                return 'inventoryadjustment';
            default:
                return null;
        }
    }

    /**
     * Get the DB type for the Netsuite Import Confirmation Service
     *
     * @param string $recordType
     *
     * @return string|null
     */
    private function getNetsuiteDBType(string $recordType): ?string
    {
        switch ($recordType) {
            case 'cashsale':
            case 'salesorder':
                return 'Order';
            case 'itemfulfillment':
                return 'Fulfilment';
            case 'cashrefund':
                return 'Refund';
            case 'customer':
                return 'Customer';
            case 'estimate':
                return 'Estimate';
            case 'return':
                return 'Return';
            case 'itemreceipt':
                return 'ItemReceipt';
            case 'inventoryadjustment':
                return 'InventoryAdjustment';
            default:
                return null;
        }
    }

    /**
     * Create a service to pull Mirakl Carriers into DB
     *
     * @param Integration $integration
     * @param array $attributes
     *
     * @throws Exception
     */
    public function createMiraklCarrierService(Integration $integration, array $attributes): void
    {
        $carrierAttributes = [
            'description' => 'Mirakl Carrier Service to populate idx',
            'schedule' => '*/15 * * * *',
            'from_environment' => $attributes['from_environment'],
            'to_environment' => $attributes['to_environment'],
            'from_options' => null,
            'to_options' => null,
            'status' => true,
            'from_factory' => 'Mirakl\Pull\Carriers',
            'to_factory' => 'nowhere',
            'billable' => false
        ];

        $this->integrationHelper->createService($integration->server, $integration->username, ['fields' => $carrierAttributes]);
    }

    /**
     * Check if username has Mirakl Carrier service already
     *
     * @param Integration $integration
     * @param string $environment
     *
     * @return bool
     */
    public function doesUserHaveMiraklCarrierService(Integration $integration, string $environment): bool
    {
        return $this->integrationHelper->getServices($integration->server, $integration->username)
            ->where('from_factory', 'Mirakl\Pull\Carriers')
            ->where('from_environment', $environment)
            ->isNotEmpty();
    }
}
