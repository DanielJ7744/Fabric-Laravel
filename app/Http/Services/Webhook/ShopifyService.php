<?php

namespace App\Http\Services\Webhook;

use App\Http\Abstracts\SystemWebhookAbstract;
use App\Http\Services\Auth\ShopifyService as ShopifyAuthService;
use GuzzleHttp\Client;
use Illuminate\Support\Arr;

class ShopifyService extends SystemWebhookAbstract
{
    protected Client $client;

    public function __construct(array $attributes, ShopifyAuthService $authoriser, Client $client)
    {
        parent::__construct($attributes, $authoriser);
        $this->client = $client;
    }

    public static function getRules(): array
    {
        return [
            'fields' => [
                'array',
            ],
        ];
    }

    public function subscribe(): int
    {
        $webhook = [
            'topic' => $this->attributes['eventType'],
            'address' => $this->attributes['callbackUrl'],
            'format' => 'json',
        ];
        if (isset($this->attributes['fields']) && !empty($this->attributes['fields'])) {
            $webhook['fields'] = $this->attributes['fields'];
        }

        $result = json_decode($this->client->post(
            sprintf(
                '%s/%s.json',
                $this->authoriser->getBaseUrl(),
                config('external-app.webhook_endpoints.shopify')
            ),
            [
                'json' => ['webhook' => $webhook],
                'headers' => array_merge(
                    ['Content-Type' => 'application/json'],
                    $this->authoriser->getAuthHeaders()
                )
            ]
        )->getBody()->getContents(), true);

        abort_if(!Arr::has($result, 'webhook.id'), 422, 'Failed to subscribe to webhook');

        return (int) $result['webhook']['id'];
    }

    public function unsubscribe(int $id): bool
    {
        return $this->client->delete(
            sprintf(
                '%s/%s/%s.json',
                $this->authoriser->getBaseUrl(),
                config('external-app.webhook_endpoints.shopify'),
                $id
            ),
            ['headers' => $this->authoriser->getAuthHeaders()]
        )->getStatusCode() === 200;
    }
}
