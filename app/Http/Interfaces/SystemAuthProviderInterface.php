<?php

namespace App\Http\Interfaces;

interface SystemAuthProviderInterface
{
    public function authenticate(): ?array;

    public static function getRules(): array;

    public static function getUpdateRules(): array;

    public function verify(?array $authResult): bool;
}
