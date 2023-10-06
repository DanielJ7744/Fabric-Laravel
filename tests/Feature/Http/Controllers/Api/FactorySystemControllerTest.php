<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Fabric\Company;
use App\Models\Fabric\Entity;
use App\Models\Fabric\Factory;
use App\Models\Fabric\FactorySystem;
use App\Models\Fabric\Integration;
use App\Models\Fabric\System;
use App\Models\Fabric\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\LaravelTestCase;

class FactorySystemControllerTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();
        $this->integration = $this->company->integrations()->save(factory(Integration::class)->make());
        $this->secondaryCompany = factory(Company::class)->create(['name' => 'Test Company Secondary']);
        $this->secondaryIntegration = $this->secondaryCompany->integrations()->save(factory(Integration::class)->make());
        $this->system = factory(System::class)->create();
        $this->entity = factory(Entity::class)->create(['integration_id' => null]);
        $this->factory = factory(Factory::class)->create();
        $this->withoutPermissions = $this->company->users()->save(factory(User::class)->make());
        $this->withPermissions = $this->company->users()->save(factory(User::class)->states('client user')->make());
        $this->factorySystem = factory(FactorySystem::class)->create([
            'system_id' => $this->system->getKey(),
            'entity_id' => $this->entity->getKey(),
            'factory_id' => $this->factory->getKey(),
            'integration_id' => null
        ]);
        $this->integrationFactorySystem = factory(FactorySystem::class)->create([
            'system_id' => $this->system->getKey(),
            'entity_id' => $this->entity->getKey(),
            'factory_id' => $this->factory->getKey(),
            'integration_id' => $this->integration->getKey()
        ]);
        $this->secondaryFactorySystem = factory(FactorySystem::class)->create([
            'system_id' => $this->system->getKey(),
            'entity_id' => $this->entity->getKey(),
            'factory_id' => $this->factory->getKey(),
            'integration_id' => $this->secondaryIntegration->getKey()
        ]);
    }

    public function test_user_with_permission_can_retrieve_factory_systems(): void
    {
        $this
            ->passportAs($this->withPermissions)
            ->getJson(route('api.v2.factory-systems.index'))
            ->assertOk();
    }

    public function test_user_without_permission_cannot_retrieve_factory_systems(): void
    {
        $this
            ->passportAs($this->withoutPermissions)
            ->getJson(route('api.v2.factory-systems.index'))
            ->assertForbidden();
    }

    public function test_user_with_permission_can_retrieve_a_factory_system(): void
    {
        $this
            ->passportAs($this->withPermissions)
            ->getJson(route('api.v2.factory-systems.show', $this->factorySystem))
            ->assertOk()
            ->assertJsonPath('data.id', $this->factorySystem->id);
    }

    public function test_user_with_permission_cannot_retrieve_factory_system_owned_by_other_integration(): void
    {
        $this
            ->passportAs($this->withPermissions)
            ->getJson(route('api.v2.factory-systems.show', $this->secondaryFactorySystem))
            ->assertNotFound();
    }

    public function test_user_without_permission_cannot_retrieve_a_factory_system(): void
    {
        $this
            ->passportAs($this->withoutPermissions)
            ->getJson(route('api.v2.factory-systems.show', $this->factorySystem))
            ->assertForbidden();
    }

    public function test_user_with_permission_can_create_factory_systems_using_global_entity(): void
    {
        $system = factory(System::class)->create(['name' => 'Test System']);
        $factory = factory(Factory::class)->create(['name' => 'Test Factory']);
        $attributes = factory(FactorySystem::class)->raw([
            'factory_id' => $factory->id,
            'system_id' => $system->id,
            'entity_id' => $this->entity->id,
            'direction' => 'pull',
            'integration_id' => $this->integration->id
        ]);

        $this
            ->passportAs($this->withPermissions)
            ->postJson(route('api.v2.factory-systems.store'), $attributes)
            ->assertCreated();
    }

    public function test_user_with_permission_cannot_create_factory_system_without_integration_id(): void
    {
        $system = factory(System::class)->create(['name' => 'Test System']);
        $factory = factory(Factory::class)->create(['name' => 'Test Factory']);
        $attributes = factory(FactorySystem::class)->raw([
            'factory_id' => $factory->id,
            'system_id' => $system->id,
            'entity_id' => $this->entity->id,
            'direction' => 'pull',
            'integration_id' => null
        ]);

        $this
            ->passportAs($this->withPermissions)
            ->postJson(route('api.v2.factory-systems.store'), $attributes)
            ->assertJsonValidationErrors([
                'integration_id' => 'The integration id field is required.'
            ]);
    }

    public function test_user_with_permission_cannot_create_factory_system_using_not_owned_integration(): void
    {
        $system = factory(System::class)->create(['name' => 'Test System']);
        $factory = factory(Factory::class)->create(['name' => 'Test Factory']);
        $attributes = factory(FactorySystem::class)->raw([
            'factory_id' => $factory->id,
            'system_id' => $system->id,
            'entity_id' => $this->entity->id,
            'direction' => 'pull',
            'integration_id' => $this->secondaryIntegration->id
        ]);

        $this
            ->passportAs($this->withPermissions)
            ->postJson(route('api.v2.factory-systems.store'), $attributes)
            ->assertForbidden();
    }

    public function test_user_without_permission_cannot_create_factory_system(): void
    {
        $system = factory(System::class)->create(['name' => 'Test System']);
        $factory = factory(Factory::class)->create(['name' => 'Test Factory']);
        $attributes = factory(FactorySystem::class)->raw([
            'factory_id' => $factory->id,
            'system_id' => $system->id,
            'entity_id' => $this->entity->id,
            'direction' => 'pull',
            'integration_id' => $this->integration->id
        ]);

        $this
            ->passportAs($this->withoutPermissions)
            ->postJson(route('api.v2.factory-systems.store'), $attributes)
            ->assertForbidden();
    }
}
