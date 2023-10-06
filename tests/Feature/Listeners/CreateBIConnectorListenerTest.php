<?php

namespace Tests\Feature\Listeners;

use App\Listeners\CreateBIConnectorListener;
use App\Models\Fabric\Company;
use App\Models\Fabric\Integration;
use App\Models\Fabric\Subscription;
use App\Models\Fabric\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateBIConnectorListenerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->biCompany = factory(Company::class)->create();
        $this->company = factory(Company::class)->create();
        $biSubscription = factory(Subscription::class)->create(['business_insights' => true]);
        $noSubscription = factory(Subscription::class)->create();
        $this->biCompany->subscriptions()->syncWithoutDetaching($biSubscription);
        $this->company->subscriptions()->syncWithoutDetaching($noSubscription);
        $this->biIntegration = $this->biCompany->integrations()->save(factory(Integration::class)->make());
        $this->integration = $this->company->integrations()->save(factory(Integration::class)->make());
        $this->withPermissions = $this->company->users()->save(factory(User::class)->states('patchworks admin')->make());
    }

    public function test_integration_created_event_without_subscription_does_not_create_bi_connector(): void
    {
        $this->integration->generateIdxTable();
        $this->mock(CreateBIConnectorListener::class)->shouldNotHaveReceived('createBIConnector');
    }

    public function test_integration_created_event_with_subscription_creates_bi_connector(): void
    {
        $mock = $this->partialMock(CreateBIConnectorListener::class)->shouldReceive('createBIConnector');
        $this->biIntegration->generateIdxTable();
        $mock->getMock()->shouldHaveReceived('integrationCreated');
        $mock->getMock()->shouldHaveReceived('createBIConnector');
    }

    public function test_bi_subscription_updated_event_creates_bi_connector(): void
    {
        $mock = $this->partialMock(CreateBIConnectorListener::class)->shouldReceive('createBIConnector');
        $this->passportAs($this->withPermissions)
            ->put(route('api.v2.admin.company.subscription.update', [
                $this->company,
                factory(Subscription::class)->create(['business_insights' => true])
            ]))
            ->assertOk()
            ->assertJsonPath('message', 'Subscription assigned to company successfully.');

        $mock->getMock()->shouldHaveReceived('companySubscriptionUpdated');
        $mock->getMock()->shouldHaveReceived('createBIConnector');
    }

    public function test_non_bi_subscription_updated_event_does_not_create_bi_connector(): void
    {
        $mock = $this->partialMock(CreateBIConnectorListener::class);
        $this->passportAs($this->withPermissions)
            ->put(route('api.v2.admin.company.subscription.update', [
                $this->company,
                factory(Subscription::class)->create(['business_insights' => false])
            ]))
            ->assertOk()
            ->assertJsonPath('message', 'Subscription assigned to company successfully.');

        $mock->shouldHaveReceived('companySubscriptionUpdated');
        $mock->shouldNotHaveReceived('createBIConnector');
    }
}
