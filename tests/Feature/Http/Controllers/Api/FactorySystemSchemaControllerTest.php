<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Fabric\Integration;
use Tests\LaravelTestCase;
use App\Models\Fabric\User;
use App\Models\Fabric\Entity;
use App\Models\Fabric\Company;
use App\Models\Fabric\Factory;
use App\Models\Fabric\System;
use App\Models\Fabric\FactorySystem;
use App\Models\Fabric\FactorySystemSchema;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FactorySystemSchemaControllerTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->company = factory(Company::class)->create(['name' => 'Test Company']);
        $this->integration = $this->company->integrations()->save(factory(Integration::class)->make());
        $this->secondaryCompany = factory(Company::class)->create(['name' => 'Test Company Secondary']);
        $this->secondaryIntegration = $this->secondaryCompany->integrations()->save(factory(Integration::class)->make());
        $system = factory(System::class)->create();
        $entity = factory(Entity::class)->create();
        $factory = factory(Factory::class)->create();
        $this->withoutPermissions = $this->company->users()->save(factory(User::class)->make());
        $this->clientUser = $this->company->users()->save(factory(User::class)->states('client user')->make());
        $this->secondaryClientUser = $this->secondaryCompany->users()->save(factory(User::class)->states('client user')->make());
        $this->factorySystem = factory(FactorySystem::class)->create([
            'system_id' => $system->getKey(),
            'entity_id' => $entity->getKey(),
            'factory_id' => $factory->getKey(),
            'integration_id' => null
        ]);
        $this->generalFactorySystemSchema = factory(FactorySystemSchema::class)->create([
            'factory_system_id' => $this->factorySystem->id,
            'integration_id' => null
        ]);
        $this->integrationFactorySystemSchema = factory(FactorySystemSchema::class)->create([
            'factory_system_id' => $this->factorySystem->id,
            'integration_id' => $this->integration->id
        ]);
    }

    public function test_user_with_permission_can_retrieve_factory_system_schemas(): void
    {
        $this
            ->passportAs($this->clientUser)
            ->getJson(route('api.v2.factory-system-schemas.index'))
            ->assertOk();
    }

    public function test_user_without_permission_cannot_retrieve_factory_system_schemas(): void
    {
        $this
            ->passportAs($this->withoutPermissions)
            ->getJson(route('api.v2.factory-system-schemas.index'))
            ->assertForbidden();
    }

    public function test_user_with_permission_can_retrieve_a_factory_system_schema(): void
    {
        $this
            ->passportAs($this->clientUser)
            ->getJson(route('api.v2.factory-system-schemas.show', $this->generalFactorySystemSchema))
            ->assertOk();
    }

    public function test_user_with_permission_can_retrieve_a_integration_factory_system_schema(): void
    {
        $this
            ->passportAs($this->clientUser)
            ->getJson(route('api.v2.factory-system-schemas.show', $this->integrationFactorySystemSchema))
            ->assertOk();
    }

    public function test_user_without_permission_cannot_retrieve_a_factory_system_schema(): void
    {
        $this
            ->passportAs($this->withoutPermissions)
            ->getJson(route('api.v2.factory-system-schemas.show', $this->generalFactorySystemSchema))
            ->assertForbidden();
    }

    public function test_user_with_permission_cannot_retrieve_non_owned_factory_system_schema(): void
    {
        $this
            ->passportAs($this->secondaryClientUser)
            ->getJson(route('api.v2.factory-system-schemas.show', $this->integrationFactorySystemSchema))
            ->assertNotFound();
    }

    public function test_user_with_permission_can_create_factory_system_schemas(): void
    {
        $system = factory(System::class)->create(['name' => 'Test System']);
        $entity = factory(Entity::class)->create(['name' => 'Test Entity']);
        $factory = factory(Factory::class)->create(['name' => 'Test Factory']);
        $factorySystem = factory(FactorySystem::class)->create([
            'system_id' => $system->getKey(),
            'entity_id' => $entity->getKey(),
            'factory_id' => $factory->getKey(),
            'integration_id' => null
        ]);
        $attributes = factory(FactorySystemSchema::class)->raw([
            'integration_id' => $this->integration->id,
            'factory_system_id' => $factorySystem->id
        ]);

        $this
            ->passportAs($this->clientUser)
            ->postJson(route('api.v2.factory-system-schemas.store'), $attributes)
            ->assertCreated();

        $this->assertDatabaseHas((new FactorySystemSchema())->getTable(), $attributes);
    }

    public function test_user_without_permission_cannot_create_factory_system_schemas(): void
    {
        $system = factory(System::class)->create(['name' => 'Test System']);
        $entity = factory(Entity::class)->create(['name' => 'Test Entity']);
        $factory = factory(Factory::class)->create(['name' => 'Test Factory']);
        $factorySystem = factory(FactorySystem::class)->create([
            'system_id' => $system->getKey(),
            'entity_id' => $entity->getKey(),
            'factory_id' => $factory->getKey(),
            'integration_id' => null
        ]);
        $attributes = factory(FactorySystemSchema::class)->raw([
            'integration_id' => $this->integration->id,
            'factory_system_id' => $factorySystem->id
        ]);

        $this
            ->passportAs($this->withoutPermissions)
            ->postJson(route('api.v2.factory-system-schemas.store'), $attributes)
            ->assertForbidden();
    }

    public function test_user_with_permission_cannot_create_factory_system_schema_with_not_owned_integration(): void
    {
        $system = factory(System::class)->create(['name' => 'Test System']);
        $entity = factory(Entity::class)->create(['name' => 'Test Entity']);
        $factory = factory(Factory::class)->create(['name' => 'Test Factory']);
        $factorySystem = factory(FactorySystem::class)->create([
            'system_id' => $system->getKey(),
            'entity_id' => $entity->getKey(),
            'factory_id' => $factory->getKey(),
            'integration_id' => null
        ]);
        $attributes = factory(FactorySystemSchema::class)->raw([
            'integration_id' => $this->integration->id,
            'factory_system_id' => $factorySystem->id
        ]);

        $this
            ->passportAs($this->secondaryClientUser)
            ->postJson(route('api.v2.factory-system-schemas.store'), $attributes)
            ->assertForbidden();
    }

    public function test_user_with_permission_can_update_factory_system_schemas(): void
    {
        $attributes = factory(FactorySystemSchema::class)->raw(['schema' => 'Test Schema']);

        $this
            ->passportAs($this->clientUser)
            ->putJson(route('api.v2.factory-system-schemas.update', $this->integrationFactorySystemSchema), $attributes)
            ->assertOk();

        $this->assertSame($attributes['schema'], $this->integrationFactorySystemSchema->fresh()->schema);
    }

    public function test_user_without_permission_cannot_update_factory_system_schemas(): void
    {
        $attributes = factory(FactorySystemSchema::class)->raw(['schema' => 'Test Schema']);

        $this
            ->passportAs($this->withoutPermissions)
            ->putJson(route('api.v2.factory-system-schemas.update', $this->integrationFactorySystemSchema), $attributes)
            ->assertForbidden();
    }

    public function test_user_with_different_company_cannot_update_factory_system_schemas(): void
    {
        $attributes = factory(FactorySystemSchema::class)->raw(['schema' => 'Test Schema']);

        $this
            ->passportAs($this->secondaryClientUser)
            ->putJson(route('api.v2.factory-system-schemas.update', $this->integrationFactorySystemSchema), $attributes)
            ->assertNotFound();
    }

    public function test_user_with_permission_cannot_update_global_factory_schemas(): void
    {
        $attributes = factory(FactorySystemSchema::class)->raw(['schema' => 'Test Schema']);

        $this
            ->passportAs($this->clientUser)
            ->putJson(route('api.v2.factory-system-schemas.update', $this->generalFactorySystemSchema), $attributes)
            ->assertForbidden();
    }

    public function test_user_with_permission_can_delete_a_factory_system_schema(): void
    {
        $this
            ->passportAs($this->clientUser)
            ->deleteJson(route('api.v2.factory-system-schemas.destroy', $this->integrationFactorySystemSchema))
            ->assertOk()
            ->assertJsonPath('message', 'Factory system schema deleted successfully.');

        $this->assertDatabaseMissing($this->integrationFactorySystemSchema->getTable(), $this->integrationFactorySystemSchema->only('id'));
    }

    public function test_user_without_permission_cannot_delete_a_factory_system_schema(): void
    {
        $this
            ->passportAs($this->withoutPermissions)
            ->deleteJson(route('api.v2.factory-system-schemas.destroy', $this->integrationFactorySystemSchema))
            ->assertForbidden();
    }

    public function test_user_with_different_company_cannot_delete_a_factory_system_schema(): void
    {
        $this
            ->passportAs($this->secondaryClientUser)
            ->deleteJson(route('api.v2.factory-system-schemas.destroy', $this->integrationFactorySystemSchema))
            ->assertNotFound();
    }

    public function test_user_with_permission_cannot_delete_a_global_factory_schema(): void
    {
        $this
            ->passportAs($this->clientUser)
            ->deleteJson(route('api.v2.factory-system-schemas.destroy', $this->generalFactorySystemSchema))
            ->assertForbidden();
    }
}
