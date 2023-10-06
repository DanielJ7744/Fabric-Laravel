<?php

namespace App\Http\Services\Auth;

use App\Http\Abstracts\SystemAuthAbstract;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class SekoAPIService extends SystemAuthAbstract
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
            'url' => [
                'required',
                'string',
            ],
        ];
    }

    public function authenticate(): ?array
    {
        try {
            $request = $this->client->get(
                sprintf('%s/stock/v1/all?api_key=%s', $this->attributes['url'], $this->attributes['token']),
                ['headers' => [
                    'Accept' => 'application/json'
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
        return isset($authResult['CallStatus']['Success']) && $authResult['CallStatus']['Success'] === true;
    }

    public static function getObfuscatedFields(): array
    {
        return ['api_key', 'token'];
    }
}
