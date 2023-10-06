<?php

namespace App\Http\Interfaces;

use App\Http\Abstracts\SystemOAuth2Abstract;

interface SystemOAuth2ManagerInterface
{
    /**
     * Get an OAuth provider implementation.
     *
     * @param string|null $driver
     *
     * @return SystemOAuth2Abstract
     */
    public function driver(string $driver = null);
}
