<?php

namespace App\Http\Interfaces;

interface ConnectorFormatInterface
{
    public static function getTapestryFormat(array $credentials): array;

    public static function getFabricFormat(array $credentials): array;
}
