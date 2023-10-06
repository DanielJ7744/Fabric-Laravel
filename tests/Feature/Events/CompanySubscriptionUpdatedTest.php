<?php

namespace Tests\Feature\Events;

use App\Events\CompanySubscriptionUpdated;
use App\Models\Fabric\Company;
use App\Models\Fabric\Integration;
use App\Models\Fabric\Subscription;
use App\Models\Fabric\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\LaravelTestCase;

class CompanySubscriptionUpdatedTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->company = factory(Company::class)->create();
        $this->subscription = factory(Subscription::class)->create();
        $this->integration = $this->company->integrations()->save(factory(Integration::class)->make());
        $this->withPermissions = $this->company->users()->save(factory(User::class)->states('patchworks admin')->make());
    }

    public function test_subscription_update_despatches_event(): void
    {
        Event::fake([CompanySubscriptionUpdated::class]);

        $this
            ->passportAs($this->withPermissions)
            ->put(route('api.v2.admin.company.subscription.update', [$this->company, $this->subscription]))
            ->assertOk()
            ->assertJsonPath('message', 'Subscription assigned to company successfully.');

        Event::assertDispatched(CompanySubscriptionUpdated::class);
    }
}
