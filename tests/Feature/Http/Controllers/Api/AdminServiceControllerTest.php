<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Fabric\Company;
use App\Models\Fabric\Integration;
use App\Models\Fabric\Subscription;
use App\Models\Fabric\User;
use App\Models\Tapestry\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\LaravelTestCase;

class AdminServiceControllerTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();
        $this->integration = $this->company->integrations()->save(factory(Integration::class)->make(['username' => 'table']));
        $this->service = $this->integration->services()->save(factory(Service::class)->make());
        $this->withoutPermissions = $this->company->users()->save(factory(User::class)->states('client user')->make());
        $this->withPermissions = $this->company->users()->save(factory(User::class)->states('patchworks admin')->make());
    }

    public function test_user_with_permission_can_update_services(): void
    {
        $this
            ->passportAs($this->withPermissions)
            ->putJson(route('api.v2.admin.services.update', $this->service), factory(Service::class)->raw())
            ->assertOk();
    }

    public function test_user_without_permission_cannot_update_services(): void
    {
        $this
            ->passportAs($this->withoutPermissions)
            ->putJson(route('api.v2.admin.services.update', $this->service), factory(Service::class)->raw())
            ->assertForbidden();
    }

    public function test_user_cannot_enable_more_services_than_their_subscription_allows(): void
    {
        $this->service->disable();
        Subscription::unguarded(fn () => $this->company->subscriptions->first()->update(['services' => 0]));

        $this
            ->passportAs($this->withPermissions)
            ->putJson(route('api.v2.admin.services.update', $this->service), ['status' => true])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
