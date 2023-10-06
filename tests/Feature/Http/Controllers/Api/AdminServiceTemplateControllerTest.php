<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Fabric\Company;
use App\Models\Fabric\Entity;
use App\Models\Fabric\Factory;
use App\Models\Fabric\FactorySystem;
use App\Models\Fabric\ServiceTemplate;
use App\Models\Fabric\System;
use App\Models\Fabric\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\LaravelTestCase;

/**
 * @group company
 */
class AdminServiceTemplateControllerTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();
        $this->factory = factory(Factory::class)->create();
        $this->shopify = factory(System::class)->create(['name' => 'Shopify']);
        $this->peoplevox = factory(System::class)->create(['name' => 'Peoplevox']);
        $this->entity = factory(Entity::class)->create();
        $this->shopifyFactorySystem = factory(FactorySystem::class)->create([
            'factory_id' => $this->factory->id,
            'system_id' => $this->shopify->id,
            'entity_id' => $this->entity->id
        ]);
        $this->peoplevoxFactorySystem = factory(FactorySystem::class)->create([
            'factory_id' => $this->factory->id,
            'system_id' => $this->peoplevox->id,
            'entity_id' => $this->entity->id
        ]);
        $this->serviceTemplate = factory(ServiceTemplate::class)->create([
            'integration_id' => null,
            'source_factory_system_id' => $this->shopifyFactorySystem->id,
            'destination_factory_system_id' => $this->peoplevoxFactorySystem->id,
        ]);
        $this->withoutPermissions = $this->company->users()->save(factory(User::class)->make());
        $this->withPermissions = $this->company->users()->save(factory(User::class)->states('patchworks admin')->make());
    }

    public function test_user_with_permission_can_retrieve_service_templates(): void
    {
        $this
            ->passportAs($this->withPermissions)
            ->getJson(route('api.v2.admin.service-templates.index'))
            ->assertOk();
    }

    public function test_user_without_permission_cannot_retrieve_service_templates(): void
    {
        $this
            ->passportAs($this->withoutPermissions)
            ->getJson(route('api.v2.admin.service-templates.index'))
            ->assertForbidden();
    }

    public function test_user_with_permission_can_retrieve_a_service_template(): void
    {
        $this
            ->passportAs($this->withPermissions)
            ->getJson(route('api.v2.admin.service-templates.show', $this->serviceTemplate))
            ->assertOk()->assertJsonPath('data.id', $this->serviceTemplate->id);
    }

    public function test_user_without_permission_cannot_retrieve_a_service_template(): void
    {
        $this
            ->passportAs($this->withoutPermissions)
            ->getJson(route('api.v2.admin.service-templates.show', $this->serviceTemplate))
            ->assertForbidden();
    }

    public function test_user_with_permission_can_create_a_service_template(): void
    {
        $attributes = factory(ServiceTemplate::class)->raw([
            'name' => 'Test Template',
            'source_factory_system_id' => factory(FactorySystem::class)->create()->id,
            'destination_factory_system_id' => factory(FactorySystem::class)->create()->id,
            'integration_id' => null
        ]);

        $this
            ->passportAs($this->withPermissions)
            ->postJson(route('api.v2.admin.service-templates.store'), $attributes)
            ->assertCreated();

        $this->assertDatabaseHas((new ServiceTemplate())->getTable(), $attributes);
    }

    public function test_user_without_permission_cannot_create_a_service_template(): void
    {
        $attributes = factory(ServiceTemplate::class)->raw([
            'name' => 'Test Template',
            'source_factory_system_id' => factory(FactorySystem::class)->create()->id,
            'destination_factory_system_id' => factory(FactorySystem::class)->create()->id,
            'integration_id' => null
        ]);

        $this
            ->passportAs($this->withoutPermissions)
            ->postJson(route('api.v2.admin.service-templates.store'), $attributes)
            ->assertForbidden();
    }

    public function test_user_with_permission_can_update_a_service_template(): void
    {
        $serviceTemplate = factory(ServiceTemplate::class)->create([
            'name' => 'Test Template',
            'source_factory_system_id' => factory(FactorySystem::class)->create()->id,
            'destination_factory_system_id' => factory(FactorySystem::class)->create()->id,
            'integration_id' => null
        ]);

        $attributes = [
            'name' => 'Updated template'
        ];

        $this
            ->passportAs($this->withPermissions)
            ->putJson(route('api.v2.admin.service-templates.update', $serviceTemplate), $attributes)
            ->assertOk();

        $this->assertSame($attributes['name'], $serviceTemplate->fresh()->name);
    }

    public function test_user_without_permission_cannot_update_a_service_template(): void
    {
        $serviceTemplate = factory(ServiceTemplate::class)->create([
            'name' => 'Test Template',
            'source_factory_system_id' => factory(FactorySystem::class)->create()->id,
            'destination_factory_system_id' => factory(FactorySystem::class)->create()->id,
            'integration_id' => null
        ]);

        $attributes = [
            'name' => 'Updated template'
        ];

        $this
            ->passportAs($this->withoutPermissions)
            ->putJson(route('api.v2.admin.service-templates.update', $serviceTemplate), $attributes)
            ->assertForbidden();
    }

    public function test_user_with_permission_can_delete_a_service_template(): void
    {
        $serviceTemplate = factory(ServiceTemplate::class)->create([
            'source_factory_system_id' => factory(FactorySystem::class)->create()->id,
            'destination_factory_system_id' => factory(FactorySystem::class)->create()->id,
            'integration_id' => null
        ]);

        $this
            ->passportAs($this->withPermissions)
            ->deleteJson(route('api.v2.admin.service-templates.destroy', $serviceTemplate))
            ->assertOk()
            ->assertJsonPath('message', 'Service template deleted successfully.');

        $this->assertDatabaseMissing($serviceTemplate->getTable(), $serviceTemplate->only('id'));
    }

    public function test_user_without_permission_cannot_delete_a_service_template(): void
    {
        $serviceTemplate = factory(ServiceTemplate::class)->create([
            'source_factory_system_id' => factory(FactorySystem::class)->create()->id,
            'destination_factory_system_id' => factory(FactorySystem::class)->create()->id,
            'integration_id' => null
        ]);

        $this
            ->passportAs($this->withoutPermissions)
            ->deleteJson(route('api.v2.admin.service-templates.destroy', $serviceTemplate))
            ->assertForbidden();
    }
}
