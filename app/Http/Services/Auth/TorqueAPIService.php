<?php

namespace App\Http\Services\Auth;

use App\Http\Abstracts\SystemAuthAbstract;
use App\Rules\Https;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class TorqueAPIService extends SystemAuthAbstract
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
            'auth' => [
                'required',
                'string',
            ],
            'url' => [
                'required',
                'string',
                new Https()
            ],
            'version' => [
                'required',
                'string'
            ],
            'client_id' => [
                'filled',
                'string'
            ],
            'client_group' => [
                'filled',
                'string'
            ],
            'site_id' => [
                'filled',
                'string'
            ],
            'ship_dock' => [
                'filled',
                'string'
            ],
            'config_id' => [
                'filled',
                'string'
            ],
            'owner_id' => [
                'filled',
                'string'
            ],
            'pallet_config' => [
                'filled',
                'string'
            ]
        ];
    }

    public static function getUpdateRules(): array
    {
        return [
            'auth' => [
                'filled',
                'string',
            ],
            'url' => [
                'filled',
                'string'
            ],
            'version' => [
                'filled',
                'string'
            ],
            'client_id' => [
                'filled',
                'string'
            ],
            'client_group' => [
                'filled',
                'string'
            ],
            'site_id' => [
                'filled',
                'string'
            ],
            'ship_dock' => [
                'filled',
                'string'
            ],
            'config_id' => [
                'filled',
                'string'
            ],
            'owner_id' => [
                'filled',
                'string'
            ],
            'pallet_config' => [
                'filled',
                'string'
            ]
        ];
    }

    public function authenticate(): ?array
    {
        try {
            $request = $this->client->get(
                sprintf(
                    '%s/api/%s/%s',
                    $this->attributes['url'],
                    $this->attributes['version'],
                    config('external-app.authentication_endpoints.torque')
                ),
                ['headers' =>
                    [
                        'Authorization' => sprintf('Basic %s', $this->attributes['auth']),
                        'Content-Type' => 'application/json'
                    ]
                ]
            );

            $authResult = [
                    'status_code' => $request->getStatusCode(),
            ];
        } catch (Exception $exception) {
            Log::warning($exception->getMessage());

            return null;
        }

        return $authResult;
    }

    public function verify(?array $authResult): bool
    {
        return isset($authResult['status_code']) && $authResult['status_code'] === 200;
    }

    public static function getObfuscatedFields(): array
    {
        return ['auth'];
    }
}
