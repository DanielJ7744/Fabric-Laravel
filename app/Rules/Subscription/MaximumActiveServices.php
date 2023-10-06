<?php

namespace App\Rules\Subscription;

use Illuminate\Contracts\Validation\Rule;

class MaximumActiveServices implements Rule
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
        if (request()->service->status) {
            return true;
        }

        $activeServices = auth()->user()->company->subscriptionUsage()->active_services;
        $maximumActiveServices = auth()->user()->company->subscriptionAllowance()->services;

        return $activeServices < $maximumActiveServices;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'You have reached your active service limit. Please upgrade your subscription.';
    }
}
