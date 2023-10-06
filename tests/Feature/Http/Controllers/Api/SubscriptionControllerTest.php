<?php

namespace Tests\Feature\Http\Controllers\Api;

use Tests\LaravelTestCase;
use App\Models\Fabric\User;
use App\Models\Fabric\Company;
use App\Models\Fabric\Subscription;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubscriptionControllerTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();
        $this->subscription = factory(Subscription::class)->create();
        $this->withoutPermissions = $this->company->users()->save(factory(User::class)->make());
        $this->withPermissions = $this->company->users()->save(factory(User::class)->states('client user')->make());
    }

    public function test_users_with_permissions_can_retrieve_subscriptions(): void
    {
        $this
            ->passportAs($this->withPermissions)
            ->getJson(route('api.v2.subscriptions.index'))
            ->assertOk();
    }

    public function test_users_without_permissions_cannot_retrieve_subscriptions(): void
    {
        $this
            ->passportAs($this->withoutPermissions)
            ->getJson(route('api.v2.subscriptions.index'))
            ->assertForbidden();
    }

    public function test_users_with_permissions_can_retrieve_a_subscription(): void
    {
        $this
            ->passportAs($this->withPermissions)
            ->getJson(route('api.v2.subscriptions.show', $this->subscription))
            ->assertOk();
    }

    public function test_users_without_permissions_cannot_retrieve_a_subscription(): void
    {
        $this
            ->passportAs($this->withoutPermissions)
            ->getJson(route('api.v2.subscriptions.show', $this->subscription))
            ->assertForbidden();
    }
}
