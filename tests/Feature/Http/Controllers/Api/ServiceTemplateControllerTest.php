<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Fabric\Company;
use App\Models\Fabric\Entity;
use App\Models\Fabric\Factory;
use App\Models\Fabric\FactorySystem;
use App\Models\Fabric\Integration;
use App\Models\Fabric\ServiceTemplate;
use App\Models\Fabric\System;
use App\Models\Fabric\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\LaravelTestCase;

class ServiceTemplateControllerTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();
        $this->integration = $this->company->integrations()->save(factory(Integration::class)->make());

        $this->secondCompany = factory(Company::class)->create();
        $this->secondIntegration = $this->secondCompany->integrations()->save(factory(Integration::class)->make());

        $this->factory = factory(Factory::class)->create(['name' => 'Test Factory']);
        $this->shopify = factory(System::class)->create(['name' => 'Shopify']);
        $this->peoplevox = factory(System::class)->create(['name' => 'Peoplevox']);
        $this->entity = factory(Entity::class)->create(['name' => 'Test Entity']);
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
        $this->integrationServiceTemplate = factory(ServiceTemplate::class)->create([
            'integration_id' => $this->integration,
            'source_factory_system_id' => $this->shopifyFactorySystem->id,
            'destination_factory_system_id' => $this->peoplevoxFactorySystem->id,
        ]);
        $this->secondIntegrationServiceTemplate = factory(ServiceTemplate::class)->create([
            'integration_id' => $this->secondIntegration,
            'source_factory_system_id' => $this->shopifyFactorySystem->id,
            'destination_factory_system_id' => $this->peoplevoxFactorySystem->id,
        ]);
        $this->withoutPermissions = $this->company->users()->save(factory(User::class)->make());
        $this->withPermissions = $this->company->users()->save(factory(User::class)->states('client user')->make());
    }

    public function test_user_with_permission_can_retrieve_service_templates(): void
    {
        $this
            ->passportAs($this->withPermissions)
            ->getJson(route('api.v2.service-templates.index'))
            ->assertOk();
    }

    public function test_user_without_permission_cannot_retrieve_service_templates(): void
    {
        $this
            ->passportAs($this->withoutPermissions)
            ->getJson(route('api.v2.service-templates.index'))
            ->assertForbidden();
    }

    public function test_user_with_permission_can_retrieve_a_service_template(): void
    {
        $this
            ->passportAs($this->withPermissions)
            ->getJson(route('api.v2.service-templates.show', $this->serviceTemplate))
            ->assertOk()->assertJsonPath('data.id', $this->serviceTemplate->id);
    }

    public function test_user_with_permission_can_retrieve_owned_service_template(): void
    {
        $this
            ->passportAs($this->withPermissions)
            ->getJson(route('api.v2.service-templates.show', $this->integrationServiceTemplate))
            ->assertOk()->assertJsonPath('data.id', $this->integrationServiceTemplate->id);
    }

    public function test_user_with_permission_cannot_retrieve_not_owned_service_template(): void
    {
        $this
            ->passportAs($this->withPermissions)
            ->getJson(route('api.v2.service-templates.show', $this->secondIntegrationServiceTemplate))
            ->assertNotFound();
    }

    public function test_user_without_permission_cannot_retrieve_a_service_template(): void
    {
        $this
            ->passportAs($this->withoutPermissions)
            ->getJson(route('api.v2.service-templates.show', $this->serviceTemplate))
            ->assertForbidden();
    }

    public function test_user_with_permission_can_create_a_service_template(): void
    {
        $attributes = factory(ServiceTemplate::class)->raw([
            'name' => 'Test Template',
            'source_factory_system_id' => factory(FactorySystem::class)->create()->id,
            'destination_factory_system_id' => factory(FactorySystem::class)->create()->id,
            'integration_id' => $this->integration->id,
            'enabled' => 1
        ]);

        $this
            ->passportAs($this->withPermissions)
            ->postJson(route('api.v2.service-templates.store'), $attributes)
            ->assertCreated();

        $this->assertDatabaseHas((new ServiceTemplate())->getTable(), $attributes);
    }

    public function test_user_with_permission_cannot_create_service_template_with_not_owned_integration(): void
    {
        $attributes = factory(ServiceTemplate::class)->raw([
            'name' => 'Test Template',
            'source_factory_system_id' => factory(FactorySystem::class)->create()->id,
            'destination_factory_system_id' => factory(FactorySystem::class)->create()->id,
            'integration_id' => $this->secondIntegration->id
        ]);

        $this
            ->passportAs($this->withPermissions)
            ->postJson(route('api.v2.service-templates.store'), $attributes)
            ->assertForbidden();
    }

    public function test_user_without_permission_cannot_create_a_service_template(): void
    {
        $attributes = factory(ServiceTemplate::class)->raw([
            'name' => 'Test Template',
            'source_factory_system_id' => factory(FactorySystem::class)->create()->id,
            'destination_factory_system_id' => factory(FactorySystem::class)->create()->id,
            'integration_id' => $this->integration->id
        ]);

        $this
            ->passportAs($this->withoutPermissions)
            ->postJson(route('api.v2.service-templates.store'), $attributes)
            ->assertForbidden();
    }
}
