<?php

namespace App\Http\Services\Auth;

use App\Http\Abstracts\SystemAuthAbstract;
use App\Rules\Shopify\StoreDomain;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Psr\Http\Message\ResponseInterface;

class ShopifyService extends SystemAuthAbstract
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
            'store' => [
                'required',
                'string',
                new StoreDomain(),
            ],
            'api_key' => [
                'required_if:private_app,true',
                'exclude_if:public_app,true',
                'string',
            ],
            'password' => [
                'required_if:private_app,true',
                'exclude_if:public_app,true',
                'string',
            ],
            'shared_secret' => [
                'required_if:private_app,true',
                'exclude_if:public_app,true',
                'string',
            ],
            'access_token' => [
                'required_if:public_app,true',
                'exclude_if:private_app,true',
                'string',
            ],
            'private_app' => [
                'required_unless:public_app,true',
                'exclude_if:public_app,true',
                'boolean',
            ],
            'public_app' => [
                'required_unless:private_app,true',
                'exclude_if:private_app,true',
                'boolean',
            ],
        ];
    }

    public static function getUpdateRules(): array
    {
        return [
            'store' => [
                'filled',
                'string',
                new StoreDomain(),
            ],
            'api_key' => [
                'filled',
                'string',
            ],
            'password' => [
                'filled',
                'string',
            ],
            'shared_secret' => [
                'filled',
                'string',
            ],
            'access_token' => [
                'filled',
                'string',
            ],
            'private_app' => [
                'filled',
                'boolean',
            ],
            'public_app' => [
                'filled',
                'boolean',
            ],
        ];
    }

    /**
     * @throws GuzzleException
     */
    public function authenticate(): ?array
    {
        try {
            $url = $this->getBaseUrl();
            $response = Arr::has($this->attributes, 'private_app') && $this->attributes['private_app'] === true
                ? $this->basicAuth($url)
                : $this->oAuth($url);
            $authResult = json_decode($response->getBody()->getContents(), true);
        } catch (Exception $exception) {
            Log::warning($exception->getMessage());

            return null;
        }

        return $authResult;
    }

    public function getBaseUrl(): string
    {
        return Arr::has($this->attributes, 'private_app') && $this->attributes['private_app'] === true
            ? sprintf('https://%s:%s@%s', $this->attributes['api_key'], $this->attributes['password'], $this->attributes['store'])
            : sprintf('https://%s', $this->attributes['store']);
    }

    public function getAuthHeaders(): array
    {
        return Arr::has($this->attributes, 'public_app') && $this->attributes['public_app'] === true
            ? ['X-Shopify-Access-Token' => $this->attributes['access_token']]
            : [];
    }

    /**
     * @throws GuzzleException
     */
    protected function basicAuth(string $url): ResponseInterface
    {
        return $this->client->get(
            sprintf('%s/%s', $url, config('external-app.authentication_endpoints.shopify'))
        );
    }

    protected function oAuth(string $url): ResponseInterface
    {
        return $this->client->get(
            sprintf('%s/%s', $url, config('external-app.authentication_endpoints.shopify')),
            ['headers' => ['X-Shopify-Access-Token' => $this->attributes['access_token']]]
        );
    }

    public function verify(?array $authResult): bool
    {
        return isset($authResult['access_scopes']) && is_array($authResult['access_scopes']);
    }

    public static function getObfuscatedFields(): array
    {
        return ['api_key', 'password', 'shared_secret'];
    }
}
