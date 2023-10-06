<?php

namespace App\Facades;

use App\Http\Abstracts\SystemOAuth2Abstract;
use App\Http\Interfaces\SystemOAuth2ManagerInterface;
use Illuminate\Support\Facades\Facade;

/**
 * @method static SystemOAuth2Abstract driver(string $driver = null)
 */
class SystemOAuth2 extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return SystemOAuth2ManagerInterface::class;
    }
}
