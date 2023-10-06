<?php

namespace App\Rules\Subscription;

use App\Models\Fabric\System;
use Illuminate\Contracts\Validation\Rule;

class SubscriptionHasBusinessInsights implements Rule
{
    private System $system;

    public function __construct(int $system)
    {
        $this->system = System::find($system);
    }

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
        if ($this->system->factory_name !== 'BI') {
            return true;
        }

        return auth()->user()->company->subscriptionAllowance()->business_insights;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'You are unable to add BI connectors. Please upgrade your subscription.';
    }
}
