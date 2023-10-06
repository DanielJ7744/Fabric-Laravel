<?php

namespace App\Http\Services\Auth;

use App\Http\Abstracts\SystemAuthAbstract;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class PrimaService extends SystemAuthAbstract
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
            'prima_soap_url' => [
                'required',
                'string',
                'regex:/(?:[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?\.)+[a-z0-9][a-z0-9-]{0,61}[a-z0-9]:[0-9]+(\/[a-z0-9]+)*/'
            ],
        ];
    }

    public static function getUpdateRules(): array
    {
        return [
            'prima_soap_url' => [
                'filled',
                'string',
                'regex:/(?:[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?\.)+[a-z0-9][a-z0-9-]{0,61}[a-z0-9]:[0-9]+(\/[a-z0-9]+)*/'
            ],
        ];
    }

    public function authenticate(): ?array
    {
        try {
            $request = $this->client->get(
                sprintf(
                    'https://%s',
                    $this->attributes['prima_soap_url']
                )
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
        return [];
    }
}
