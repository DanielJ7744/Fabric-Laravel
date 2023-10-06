<?php

namespace App\Facades;

use App\Http\Abstracts\ConnectorAuthAbstract;
use App\Http\Abstracts\SystemWebhookAbstract;
use App\Http\Interfaces\SystemWebhookManagerInterface;
use Illuminate\Support\Facades\Facade;

/**
 * @method static SystemWebhookAbstract driver(string $driver = null, array $attributes, ?ConnectorAuthAbstract $authoriser = null)
 * @method static array getRules(string $driver)
 */
class SystemWebhook extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return SystemWebhookManagerInterface::class;
    }
}
