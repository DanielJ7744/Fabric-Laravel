<?php

namespace App\Http\Services\Auth;

use App\Http\Abstracts\SystemAuthAbstract;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class ZigZagService extends SystemAuthAbstract
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
            'username' => [
                'required',
                'string',
            ],
            'password' => [
                'required',
                'string',
            ],
            'url' => [
                'required',
                'string',
            ]
        ];
    }

    public static function getUpdateRules(): array
    {
        return [
            'username' => [
                'filled',
                'string',
            ],
            'password' => [
                'filled',
                'string',
            ],
            'url' => [
                'filled',
                'string',
            ],
        ];
    }

    public function authenticate(): ?array
    {
        $url = $this->attributes['url'] . '/Return/api/v1/Account/Token';
        try {
            $options = [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'body' => json_encode(
                    [
                        'username' => $this->attributes['username'],
                        'password' => $this->attributes['password']
                    ]
                )
            ];

            $request = $this->client->request("POST", $url, $options);

            return ['status_code' => $request->getStatusCode(), 'body' => json_decode($request->getBody()->getContents(), true)];
        } catch (GuzzleException $e) {
            Log::warning($e->getMessage());

            return null;
        }
    }

    public function verify(?array $authResult): bool
    {
        return !is_null($authResult) && $authResult['status_code'] === 200;
    }

    public static function getTapestryFormat(array $credentials): array
    {
        return parent::getTapestryFormat($credentials);
    }

    public static function getFabricFormat(array $credentials): array
    {
        return parent::getFabricFormat($credentials);
    }

    public static function getObfuscatedFields(): array
    {
        return ['password'];
    }
}
