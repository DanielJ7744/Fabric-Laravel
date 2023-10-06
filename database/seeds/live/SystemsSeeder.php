<?php

use App\Enums\Systems;
use App\Models\Fabric\System;
use App\Models\Fabric\SystemType;
use Illuminate\Database\Seeder;

class SystemsSeeder extends Seeder
{
    protected const WMS = 'Warehouse Management';
    protected const ERP = 'Enterprise Resource Planning';
    protected const POS = 'Point of Sale';
    protected const CRM = 'Customer Relationship Management';
    protected const E_COMM = 'E-commerce';
    protected const ACCOUNTING = 'Accounting';

    // Taken from Prod Fabric DB 2021_12_01
    private const SYSTEMS = [
        Systems::SEKO => [
            'website' => 'https://www.sekologistics.com/uk/',
            'description' => self::WMS,
            'factory_name' => 'Seko',
            'system_type_id' => 'WMS/3PL'
        ],
        Systems::SEKO_API => [
            'website' => 'https://www.sekologistics.com/uk/',
            'description' => self::WMS,
            'factory_name' => 'SekoAPI',
            'system_type_id' => 'WMS/3PL'
        ],
        Systems::CLOVER => [
            'website' => 'https://www.clover.com/',
            'description' => self::POS,
            'factory_name' => 'Clover',
            'system_type_id' => 'POS'
        ],
        Systems::LIGHTSPEED => [
            'website' => 'https://www.lightspeedhq.co.uk/',
            'description' => self::POS,
            'factory_name' => 'Lightspeed',
            'system_type_id' => 'POS'
        ],
        Systems::SHOPIFY => [
            'website' => 'https://www.shopify.com/',
            'description' => self::E_COMM,
            'factory_name' => 'Shopify',
            'system_type_id' => 'eCommerce',
            'date_format' => 'c',
            'documentation_link' => 'https://app.gitbook.com/o/QK9606D86GQKTsWinNMs/s/LYNcUBVQwSkOMG6KjZfz/systems/shopify-shopify+',
            'has_webhooks' => true,
            'documentation_link_description' => 'Please ensure you have completed all prerequisite steps. Further information is available on our help page.',
            'popular' => true
        ],
        Systems::YOTPO => [
            'website' => 'https://www.yotpo.com/',
            'description' => self::CRM,
            'factory_name' => 'Yotpo',
            'system_type_id' => 'CRM'
        ],
        Systems::XERO => [
            'website' => 'https://www.xero.com/uk/',
            'description' => self::ACCOUNTING,
            'factory_name' => 'Xero',
            'system_type_id' => 'Accounting'
        ],
        Systems::PEOPLEVOX => [
            'website' => 'https://www.peoplevox.com/en-us/',
            'description' => self::WMS,
            'factory_name' => 'Peoplevox',
            'system_type_id' => 'WMS/3PL',
            'documentation_link' => 'https://app.gitbook.com/o/QK9606D86GQKTsWinNMs/s/LYNcUBVQwSkOMG6KjZfz/systems/decartes-peoplevox',
            'has_webhooks' => true,
            'webhook_schema' => [
                [
                    'label' => 'Filter',
                    'key' => 'filter',
                    'type' => 'input',
                    'required' => false
                ],
                [
                    'label' => 'Parameters',
                    'key' => 'postParams',
                    'type' => 'input',
                    'required' => false
                ]
            ],
            'documentation_link_description' => 'Please ensure you have completed all prerequisite steps. Further information is available on our help page.',
            'popular' => true
        ],
        Systems::NETSUITE => [
            'website' => 'https://www.netsuite.co.uk/',
            'description' => self::ERP,
            'factory_name' => 'Netsuite',
            'system_type_id' => 'ERP',
            'documentation_link' => 'https://app.gitbook.com/o/QK9606D86GQKTsWinNMs/s/LYNcUBVQwSkOMG6KjZfz/systems/netsuite',
            'documentation_link_description' => 'Please ensure you have completed all prerequisite steps. Further information is available on our help page.',
            'popular' => true
        ],
        Systems::NETSUITE_REST => [
            'website' => 'https://www.netsuite.co.uk/',
            'description' => self::ERP,
            'factory_name' => 'NetSuiteRest',
            'system_type_id' => 'ERP',
            'documentation_link' => 'https://app.gitbook.com/o/QK9606D86GQKTsWinNMs/s/LYNcUBVQwSkOMG6KjZfz/systems/netsuite',
            'documentation_link_description' => 'Please ensure you have completed all prerequisite steps. Further information is available on our help page.',
            'popular' => true
        ],
        Systems::MAGENTO_2 => [
            'website' => 'https://magento.com/',
            'description' => self::E_COMM,
            'factory_name' => 'Magentotwo',
            'system_type_id' => 'eCommerce',
            'documentation_link' => '',
            'documentation_link_description' => 'Please ensure you have completed all prerequisite steps. Further information is available on our help page.'
        ],
        Systems::KHAOS => [
            'website' => 'https://www.khaoscontrol.com/',
            'description' => self::WMS,
            'factory_name' => 'Khaos',
            'system_type_id' => 'WMS/3PL',
            'documentation_link' => 'https://app.gitbook.com/o/QK9606D86GQKTsWinNMs/s/LYNcUBVQwSkOMG6KjZfz/systems/khaos-control',
            'documentation_link_description' => 'Please ensure you have completed all prerequisite steps. Further information is available on our help page.'
        ],
        Systems::DYNAMICS_NAV => [
            'website' => 'https://dynamics.microsoft.com/en-us/',
            'description' => self::ERP,
            'factory_name' => 'Dynamics Nav',
            'system_type_id' => 'ERP',
            'documentation_link' => 'https://app.gitbook.com/o/QK9606D86GQKTsWinNMs/s/LYNcUBVQwSkOMG6KjZfz/systems/navision',
            'documentation_link_description' => 'Please ensure you have completed all prerequisite steps. Further information is available on our help page.',
            'popular' => true
        ],
        Systems::VISUALSOFT => [
            'website' => 'https://www.visualsoft.co.uk/',
            'description' => self::E_COMM,
            'factory_name' => 'Visualsoft',
            'system_type_id' => 'eCommerce'
        ],
        Systems::COMMERCETOOLS => [
            'website' => 'https://commercetools.com/',
            'description' => self::E_COMM,
            'factory_name' => 'CommerceTools',
            'system_type_id' => 'eCommerce',
            'documentation_link' => 'https://app.gitbook.com/o/QK9606D86GQKTsWinNMs/s/LYNcUBVQwSkOMG6KjZfz/systems/commercetools',
            'documentation_link_description' => 'Please ensure you have completed all prerequisite steps. Further information is available on our help page.'
        ],
        Systems::BLECKMANN => [
            'website' => 'https://www.bleckmann.com/en/',
            'description' => self::WMS,
            'factory_name' => 'Bleckmann',
            'system_type_id' => 'WMS/3PL'
        ],
        Systems::RADIAL => [
            'website' => 'https://www.radial.com/',
            'description' => self::WMS,
            'factory_name' => 'Radial',
            'system_type_id' => 'WMS/3PL'
        ],
        Systems::REBOUND => [
            'website' => 'https://www.reboundreturns.com/',
            'description' => self::WMS,
            'factory_name' => 'Rebound',
            'system_type_id' => 'WMS/3PL',
            'documentation_link' => 'https://app.gitbook.com/o/QK9606D86GQKTsWinNMs/s/LYNcUBVQwSkOMG6KjZfz/systems/rebound',
            'documentation_link_description' => 'Please ensure you have completed all prerequisite steps. Further information is available on our help page.'
        ],
        Systems::OMETRIA => [
            'website' => 'https://ometria.com/',
            'description' => self::CRM,
            'factory_name' => 'Ometria',
            'system_type_id' => 'CRM'
        ],
        Systems::OMETRIAFILEPULL => [
            'website' => 'https://ometria.com/',
            'description' => self::CRM,
            'factory_name' => 'OmetriaFilePull',
            'system_type_id' => 'CRM'
        ],
        Systems::DEMANDWARE => [
            'website' => 'https://demandware.com/',
            'description' => self::CRM,
            'factory_name' => 'Demandware',
            'system_type_id' => 'CRM'
        ],
        Systems::LINNWORKS => [
            'website' => 'https://www.linnworks.com/',
            'description' => self::ERP,
            'factory_name' => 'Linnworks',
            'system_type_id' => 'ERP',
            'documentation_link' => 'https://app.gitbook.com/o/QK9606D86GQKTsWinNMs/s/LYNcUBVQwSkOMG6KjZfz/systems/linnworks',
            'documentation_link_description' => 'Please ensure you have completed all prerequisite steps. Further information is available on our help page.'
        ],
        Systems::VEND => [
            'website' => 'https://www.vendhq.com/',
            'description' => self::POS,
            'factory_name' => 'Vend',
            'system_type_id' => 'POS',
            'date_format' => 'Y-m-d\\TH:i:s\\Z',
            'time_zone' => 'UTC',
            'documentation_link' => 'https://app.gitbook.com/o/QK9606D86GQKTsWinNMs/s/LYNcUBVQwSkOMG6KjZfz/systems/vend',
            'documentation_link_description' => 'Please ensure you have completed all prerequisite steps. Further information is available on our help page.'
        ],
        Systems::BIGCOMMERCE => [
            'website' => 'https://www.bigcommerce.com/',
            'description' => self::E_COMM,
            'factory_name' => 'Bigcommerce',
            'system_type_id' => 'eCommerce',
            'documentation_link' => 'https://app.gitbook.com/o/QK9606D86GQKTsWinNMs/s/LYNcUBVQwSkOMG6KjZfz/systems/bigcommerce',
            'documentation_link_description' => 'Please ensure you have completed all prerequisite steps. Further information is available on our help page.'
        ],
        Systems::MIRAKL => [
            'website' => 'https://www.mirakl.com/',
            'description' => self::E_COMM,
            'factory_name' => 'Mirakl',
            'system_type_id' => 'eCommerce',
        ],
        Systems::WOOCOMMERCE => [
            'website' => 'https://www.woocommerce.com/',
            'description' => self::E_COMM,
            'factory_name' => 'Woocommerce',
            'system_type_id' => 'eCommerce'
        ],
        Systems::BRIGHTPEARL => [
            'website' => 'https://www.brightpearl.com/',
            'description' => self::ERP,
            'factory_name' => 'Brightpearlv2',
            'system_type_id' => 'ERP',
            'date_format' => 'c',
            'time_zone' => 'UTC',
            'documentation_link' => 'https://app.gitbook.com/o/QK9606D86GQKTsWinNMs/s/LYNcUBVQwSkOMG6KjZfz/systems/brightpearl',
            'documentation_link_description' => 'Please ensure you have completed all prerequisite steps. Further information is available on our help page.'
        ],
        Systems::SFTP => [
            'website' => 'https://www.wearepatchworks.com/',
            'description' => 'SFTP Connector',
            'factory_name' => 'SFTP',
            'system_type_id' => 'Other',
            'date_format' => 'c',
            'time_zone' => 'UTC',
        ],
        Systems::CSV => [
            'website' => 'https://www.wearepatchworks.com/',
            'description' => 'CSV SFTP Connector',
            'factory_name' => 'Csv',
            'system_type_id' => 'Other',
            'date_format' => 'c',
            'time_zone' => 'UTC',
        ],
        Systems::JSON => [
            'website' => 'https://www.wearepatchworks.com/',
            'description' => 'Json SFTP Connector',
            'factory_name' => 'Json',
            'system_type_id' => 'Other',
            'date_format' => 'c',
            'time_zone' => 'UTC',
        ],
        Systems::SYTELINE => [
            'website' => 'https://www.wearepatchworks.com/',
            'description' => 'SyteLine SFTP Connector',
            'factory_name' => 'SyteLine',
            'system_type_id' => 'Other',
            'date_format' => 'c',
            'time_zone' => 'UTC',
        ],
        Systems::TXT => [
            'website' => 'https://www.wearepatchworks.com/',
            'description' => 'Txt SFTP Connector',
            'factory_name' => 'Txt',
            'system_type_id' => 'Other',
            'date_format' => 'c',
            'time_zone' => 'UTC',
        ],
        Systems::XML => [
            'website' => 'https://www.wearepatchworks.com/',
            'description' => 'XML SFTP Connector',
            'factory_name' => 'Xml',
            'system_type_id' => 'Other',
            'date_format' => 'c',
            'time_zone' => 'UTC',
        ],
        Systems::OPSUITE => [
            'website' => 'https://www.opsuite.com/',
            'description' => self::POS,
            'factory_name' => 'Opsuite',
            'system_type_id' => 'POS',
            'date_format' => 'c',
            'time_zone' => 'UTC',
        ],
        Systems::EMARSYS => [
            'website' => 'https://emarsys.com/',
            'description' => self::CRM,
            'factory_name' => 'Emarsys',
            'system_type_id' => 'CRM',
            'documentation_link' => 'https://app.gitbook.com/o/QK9606D86GQKTsWinNMs/s/LYNcUBVQwSkOMG6KjZfz/systems/emarsys',
            'documentation_link_description' => 'Please ensure you have completed all prerequisite steps. Further information is available on our help page.'
        ],
        Systems::INBOUND_API => [
            'website' => 'https://www.wearepatchworks.com/',
            'description' => "Inbound Api",
            'factory_name' => "InboundAPI",
            'system_type_id' => 'Patchworks'
        ],
        Systems::VEEQO => [
            'website' => 'https://www.veeqo.com/',
            'description' => self::WMS,
            'factory_name' => 'Veeqo',
            'system_type_id' => 'WMS/3PL',
        ],
        Systems::EPOSNOW => [
            'website' => 'https://www.eposnowhq.com/',
            'description' => self::POS,
            'factory_name' => 'EposNow',
            'system_type_id' => 'POS',
        ],
        Systems::S3 => [
            'website' => 'https://www.wearepatchworks.com/',
            'description' => 'S3 Connector',
            'factory_name' => 'S3',
            'system_type_id' => 'Other',
            'date_format' => 'c',
            'time_zone' => 'UTC',
        ],
        Systems::BI => [
            'website' => 'https://www.wearepatchworks.com/',
            'description' => 'BI Connector',
            'factory_name' => 'BI',
            'system_type_id' => 'Other',
            'date_format' => 'c',
            'time_zone' => 'UTC',
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (self::SYSTEMS as $name => $system) {
            $system['name'] = $name;
            $system['system_type_id'] = SystemType::firstWhere('name', $system['system_type_id'])->id;
            $existingSystem = System::where('name', $name)->first();
            if (!is_null($existingSystem)) {
                $existingSystem->update($system);
                $existingSystem->save();

                continue;
            }
            System::create($system);
        }
    }
}
