<?php

namespace Tests\Feature\Jobs;

use App\Jobs\DisableServicesForExpiredTrials;
use App\Models\Fabric\Company;
use App\Models\Fabric\Integration;
use App\Models\Tapestry\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\LaravelTestCase;

class DisableServicesForExpiredTrialsTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();
        $this->integration = $this->company->integrations()->save(factory(Integration::class)->make());
        $this->service = $this->integration->services()->save(factory(Service::class)->make());
    }

    public function test_expired_services_are_disabled(): void
    {
        $this->company->update(['trial_ends_at' => now()->subDays(1)]);
        $enabled = $this->integration->services()->save(factory(Service::class)->make(['status' => true]));
        $disabled = $this->integration->services()->save(factory(Service::class)->make(['status' => false]));

        DisableServicesForExpiredTrials::dispatchNow();

        $this->assertFalse($enabled->fresh()->status);
        $this->assertFalse($disabled->fresh()->status);
    }

    public function test_subscribed_services_are_unaffected(): void
    {
        $this->company->update(['trial_ends_at' => now()->subDays(1)]);
        $subscribed = factory(Company::class)->create(['trial_ends_at' => null]);
        $integration = $subscribed->integrations()->save(factory(Integration::class)->make());
        $enabled = $integration->services()->save(factory(Service::class)->make(['status' => true]));

        DisableServicesForExpiredTrials::dispatchNow();

        $this->assertTrue($enabled->fresh()->status);
    }
}
