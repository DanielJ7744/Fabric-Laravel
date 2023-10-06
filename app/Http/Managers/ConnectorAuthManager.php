<?php

namespace App\Http\Managers;

use App\Enums\AuthTypes;
use App\Http\Interfaces\ConnectorAuthManagerInterface;
use Illuminate\Support\Str;
use InvalidArgumentException;

class ConnectorAuthManager implements ConnectorAuthManagerInterface
{
    /**
     * @param string $driver
     * @param array $credentials
     *
     * @return string
     *
     * @throws InvalidArgumentException
     */
    public static function getService(string $driver, array $credentials, string $authType): string
    {
        $method = sprintf('get%sService', Str::studly($driver));

        if (method_exists(SystemOAuth2Manager::class, $method) && $authType === AuthTypes::OAUTH2) {
            return SystemOAuth2Manager::$method();
        }

        if (method_exists(SystemAuthManager::class, $method)) {
            return SystemAuthManager::$method($credentials);
        }

        throw new InvalidArgumentException("Driver [$driver] not supported.");
    }

    public function getTapestryFormat(string $driver, array $credentials, string $authType): array
    {
        $service = $this->getService($driver, $credentials, $authType);

        return $service::getTapestryFormat($credentials);
    }

    public function getFabricFormat(string $driver, array $credentials, ?string $authType): array
    {
        $service = $this->getService($driver, $credentials, $authType);

        return $service::getFabricFormat($credentials);
    }

    public function getObfuscatedFields(string $driver, array $credentials, ?string $authType): array
    {
        $service = $this->getService($driver, $credentials, $authType);

        return $service::getObfuscatedFields();
    }
}
