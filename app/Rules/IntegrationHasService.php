<?php

namespace App\Rules;

use App\Models\Tapestry\Service;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class IntegrationHasService implements Rule
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
        return Service::where('id', $value)->whereIn('username', Auth::user()->company->getIntegrationUsernames())->exists();
    }

    public function message(): string
    {
        return 'The :attribute must must belong to one of your integrations.';
    }
}
