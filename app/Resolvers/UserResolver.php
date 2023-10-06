<?php

namespace App\Resolvers;

use Illuminate\Support\Facades\Auth;

class UserResolver implements \OwenIt\Auditing\Contracts\UserResolver
{
    public static function resolve()
    {
        return Auth::user();
    }
}
