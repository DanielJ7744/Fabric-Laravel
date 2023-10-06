<?php

namespace App\Rules\Rebound;

use Illuminate\Contracts\Validation\Rule;

class Host implements Rule
{
    private const HOSTS = [
        'intelligentreturns.net',
        'test.intelligentreturns.net',
    ];

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
        $parsed = parse_url($value);

        return isset($parsed['host']) && in_array($parsed['host'], self::HOSTS);
    }

    public function message(): string
    {
        return 'The URL host must be one of `test.intelligentreturns.net`, `intelligentreturns.net`';
    }
}
