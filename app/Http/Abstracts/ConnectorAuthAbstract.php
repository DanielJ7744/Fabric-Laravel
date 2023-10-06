<?php

namespace App\Http\Abstracts;

use App\Http\Interfaces\ConnectorFormatInterface;
use App\Http\Interfaces\ConnectorObfuscateInterface;

abstract class ConnectorAuthAbstract implements ConnectorObfuscateInterface, ConnectorFormatInterface
{
    public static function getTapestryFormat(array $credentials): array
    {
        return $credentials;
    }

    public static function getFabricFormat(array $credentials): array
    {
        return $credentials;
    }
}
