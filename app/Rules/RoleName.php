<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class RoleName implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param string $value
     *
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        return preg_match('/^[a-z ]+$/i', $value);
    }

    public function message(): string
    {
        return 'The :attribute must only contain alphabetical and space characters only.';
    }
}
