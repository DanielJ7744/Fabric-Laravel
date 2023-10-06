<?php

namespace App\Rules\Khaos;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Str;

class Https implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        return Str::startsWith(Str::lower($value), ['https://', 'http://']);
    }

    public function message(): string
    {
        return 'The :attribute must be a valid URL.';
    }
}
