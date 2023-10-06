<?php

namespace App\Rules;

use Cron\CronExpression;
use Illuminate\Contracts\Validation\Rule;

class Cron implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return CronExpression::isValidExpression($value) || mb_strtolower($value) === 'off' || mb_strtolower($value) === 'event';
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be a valid cron expression.';
    }
}
