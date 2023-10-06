<?php

namespace App\Http\Interfaces;

interface BatchRedirectInterface
{
    public static function getScriptLibraryUri(string $uri): string;
}
