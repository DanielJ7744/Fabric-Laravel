<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Fabric\Company;
use App\Models\Fabric\Integration;
use App\Models\Fabric\User;
use App\Models\Tapestry\Service;
use App\Models\Tapestry\ServiceLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\LaravelTestCase;

class ServiceLogControllerTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();
        $this->integration = $this->company->integrations()->save(factory(Integration::class)->make());
        $this->service = $this->integration->services()->save(factory(Service::class)->make());
        $this->serviceLog = factory(ServiceLog::class)->create([
            'service_id' => $this->service->id,
            'username' => $this->integration->username
        ]);
        $this->withoutPermissions = $this->company->users()->save(factory(User::class)->make());
        $this->withPermissions = $this->company->users()->save(factory(User::class)->states('client user')->make());
    }

    public function test_users_with_permissions_can_retrieve_service_logs(): void
    {
        $this
            ->passportAs($this->withPermissions)
            ->getJson(route('api.v2.service-logs.index'))
            ->assertOk();
    }

    public function test_users_without_permissions_cannot_retrieve_service_logs(): void
    {
        $this
            ->passportAs($this->withoutPermissions)
            ->getJson(route('api.v2.service-logs.index'))
            ->assertForbidden();
    }

    public function test_users_with_permissions_can_retrieve_a_service_log(): void
    {
        $this
            ->passportAs($this->withPermissions)
            ->getJson(route('api.v2.service-logs.show', $this->serviceLog))
            ->assertOk();
    }

    public function test_users_without_permissions_cannot_retrieve_a_service_log(): void
    {
        $this
            ->passportAs($this->withoutPermissions)
            ->getJson(route('api.v2.service-logs.show', $this->serviceLog))
            ->assertForbidden();
    }
}
