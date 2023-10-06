<?php

namespace App\Rules\Systems;

use Illuminate\Contracts\Validation\Rule;

class Name implements Rule
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
        return preg_match('/^[a-z\d ]+$/i', $value);

    }

    public function message(): string
    {
        return 'The :attribute must only contain alphanumeric characters.';
    }
}
