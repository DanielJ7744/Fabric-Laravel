<?php

namespace App\Rules\FilterTemplates;

use Illuminate\Contracts\Validation\Rule;

class FilterKey implements Rule
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
        return preg_match('/^[a-z0-9 \-\&\+\_]+$/i', $value);
    }

    public function message(): string
    {
        return 'The :attribute must contain alphanumeric, ampersand, dash, underscore, plus and space characters only.';
    }
}
