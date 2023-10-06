<?php

namespace App\Http\Services\Auth;

use App\Http\Abstracts\SystemAuthAbstract;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class OmetriaService extends SystemAuthAbstract
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
        ];
    }

    public static function getUpdateRules(): array
    {
        return [
            'api_key' => [
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
                    'https://api.ometria.com/%s',
                    config('external-app.authentication_endpoints.ometria')
                ),
                ['headers' => [
                    'X-Ometria-Auth' => $this->attributes['api_key']
                ]]
            );

            $authResult = ['status_code' => $request->getStatusCode()];
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
        return ['api_key'];
    }
}
