<?php

namespace App\Http\Interfaces;

interface WebhookProviderInterface
{
    public function subscribe(): int;

    public function unsubscribe(int $id): bool;

    public static function getRules(): array;
}
