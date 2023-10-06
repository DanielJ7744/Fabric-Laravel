<?php

namespace App\Http\Services\Auth;

use App\Http\Abstracts\SystemAuthAbstract;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class BigcommerceService extends SystemAuthAbstract
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
            'store_hash' => [
                'required',
                'string',
            ],
            'client_id' => [
                'required',
                'string',
            ],
            'client_secret' => [
                'required',
                'string',
            ],
            'access_token' => [
                'required',
                'string',
            ],
        ];
    }

    public static function getUpdateRules(): array
    {
        return [
            'store_hash' => [
                'filled',
                'string',
            ],
            'client_id' => [
                'filled',
                'string',
            ],
            'client_secret' => [
                'filled',
                'string',
            ],
            'access_token' => [
                'filled',
                'string',
            ],
        ];
    }

    public function authenticate(): ?array
    {
        try {
            $request = $this->client->get(
                sprintf(
                    'https://api.bigcommerce.com/stores/%s/%s',
                    $this->attributes['store_hash'],
                    config('external-app.authentication_endpoints.bigcommerce')
                ),
                ['headers' => [
                    'X-Auth-Client' => $this->attributes['client_id'],
                    'X-Auth-Token' => $this->attributes['access_token'],
                    'Accept' => 'application/json'
                ]]
            );

            $authResult = ['status_code' => $request->getStatusCode(), 'body' => json_decode($request->getBody()->getContents(), true)];
        } catch (Exception $exception) {
            Log::warning($exception->getMessage());

            return null;
        }

        return $authResult;
    }

    public function verify(?array $authResult): bool
    {
        return !is_null($authResult) && in_array($authResult['status_code'], [200, 204]);
    }

    public static function getObfuscatedFields(): array
    {
        return ['client_secret', 'access_token', 'client_id'];
    }
}
