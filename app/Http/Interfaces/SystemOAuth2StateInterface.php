<?php

namespace App\Http\Interfaces;

interface SystemOAuth2StateInterface
{
    public CONST UNIQUE_STATE_CACHE_KEY = 'oauth-2-state:%s';

    public function getState(): string;

    public function setState(): void;
}
