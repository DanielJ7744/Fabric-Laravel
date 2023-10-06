<?php

namespace App\Http\Services\Auth;

use App\Http\Abstracts\SystemAuthAbstract;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class WooCommerceService extends SystemAuthAbstract
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
            'url' => [
                'required',
                'string',
            ],
            'consumer_key' => [
                'required',
                'string',
            ],
            'consumer_secret' => [
                'required',
                'string',
            ]
        ];
    }

    public static function getUpdateRules(): array
    {
        return [
            'url' => [
                'filled',
                'string',
            ],
            'consumer_key' => [
                'filled',
                'string',
            ],
            'consumer_secret' => [
                'filled',
                'string',
            ]
        ];
    }

    public function authenticate(): ?array
    {
        try {
            $request = $this->client->get(
                sprintf(
                    '%s/wp-json/wc/v3/orders',
                    $this->attributes['url']
                ),
                ['auth' =>
                    [
                        $this->attributes['consumer_key'],
                        $this->attributes['consumer_secret']
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
        return ['consumer_key', 'consumer_secret'];
    }
}
