<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class FilterOperatorKey implements Rule
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
        return ctype_alpha($value);

    }

    public function message(): string
    {
        return 'The :attribute must only contain alphabetic characters.';
    }
}
