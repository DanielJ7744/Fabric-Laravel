<?php

namespace App\Http\Services\Auth;

use App\Http\Abstracts\SystemAuthAbstract;
use App\Rules\Https;
use App\Rules\Rebound\Host;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class ReboundService extends SystemAuthAbstract
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
            'login' => [
                'required',
                'string',
            ],
            'api_key' => [
                'required',
                'string',
            ],
            'url' => [
                'required',
                'string',
                new Https(),
                new Host(),
            ],
        ];
    }

    public static function getUpdateRules(): array
    {
        return [
            'login' => [
                'filled',
                'string',
            ],
            'api_key' => [
                'filled',
                'string',
            ],
            'url' => [
                'filled',
                'string',
                new Https(),
                new Host(),
            ],
        ];
    }

    public function authenticate(): ?array
    {
        try {
            $request = $this->client->post(
                sprintf(
                    '%s://%s/%s',
                    parse_url($this->attributes['url'])['scheme'],
                    parse_url($this->attributes['url'])['host'],
                    config('external-app.authentication_endpoints.rebound')
                ),
                ['form_params' => [
                    'login' => $this->attributes['login'],
                    'api_key' => $this->attributes['api_key'],
                    'request' => json_encode([
                        'filter' => [
                            'date_from' => Carbon::now()->subDay()->format('m/d/Y')
                        ]
                    ])
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
        return isset($authResult['success']) || $this->isValidError($authResult);
    }

    protected function isValidError(?array $authResult): bool
    {
        return isset($authResult['error']['code']) && $authResult['error']['code'] === 'empty';
    }

    public static function getTapestryFormat(array $credentials): array
    {
        $parsedUrl = parse_url($credentials['url']);
        $credentials['url'] = sprintf('%s://%s', $parsedUrl['scheme'], $parsedUrl['host']);
        if (self::isTestHost($parsedUrl['host'])) {
            $credentials['test'] = true;
        }

        return $credentials;
    }

    public static function getFabricFormat(array $credentials): array
    {
        unset($credentials['test']);

        return $credentials;
    }

    protected static function isTestHost(string $host): bool
    {
        return mb_strtolower($host) === 'test.intelligentreturns.net';
    }

    public static function getObfuscatedFields(): array
    {
        return ['api_key', 'password'];
    }
}
