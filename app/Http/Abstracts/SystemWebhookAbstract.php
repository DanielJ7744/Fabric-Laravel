<?php

namespace App\Http\Abstracts;

use App\Http\Interfaces\WebhookProviderInterface;

abstract class SystemWebhookAbstract implements WebhookProviderInterface
{
    protected array $attributes;

    protected ConnectorAuthAbstract $authoriser;

    public function __construct(array $attributes, ConnectorAuthAbstract $authoriser) {
        $this->authoriser = $authoriser;
        $this->attributes = $attributes;
    }
}
