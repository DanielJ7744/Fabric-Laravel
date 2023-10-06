<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class UserPassword implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        return preg_match('/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/i', $value);
    }

    public function message(): string
    {
        return "The :attribute must contain characters from at least 3 of the following categories:\n
         Uppercase (A - Z)\n
         Lowercase (a - z)\n
         Digit (0 - 9)\n
         Special character (!, $, #, or %)";
    }
}
