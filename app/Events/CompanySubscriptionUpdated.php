<?php

namespace App\Events;

use App\Models\Fabric\Company;
use App\Models\Fabric\Subscription;
use Illuminate\Queue\SerializesModels;

class CompanySubscriptionUpdated
{
    use SerializesModels;

    public Company $company;

    public Subscription $subscription;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Company $company, Subscription $subscription)
    {
        $this->company = $company;
        $this->subscription = $subscription;
    }
}
