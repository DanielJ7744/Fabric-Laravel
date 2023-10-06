<?php

namespace App\Rules;

use DateTimeZone;
use Exception;
use Illuminate\Contracts\Validation\Rule;

class Timezone implements Rule
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
        try {
            $dateTimeZone = new DateTimeZone($value);

            return !empty($dateTimeZone);
        } catch(Exception $e) {
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be a valid IANA timezone.';
    }
}
