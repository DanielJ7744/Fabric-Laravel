<?php

namespace Tests\Feature\Http\Services\Auth;

use App\Facades\SystemAuth;
use App\Http\Services\Auth\BIService;
use App\Models\Fabric\Subscription;
use Tests\LaravelTestCase;
use App\Models\Fabric\User;
use App\Models\Fabric\System;
use App\Models\Fabric\Company;
use App\Models\Fabric\Integration;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BIServiceTest extends LaravelTestCase
{
    use RefreshDatabase;

    protected User $userWithTier;
    protected User $userWithoutTier;
    protected Integration $integrationWithTier;
    protected Integration $integrationWithoutTier;
    protected System $system;
    protected Company $companyWithTier;
    protected Company $companyWithoutTier;

    public function setup(): void
    {
        parent::setUp();
        $this->system = factory(System::class)->create(['factory_name' => 'BI']);


        $this->companyWithTier = factory(Company::class)->create();
        $this->companyWithTier->subscriptions()->detach();
        $this->companyWithTier->subscriptions()->sync(factory(Subscription::class)->create(['business_insights' => true]));
        $this->userWith = $this->companyWithTier->users()->save(factory(User::class)->states('client user')->make());
        $this->integrationWithTier = $this->companyWithTier->integrations()->save(factory(Integration::class)->make([
            'name' => 'Test integration UK',
            'username' => 'table'
        ]));

        $this->companyWithoutTier = factory(Company::class)->create();
        $this->companyWithoutTier->subscriptions()->detach();
        $this->companyWithoutTier->subscriptions()->syncWithoutDetaching(factory(Subscription::class)->create(['business_insights' => false]));
        $this->userWithoutTier = $this->companyWithoutTier->users()->save(factory(User::class)->states('client user')->make());
        $this->integrationWithoutTier = $this->companyWithoutTier->integrations()->save(factory(Integration::class)->make([
            'name' => 'Test integration UK',
            'username' => 'table'
        ]));
    }

    public function test_company_must_have_required_subscription_tier(): void
    {
        $credentials = [
            'connector_name' => 'test',
            'timezone' => 'UTC',
            'date_format' => 'd/m/Y',
        ];

        $data = [
            'credentials' => $credentials,
            'environment' => 'test',
            'connectorName' => 'test',
            'timeZone' => 'UTC',
            'dateFormat' => 'd/m/Y',
            'integration_id' => $this->integrationWithoutTier->id,
            'system_id' => $this->system->id,
        ];

        $this->passportAs($this->userWithoutTier)
            ->postJson(route('api.v2.connectors.store'), $data)
            ->assertJsonValidationErrors([
            'system_id' => 'You are unable to add BI connectors. Please upgrade your subscription.',
        ]);
    }

    public function test_authenticate_method(): void
    {
        $credentials = [
            'connector_name' => 'test',
            'timezone' => 'UTC',
            'date_format' => 'd/m/Y',
            'authorisation_type' => 'none',
        ];

        $data = [
            'credentials' => $credentials,
            'authorisation_type' => 'none',
            'environment' => 'test',
            'connectorName' => 'test',
            'timeZone' => 'UTC',
            'dateFormat' => 'd/m/Y',
            'integration_id' => $this->integrationWithTier->id,
            'system_id' => $this->system->id,
        ];

        $biMock = $this->partialMock(BIService::class, fn ($mock) => $mock
            ->shouldReceive('authenticate')
            ->andReturn(['statusCode' => 200]));

        SystemAuth::partialMock()
            ->shouldReceive('driver')
            ->once()
            ->with('BI', $credentials)
            ->andReturn($biMock);
        $response = $this->passportAs($this->userWith)
            ->postJson(route('api.v2.connectors.store'), $data);
            $response->assertCreated();
    }
}
