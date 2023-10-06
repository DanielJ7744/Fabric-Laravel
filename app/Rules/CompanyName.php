<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CompanyName implements Rule
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
        return preg_match('/^[a-z0-9 \-\&]+$/i', $value);
    }

    public function message(): string
    {
        return 'The :attribute must only contain alphanumeric, ampersand, dash and space characters only.';
    }
}
