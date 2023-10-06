<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class FilterFieldName implements Rule
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
        return preg_match('/^[a-z ]+$/i', $value);

    }

    public function message(): string
    {
        return 'The :attribute must contain alphabetical and space characters only.';
    }
}
