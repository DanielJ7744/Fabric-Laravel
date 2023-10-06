<?php

namespace App\Http\Services\Auth;

use App\Http\Abstracts\SystemAuthAbstract;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class VendService extends SystemAuthAbstract
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
            'domain_prefix' => [
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
            'domain_prefix' => [
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
                    'https://%s.vendhq.com/%s',
                    $this->attributes['domain_prefix'],
                    config('external-app.authentication_endpoints.vend')
                ),
                ['headers' => [
                    'Authorization' => sprintf('Bearer %s', $this->attributes['access_token']),
                    'Content-Type' => 'application/json'
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
        return isset($authResult['data'], $authResult['version']);
    }

    public static function getObfuscatedFields(): array
    {
        return ['access_token'];
    }
}
