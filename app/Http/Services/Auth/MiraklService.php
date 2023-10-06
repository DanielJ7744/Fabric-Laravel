<?php

namespace App\Http\Services\Auth;

use App\Http\Abstracts\SystemAuthAbstract;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class MiraklService extends SystemAuthAbstract
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
            'api_key' => [
                'required',
                'string',
            ],
            'store_name' => [
                'required',
                'string'
            ]
        ];
    }

    public static function getUpdateRules(): array
    {
        return [
            'api_key' => [
                'filled',
                'string',
            ],
            'store_name' => [
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
                    'https://%s.mirakl.net/api/%s',
                    $this->attributes['store_name'],
                    config('external-app.authentication_endpoints.mirakl')
                ),
                ['headers' => [
                    'Authorization' => $this->attributes['api_key']
                ]]
            );

            $authResult = json_decode($request->getBody()->getContents(), true);
        } catch (Exception $exception) {
            Log::warning($exception->getMessage());

            return null;
        }

        return $authResult;
    }

    public function verify(?array $authResult): bool
    {
        return isset($authResult['shop_id']);
    }

    public static function getObfuscatedFields(): array
    {
        return ['api_key'];
    }
}
