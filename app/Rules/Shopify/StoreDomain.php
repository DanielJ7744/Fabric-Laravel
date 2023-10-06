<?php

namespace App\Rules\Shopify;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Str;

class StoreDomain implements Rule
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
        return Str::endsWith(Str::lower($value), '.myshopify.com');
    }

    public function message(): string
    {
        return 'The :attribute must be a myshopify.com domain.';
    }
}
