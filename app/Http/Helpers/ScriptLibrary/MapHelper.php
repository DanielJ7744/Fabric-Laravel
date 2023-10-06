<?php

namespace App\Http\Helpers\ScriptLibrary;

use Illuminate\Support\Facades\Auth;

trait MapHelper
{
    public static function getScriptLibraryUri(string $uri): string
    {
        return sprintf(
            'company/%s/%s',
            Auth::user()->company_id,
            $uri
        );
    }
}
