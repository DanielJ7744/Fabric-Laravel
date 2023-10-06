<?php

namespace App\Jobs;

use App\Models\Fabric\Company;
use App\Models\Fabric\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AttachBaseSubscriptionToUnsubscribedCompanies
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Attach the legacy subscription to unsubscribed companies.
     *
     * @return void
     */
    public function handle()
    {
        $legacySubscription = Subscription::whereName('Base')->firstOrFail();

        Company::whereDoesntHave('subscriptions')->chunk(100, function ($companies) use ($legacySubscription) {
            $companies->each(function ($company) use ($legacySubscription) {
                $company->subscriptions()->attach($legacySubscription);
            });
        });
    }
}
