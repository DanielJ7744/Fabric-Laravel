<?php

namespace App\Http\Services\Auth;

use App\Http\Abstracts\SystemAuthAbstract;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class SapB1Service extends SystemAuthAbstract
{
    protected Client $client;

    public function __construct(array $attributes, Client $client)
    {
        parent::__construct($attributes);
        $this->client = $client;
    }

    public static function getRules(): array
    {
        return [
            'api_url' => [
                'required',
                'string',
            ],
            'api_user' => [
                'required',
                'string',
            ],
            'api_password' => [
                'required',
                'string'
            ],
            'sap_db' => [
                'required',
                'string'
            ]
        ];
    }

    public static function getUpdateRules(): array
    {
        return [
            'api_url' => [
                'required',
                'string',
            ],
            'api_user' => [
                'required',
                'string',
            ],
            'api_password' => [
                'required',
                'string'
            ],
            'sap_db' => [
                'required',
                'string'
            ]
        ];
    }

    public function authenticate(): ?array
    {
        $authResult = null;
        try {
            $loginUrl = sprintf('%s/Login', $this->attributes['api_url']);
            $loginData = [
                'json' => [
                    'CompanyDB' => $this->attributes['sap_db'],
                    'UserName' => $this->attributes['api_user'],
                    'Password' => $this->attributes['api_password']
                ]
            ];

            $client = new Client(['verify' => false]);
            $api = $client->request('POST', $loginUrl, $loginData);

            if (($api->getStatusCode() === 201) || ($api->getStatusCode() === 200)) {
                $authResult = json_decode($api->getBody()->getContents(), true);
            }

        } catch (Exception $exception) {
            Log::warning($exception->getMessage());

            return null;
        }

        return $authResult;
    }

    public function verify(?array $authResult): bool
    {
        return !is_null($authResult) && isset($authResult['SessionId']);
    }

    public static function getObfuscatedFields(): array
    {
        return ['api_password'];
    }
}
