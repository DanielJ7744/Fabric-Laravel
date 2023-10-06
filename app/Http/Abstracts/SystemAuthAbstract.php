<?php

namespace App\Http\Abstracts;

use App\Http\Interfaces\SystemAuthProviderInterface;

abstract class SystemAuthAbstract extends ConnectorAuthAbstract implements SystemAuthProviderInterface
{
    public array $attributes;

    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }
}
