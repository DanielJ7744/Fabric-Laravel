<?php

namespace App\Http\Services\Auth;

use App\Http\Abstracts\SystemAuthAbstract;

class InboundApiService extends SystemAuthAbstract
{
    public static function getRules(): array
    {
        return [];
    }

    public static function getUpdateRules(): array
    {
        return [];
    }

    public function authenticate(): ?array
    {
        return [true];
    }

    public function verify(?array $authResult): bool
    {
        return true;
    }

    public static function getObfuscatedFields(): array
    {
        return [];
    }
}
