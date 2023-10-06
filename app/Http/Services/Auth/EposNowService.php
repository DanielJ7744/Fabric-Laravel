<?php

namespace App\Http\Services\Auth;

use App\Http\Abstracts\SystemAuthAbstract;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class EposNowService extends SystemAuthAbstract
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
            'token' => [
                'required',
                'string',
            ],
        ];
    }

    public static function getUpdateRules(): array
    {
        return [
            'token' => [
                'filled',
                'string',
            ],
        ];
    }

    public function authenticate(): ?array
    {
        try {
            $response = $this->client->get('https://api.eposnowhq.com/api/v4/AppSettings', ['headers' => [
                'Authorization' => sprintf('Basic %s', $this->attributes['token'])
            ]]);
            $statusCode = $response->getStatusCode();
        } catch (Exception $exception) {
            Log::warning($exception->getMessage());

            return null;
        }

        return ['status' => $statusCode];
    }

    public function verify(?array $authResult): bool
    {
        return isset($authResult['status']) && $authResult['status'] === 200;
    }

    public static function getObfuscatedFields(): array
    {
        return ['token'];
    }

    public static function getTapestryFormat(array $credentials): array
    {
        $credentials['url'] = 'https://api.eposnowhq.com/api/v4/';

        return parent::getTapestryFormat($credentials);
    }

    public static function getFabricFormat(array $credentials): array
    {
        unset($credentials['url']);

        return parent::getFabricFormat($credentials);
    }
}
