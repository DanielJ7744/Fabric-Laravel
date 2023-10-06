<?php

namespace App\Http\Services\Webhook;

use App\Http\Services\Auth\PeoplevoxService as PeoplevoxAuthService;
use App\Http\Abstracts\SystemWebhookAbstract;
use Illuminate\Support\Arr;
use SoapClient;

class PeoplevoxService extends SystemWebhookAbstract
{
    private SoapClient $client;

    public static function getRules(): array
    {
        return [
            'filter' => [
                'string',
            ],
            'postParams' => [
                'string',
            ],
        ];
    }

    public function __construct(array $attributes, PeoplevoxAuthService $authoriser, SoapClient $soapClient)
    {
        parent::__construct($attributes, $authoriser);
        $this->client = $soapClient;
    }

    public function subscribe(): int
    {
        $result = json_decode(json_encode($this->client->SubscribePostEvent([
            'eventType' => $this->attributes['eventType'],
            'filter' => $this->attributes['filter'] ?? '',
            'postUrl' => $this->attributes['callbackUrl'],
            'postParams' => $this->attributes['postParams'] ?? '',
            'encodeParameterData' => false,
        ])), true);

        abort_if(
            Arr::get($result, 'SubscribePostEventResult.ResponseId') === -1
            || !Arr::has($result, 'SubscribePostEventResult.Detail'),
            422,
            sprintf('Failed to subscribe to webhook: %s', $result['SubscribePostEventResult']['Detail'])
        );

        return (int) $result['SubscribePostEventResult']['Detail'];
    }

    public function unsubscribe(int $id): bool
    {
        $result = json_decode(json_encode($this->client->UnsubscribeEvent([
            'subscriptionId' => $id
        ])), true);

        abort_if(
            Arr::get($result, 'UnsubscribeEventResult.ResponseId') === -1
            || !Arr::has($result, 'UnsubscribeEventResult.Detail'),
            422,
            sprintf('Failed to unsubscribe from webhook: %s', $result['UnsubscribeEventResult']['Detail'])
        );

        return $result['UnsubscribeEventResult']['Detail'] === 'True';
    }
}
