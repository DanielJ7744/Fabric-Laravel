<?php

namespace App\Http\Interfaces;

use App\Exceptions\MissingSystemAuthAttributesException;
use App\Http\Abstracts\ConnectorAuthAbstract;
use App\Http\Abstracts\SystemWebhookAbstract;
use InvalidArgumentException;
use SoapFault;

interface SystemWebhookManagerInterface
{
    /**
     * Get an OAuth provider implementation.
     *
     * @param string|null $driver
     * @param array $attributes
     * @param ConnectorAuthAbstract|null $authoriser
     *
     * @return SystemWebhookAbstract
     *
     * @throws InvalidArgumentException
     * @throws MissingSystemAuthAttributesException
     * @throws SoapFault
     */
    public function driver(string $driver = null, array $attributes, ?ConnectorAuthAbstract $authoriser = null): SystemWebhookAbstract;

    public function getRules(string $driver): array;
}
