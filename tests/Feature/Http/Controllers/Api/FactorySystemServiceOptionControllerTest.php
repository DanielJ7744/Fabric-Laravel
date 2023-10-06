<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Fabric\Company;
use App\Models\Fabric\FactorySystemServiceOption;
use App\Models\Fabric\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\LaravelTestCase;

class FactorySystemServiceOptionControllerTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->factorySystemServiceOption = factory(FactorySystemServiceOption::class)->create();
        $this->company = factory(Company::class)->create();
        $this->withoutPermissions = $this->company->users()->save(factory(User::class)->make());
        $this->withPermissions = $this->company->users()->save(factory(User::class)->states('client user')->make());
    }

    public function test_user_with_permission_can_retrieve_factory_system_service_options(): void
    {
        $this->passportAs($this->withPermissions)
            ->getJson(route('api.v2.factory-systems.factory-system-service-options.index', $this->factorySystemServiceOption->factory_system_id))
            ->assertOk();
    }

    public function test_user_without_permission_cannot_retrieve_factory_system_service_options(): void
    {
        $this->passportAs($this->withoutPermissions)
            ->getJson(route('api.v2.factory-systems.factory-system-service-options.index', $this->factorySystemServiceOption->factory_system_id))
            ->assertForbidden();
    }

    public function test_user_with_permission_can_retrieve_a_factory_system_service_option(): void
    {
        $this->passportAs($this->withPermissions)
            ->getJson(route('api.v2.factory-systems.factory-system-service-options.show', [$this->factorySystemServiceOption->factory_system_id, $this->factorySystemServiceOption]))
            ->assertOk()
            ->assertJsonPath('data.id', $this->factorySystemServiceOption->id);
    }

    public function test_user_without_permission_cannot_retrieve_a_factory_system_service_option(): void
    {
        $this->passportAs($this->withoutPermissions)
            ->getJson(route('api.v2.factory-systems.factory-system-service-options.show', [$this->factorySystemServiceOption->factory_system_id, $this->factorySystemServiceOption]))
            ->assertForbidden();
    }
}
