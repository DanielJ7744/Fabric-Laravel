<?php

namespace App\Http\Helpers;

use Neomerx\JsonApi\Document\Error;
use Illuminate\Support\Facades\Log;

class ErrorHelper
{
    public static function create(string $title, string $detail = null): Error
    {
        Log::error($detail ?? $title);
        return new Error(null, null, 500, 500, $title, $detail);
    }
}
