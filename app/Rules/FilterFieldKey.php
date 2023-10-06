<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class FilterFieldKey implements Rule
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
        return preg_match('/^[a-zA-Z_]+$/i', $value);
    }

    public function message(): string
    {
        return 'The :attribute must contain alphabetical and underscore characters only.';
    }
}
