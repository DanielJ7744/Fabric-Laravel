<?php

namespace App\Rules\FactorySystem;

use Illuminate\Contracts\Validation\Rule;

class DisplayName implements Rule
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
        return preg_match('/^[a-z0-9 ]+$/i', $value);

    }

    public function message(): string
    {
        return 'The :attribute must contain alphanumeric and space characters only.';
    }
}
