<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Fabric\Company;
use App\Models\Fabric\FactorySystemServiceOption;
use App\Models\Fabric\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\LaravelTestCase;

class AdminFactorySystemServiceOptionControllerTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();
        $this->factorySystemServiceOption = factory(FactorySystemServiceOption::class)->create();
        $this->withoutPermissions = $this->company->users()->save(factory(User::class)->make());
        $this->withPermissions = $this->company->users()->save(factory(User::class)->states('patchworks admin')->make());
    }

    public function test_user_with_permission_can_create_a_factory_system_service_option(): void
    {
        $attributes = factory(FactorySystemServiceOption::class)->raw();

        $this->passportAs($this->withPermissions)
            ->postJson(route(
                'api.v2.admin.factory-systems.factory-system-service-options.store',
                $this->factorySystemServiceOption->factory_system_id
            ), $attributes)
            ->assertCreated();
    }

    public function test_user_without_permission_cannot_create_a_factory_system_service_option(): void
    {
        $attributes = factory(FactorySystemServiceOption::class)->raw();

        $this->passportAs($this->withoutPermissions)
            ->postJson(route(
                'api.v2.admin.factory-systems.factory-system-service-options.store',
                $this->factorySystemServiceOption->factory_system_id
            ), $attributes)
            ->assertForbidden();
    }

    public function test_user_with_permission_can_update_a_factory_system_service_option(): void
    {
        $this
            ->passportAs($this->withPermissions)
            ->putJson(route(
                'api.v2.admin.factory-systems.factory-system-service-options.update',
                [$this->factorySystemServiceOption->factory_system_id, $this->factorySystemServiceOption]
            ), ['value' => 'Updated Option'])
            ->assertOk();

        $this->assertSame('Updated Option', $this->factorySystemServiceOption->fresh()->value);
    }

    public function test_user_without_permission_cannot_update_a_factory_system_service_option(): void
    {
        $this
            ->passportAs($this->withoutPermissions)
            ->putJson(route(
                'api.v2.admin.factory-systems.factory-system-service-options.update',
                [$this->factorySystemServiceOption->factory_system_id, $this->factorySystemServiceOption]
            ), ['value' => 'Updated value'])
            ->assertForbidden();
    }

    public function test_user_with_permission_can_delete_a_factory_system_service_option(): void
    {
        $this
            ->passportAs($this->withPermissions)
            ->deleteJson(route(
                'api.v2.admin.factory-systems.factory-system-service-options.destroy',
                [$this->factorySystemServiceOption->factory_system_id, $this->factorySystemServiceOption]
            ))
            ->assertOk();

        $this->assertDatabaseMissing($this->factorySystemServiceOption->getTable(), [$this->factorySystemServiceOption->id]);
    }

    public function test_user_without_permission_cannot_delete_a_factory_system_service_option(): void
    {
        $this
            ->passportAs($this->withoutPermissions)
            ->deleteJson(route(
                'api.v2.admin.factory-systems.factory-system-service-options.destroy',
                [$this->factorySystemServiceOption->factory_system_id, $this->factorySystemServiceOption]
            ))
            ->assertForbidden();
    }
}
