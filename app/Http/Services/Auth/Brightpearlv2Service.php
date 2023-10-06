<?php

namespace App\Http\Services\Auth;

use App\Http\Abstracts\SystemAuthAbstract;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class Brightpearlv2Service extends SystemAuthAbstract
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
            'base_url' => [
                'required',
                'string',
            ],
            'account_code' => [
                'required',
                'string',
            ],
            'app_reference' => [
                'required',
                'string',
            ],
            'token' => [
                'required',
                'string',
            ],
        ];
    }

    public static function getUpdateRules(): array
    {
        return [
            'base_url' => [
                'filled',
                'string',
            ],
            'account_code' => [
                'filled',
                'string',
            ],
            'app_reference' => [
                'filled',
                'string',
            ],
            'token' => [
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
                    '%s/%s/%s',
                    $this->attributes['base_url'],
                    $this->attributes['account_code'],
                    config('external-app.authentication_endpoints.brightpearl')
                ),
                ['headers' => [
                    'brightpearl-account-token' => $this->attributes['token'],
                    'brightpearl-app-ref' => $this->attributes['app_reference']
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
        return isset($authResult['response']);
    }

    public static function getObfuscatedFields(): array
    {
        return ['token'];
    }
}
