<?php

namespace App\Facades;

use App\Http\Interfaces\ConnectorAuthManagerInterface;
use Illuminate\Support\Facades\Facade;

/**
 * @method static string getService(string $driver, array $credentials, string $authType)
 * @method static array getTapestryFormat(string $driver, array $credentials, string $authType)
 * @method static array getFabricFormat(string $driver, array $credentials, string $authType)
 * @method static array getObfuscatedFields(string $driver, array $credentials, string $authType)
 */
class ConnectorAuth extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return ConnectorAuthManagerInterface::class;
    }
}
