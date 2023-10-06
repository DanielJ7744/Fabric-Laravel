<?php

namespace App\Http\Interfaces;

interface ConnectorAuthManagerInterface
{
    public static function getService(string $driver, array $credentials, string $authType): string;

    public function getTapestryFormat(string $driver, array $credentials, string $authType): array;

    public function getFabricFormat(string $driver, array $credentials, string $authType): array;

    public function getObfuscatedFields(string $driver, array $credentials, string $authType): array;
}
