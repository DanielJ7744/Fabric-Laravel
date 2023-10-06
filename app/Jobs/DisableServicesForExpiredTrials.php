<?php

namespace App\Jobs;

use App\Models\Fabric\Company;
use App\Models\Tapestry\Service;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class DisableServicesForExpiredTrials
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Disable company services when their trial period has expired.
     *
     * @return void
     */
    public function handle()
    {
        Company::with('integrations.services')->trialExpired()->chunk(5, function ($companies) {
            $companies->each(function ($company) {
                $company->integrations->pluck('services')->flatten()
                    ->filter(fn (Service $service) => $service->status)
                    ->each(fn (Service $service) => $service->disable())
                    ->each(fn (Service $service) => Log::info('Service disabled', [
                        'service_id' => $service->getKey(),
                        'message' => 'Trial expired'
                    ]));
            });
        });
    }
}
