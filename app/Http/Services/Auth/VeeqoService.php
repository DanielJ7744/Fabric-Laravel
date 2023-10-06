<?php

namespace App\Http\Services\Auth;

use App\Http\Abstracts\SystemAuthAbstract;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class VeeqoService extends SystemAuthAbstract
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
            $request = $this->client->get('https://api.veeqo.com/current_company', ['headers' => [
                'x-api-key' => $this->attributes['token']
            ]]);
            $authResult = json_decode($request->getBody()->getContents(), true);
        } catch (Exception $exception) {
            Log::warning($exception->getMessage());

            return null;
        }

        return $authResult;
    }

    public function verify(?array $authResult): bool
    {
        return isset($authResult['id']) && !empty($authResult['id']);
    }

    public static function getObfuscatedFields(): array
    {
        return ['token'];
    }

    public static function getTapestryFormat(array $credentials): array
    {
        $credentials['url'] = 'https://api.veeqo.com/';

        return parent::getTapestryFormat($credentials);
    }

    public static function getFabricFormat(array $credentials): array
    {
        unset($credentials['url']);

        return parent::getFabricFormat($credentials);
    }
}
