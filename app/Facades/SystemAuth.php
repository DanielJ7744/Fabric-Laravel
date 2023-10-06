<?php

namespace App\Facades;

use App\Http\Abstracts\SystemAuthAbstract;
use App\Http\Interfaces\SystemAuthManagerInterface;
use Illuminate\Support\Facades\Facade;

/**
 * @method static SystemAuthAbstract driver(string $driver = null, array $credentials = [])
 * @method static array getRules(string $driver, array $credentials)
 * @method static array getUpdateRules(string $driver, array $credentials)
 */
class SystemAuth extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return SystemAuthManagerInterface::class;
    }
}
