<?php

namespace App\Http\Services\Auth;

use App\Http\Abstracts\SystemAuthAbstract;
use Exception;
use App\Rules\Https;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class MagentotwoService extends SystemAuthAbstract
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
            'url' => [
                'required',
                'string',
                new Https()
            ]
        ];
    }

    public static function getUpdateRules(): array
    {
        return [
            'token' => [
                'filled',
                'string',
            ],
            'url' => [
                'filled',
                'string',
                new Https()
            ]
        ];
    }

    public function authenticate(): ?array
    {
        try {
            $url = $this->attributes['url'];
            if (!Str::endsWith($url, '/')) {
                $url = sprintf('%s/', $url);
            }
            $request = $this->client->get(
                sprintf(
                    '%s%s',
                    $url,
                    config('external-app.authentication_endpoints.magentotwo')
                ),
                ['headers' => [
                    'Authorization' => sprintf('Bearer %s', $this->attributes['token']),
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
        return !is_null($authResult) && $authResult['status_code'] === 200;
    }

    public static function getObfuscatedFields(): array
    {
        return ['token'];
    }
}
