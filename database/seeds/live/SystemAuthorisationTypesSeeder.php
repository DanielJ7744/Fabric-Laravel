<?php

use App\Enums\Systems;
use App\Models\Fabric\AuthorisationType;
use App\Models\Fabric\System;
use App\Models\Fabric\SystemAuthorisationType;
use Illuminate\Database\Seeder;

class SystemAuthorisationTypesSeeder extends Seeder
{
    private array $systemsAndAuthTypes = [
        Systems::SEKO => [
            'basic_auth' => '{"attributes":[{"type":"text","label":"Host","value":"ftp_host"},{"type":"number","label":"Port","value":"ftp_port"},{"type":"text","label":"Username","value":"ftp_username"},{"type":"text","label":"Password","value":"ftp_password"},{"type":"select","label":"Mode","value":"ftp_passive","options":{"true":"Passive", "false":"Active"}, "defaultValue": "true"}]}',
        ],
        Systems::SEKO_API => [
            'basic_auth' => '{"attributes":[{"type":"text","label":"API Key","value":"token"},{"type":"text","label":"URL","value":"url"}]}',
        ],
        Systems::SHOPIFY => [
            'basic_auth' => '{"attributes":[{"type":"text","label":"Store","value":"store"},{"type":"text","label":"API Key","value":"api_key"},{"type":"password","label":"Password","value":"password"},{"type":"text","label":"Shared Secret","value":"shared_secret"},{"type":"hidden","label":null,"show":false,"defaultValue":true,"value":"private_app"}]}',
            'oauth2' => '{"attributes":[{"type":"hidden","label":null,"show":false,"defaultValue":true,"value":"public_app"}]}'
        ],
        Systems::PEOPLEVOX => [
            'basic_auth' => '{"attributes":[{"type":"text","label":"Client ID","value":"client_id"},{"type":"text","label":"Username","value":"username"},{"type":"password","label":"Password","value":"password"},{"type":"text","label":"URL","value":"url"}]}',
        ],
        Systems::NETSUITE => [
            'oauth1' => '{"attributes":[{"type":"text","label":"Oauth Token ID","value":"oauth_token_id"},{"type":"text","label":"Oauth Token Secret","value":"oauth_token_secret"},{"type":"text","label":"Account ID","value":"account_id"},{"type":"text","label":"Deploy ID","value":"deploy_id"},{"type":"text","label":"Script ID","value":"script_id"},{"type":"text","label":"Consumer Key","value":"consumer_key","required":false,"tooltip":"This field is only required if a new consumer key is generated within NetSuite"},{"type":"text","label":"Consumer Secret","value":"consumer_secret","required":false,"tooltip":"This field is only required if a new consumer secret is generated within NetSuite"}]}'
        ],
        Systems::NETSUITE_REST => [
            'oauth2' => '{"attributes":[{"type":"text","label":"Account ID","value":"account_id","required":true}]}'
        ],
        Systems::DYNAMICS_NAV => [
            'ntlm' => '{"attributes":[{"type":"text","label":"Server Instance","value":"server_instance"},{"type":"text","label":"Username","value":"username"},{"type":"password","label":"Password","value":"password"},{"type":"text","label":"Company","value":"company"},{"type":"text","label":"URL","value":"url"},{"type":"text","label":"Domain","value":"domain"},{"type":"hidden","label":"type","value":"type","defaultValue":"ntlm"}]}',
        ],
        Systems::REBOUND => [
            'basic_auth' => '{"attributes":[{"type":"text","label":"Login","value":"login"},{"type":"text","label":"API Key","value":"api_key"},{"type":"text","label":"URL","value":"url"}]}',
        ],
        Systems::VEND => [
            'basic_auth' => '{"attributes":[{"type":"text","label":"Domain Prefix","value":"domain_prefix"},{"type":"text","label":"Access Token","value":"access_token"}]}',
        ],
        Systems::LINNWORKS => [
            'basic_auth' => '{"attributes":[{"type":"text","label":"Token","value":"token"}]}',
        ],
        Systems::BIGCOMMERCE => [
            'oauth1' => '{"attributes":[{"type":"text","label":"Store Hash","value":"store_hash"},{"type":"text","label":"Client ID","value":"client_id"},{"type":"password","label":"Client Secret","value":"client_secret"},{"type":"text","label":"Access Token","value":"access_token"}]}',
        ],
        Systems::MAGENTO_2 => [
            'basic_auth' => '{"attributes":[{"type":"text","label":"Token","value":"token"},{"type":"text","label":"URL","value":"url","tooltip":"Please enter a value such as https://magento.com/"}]}'
        ],
        Systems::COMMERCETOOLS => [
            'basic_auth' => '{"attributes":[{"type":"text","label":"Project Key","value":"project_key"},{"type":"text","label":"Client ID","value":"client_id"},{"type":"text","label":"Secret","value":"secret"},{"type":"text","label":"Scope","value":"scope"},{"type":"text","label":"API URL","value":"api_url"},{"type":"text","label":"Auth URL","value":"auth_url"}]}',
        ],
        Systems::MIRAKL => [
            'basic_auth' => '{"attributes":[{"type":"text","label":"Store Name","value":"store_name"},{"type":"text","label":"API Key","value":"api_key"}]}',
        ],
        Systems::WOOCOMMERCE => [
            'basic_auth' => '{"attributes":[{"type":"text","label":"URL","value":"url"},{"type":"password","label":"Consumer Key","value":"consumer_key"},{"type":"password","label":"Consumer Secret","value":"consumer_secret"}]}'
        ],
        Systems::BRIGHTPEARL => [
            'basic_auth' => '{"attributes":[{"type":"text","label":"Base URL","value":"base_url"},{"type":"text","label":"Account Code","value":"account_code"},{"type":"text","label":"App Reference","value":"app_reference"},{"type":"text","label":"Token","value":"token"}]}',
        ],
        Systems::SFTP => [
            'basic_auth' => '{"attributes":[{"type":"text","label":"URL/Host","value":"host"},{"type":"text","label":"Port","value":"port"},{"type":"text","label":"Username","value":"username"},{"type":"password","label":"Password","value":"password"}]}',
            'ssh' => '{"attributes":[{"type":"text","label":"URL/Host","value":"host"},{"type":"text","label":"Port","value":"port"},{"type":"text","label":"Public Key","value":"public_key"}]}'
        ],
        Systems::CSV => [
            'basic_auth' => '{"attributes":[{"type":"text","label":"URL/Host","value":"host"},{"type":"text","label":"Port","value":"port"},{"type":"text","label":"Username","value":"username"},{"type":"password","label":"Password","value":"password"}]}',
        ],
        Systems::JSON => [
            'basic_auth' => '{"attributes":[{"type":"text","label":"URL/Host","value":"host"},{"type":"text","label":"Port","value":"port"},{"type":"text","label":"Username","value":"username"},{"type":"password","label":"Password","value":"password"}]}',
        ],
        Systems::SYTELINE => [
            'basic_auth' => '{"attributes":[{"type":"text","label":"URL/Host","value":"host"},{"type":"text","label":"Port","value":"port"},{"type":"text","label":"Username","value":"username"},{"type":"password","label":"Password","value":"password"}]}',
        ],
        Systems::TXT => [
            'basic_auth' => '{"attributes":[{"type":"text","label":"URL/Host","value":"host"},{"type":"text","label":"Port","value":"port"},{"type":"text","label":"Username","value":"username"},{"type":"password","label":"Password","value":"password"}]}',
        ],
        Systems::XML => [
            'basic_auth' => '{"attributes":[{"type":"text","label":"URL/Host","value":"host"},{"type":"text","label":"Port","value":"port"},{"type":"text","label":"Username","value":"username"},{"type":"password","label":"Password","value":"password"}]}',
        ],
        Systems::KHAOS => [
            'none' => '{"attributes":[{"type":"text","label":"URL","value":"url","tooltip":"This must be a valid URL, it can be either HTTP or HTTPS."}]}'
        ],
        Systems::EMARSYS => [
            'basic_auth' => '{"attributes":[{"type":"text","label":"Username","value":"username"},{"type":"password","label":"Secret","value":"api_secret"}]}',
            'ftp' => '{"attributes":[{"type":"text","label":"FTP Host","value":"ftp_host"},{"type":"number","label":"FTP Port","value":"ftp_port"},{"type":"text","label":"FTP Username","value":"ftp_username"},{"type":"password","label":"FTP Password","value":"ftp_password"},{"type":"select","label":"FTP Mode","value":"ftp_passive","options":{"1":"Passive","0":"Active"}}]}'
        ],
        Systems::VISUALSOFT => [
            'basic_auth' => '{"attributes":[{"type":"text","label":"URL","value":"url"},{"type":"text","label":"Client ID","value":"client_id"},{"type":"text","label":"Username","value":"username"},{"type":"password","label":"Password","value":"password"},{"type":"select","label":"Version","value":"version","options":{"3":"3","4":"4","5":"5","6":"6","7":"7"}}]}',
        ],
        Systems::VEEQO => [
            'basic_auth' => '{"attributes":[{"type":"text","label":"Token","value":"token"}]}',
        ],
        Systems::EPOSNOW => [
            'basic_auth' => '{"attributes":[{"type":"text","label":"Token","value":"token"}]}',
        ],
        Systems::LIGHTSPEED => [
            'oauth2' => null,
        ],
        Systems::BI => [
            'none' => null,
        ],
        Systems::S3 => [
            'basic_auth' => '{"attributes":[{"type":"text","label":"Key","value":"key","required":false,"tooltip":"This field is only required if using your own AWS environment"},{"type":"text","label":"Secret","value":"secret","required":false,"tooltip":"This field is only required if using your own AWS environment"}]}',
        ],
        Systems::OMETRIA => [
            'basic_auth' => '{"attributes":[{"type":"text","label":"API Key","value":"api_key"}]}',
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $inserts = [];
        foreach ($this->getSystemsAndAuthTypes() as $systemFactoryName => $authTypes) {
            if (!is_array($authTypes)) {
                continue;
            }

            $matchingSystem = System::firstWhere('name', $systemFactoryName);
            if (is_null($matchingSystem)) {
                continue;
            }

            foreach ($authTypes as $authType => $credentialSchema) {
                $matchingAuthType = AuthorisationType::where('name', $authType)->first();
                $inserts[] = [
                    'system_id' => $matchingSystem->id,
                    'authorisation_type_id' => $matchingAuthType->id,
                    'credentials_schema' => $credentialSchema,
                ];
            }
        }
        foreach ($inserts as $insert) {
            SystemAuthorisationType::updateOrCreate(
                [
                    'system_id' => $insert['system_id'],
                    'authorisation_type_id' => $insert['authorisation_type_id']
                ],
                $insert
            );
        }
    }

    public function getSystemsAndAuthTypes(): array
    {
        return $this->systemsAndAuthTypes;
    }
}
