<?php

namespace App\Http\Interfaces;

use App\Exceptions\MissingSystemAuthAttributesException;
use App\Http\Abstracts\SystemAuthAbstract;
use InvalidArgumentException;
use SoapFault;

interface SystemAuthManagerInterface
{
    /**
     * Get an OAuth provider implementation.
     *
     * @param string|null $driver
     * @param array $credentials
     *
     * @return SystemAuthAbstract
     *
     * @throws InvalidArgumentException
     * @throws MissingSystemAuthAttributesException
     * @throws SoapFault
     */
    public function driver(string $driver = null, array $credentials = []): SystemAuthAbstract;

    public function getRules(string $driver, array $credentials): array;

    public function getUpdateRules(string $driver, array $credentials): array;
}
