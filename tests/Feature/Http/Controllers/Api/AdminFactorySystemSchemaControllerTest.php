<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Fabric\Company;
use App\Models\Fabric\Entity;
use App\Models\Fabric\Factory;
use App\Models\Fabric\FactorySystem;
use App\Models\Fabric\FactorySystemSchema;
use App\Models\Fabric\System;
use App\Models\Fabric\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\LaravelTestCase;

class AdminFactorySystemSchemaControllerTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();
        $this->withoutPermissions = $this->company->users()->save(factory(User::class)->states('client user')->make());
        $this->withPermissions = $this->company->users()->save(factory(User::class)->states('patchworks admin')->make());
        $system = factory(System::class)->create();
        $entity = factory(Entity::class)->create();
        $factory = factory(Factory::class)->create();
        $this->factorySystem = factory(FactorySystem::class)->create([
            'system_id' => $system->getKey(),
            'entity_id' => $entity->getKey(),
            'factory_id' => $factory->getKey(),
        ]);
        $this->factorySystemSchema = factory(FactorySystemSchema::class)->create([
            'factory_system_id' => $this->factorySystem->id,
            'integration_id' => null
        ]);
    }

    public function test_user_with_permission_can_retrieve_factory_system_schemas(): void
    {
        $this
            ->passportAs($this->withPermissions)
            ->getJson(route('api.v2.admin.factory-system-schemas.index'))
            ->assertOk();
    }

    public function test_user_without_permission_cannot_retrieve_factory_system_schemas(): void
    {
        $this
            ->passportAs($this->withoutPermissions)
            ->getJson(route('api.v2.admin.factory-system-schemas.index'))
            ->assertForbidden();
    }

    public function test_user_with_permission_can_retrieve_a_factory_system_schema(): void
    {
        $this
            ->passportAs($this->withPermissions)
            ->getJson(route('api.v2.admin.factory-system-schemas.show', $this->factorySystemSchema))
            ->assertOk();
    }

    public function test_user_without_permission_cannot_retrieve_a_factory_system_schema(): void
    {
        $this
            ->passportAs($this->withoutPermissions)
            ->getJson(route('api.v2.admin.factory-system-schemas.show', $this->factorySystemSchema))
            ->assertForbidden();
    }

    public function test_user_with_permission_can_create_factory_system_schemas(): void
    {
        $attributes = factory(FactorySystemSchema::class)->raw([
            'integration_id' => null,
            'factory_system_id' => $this->factorySystem->id
        ]);

        $this
            ->passportAs($this->withPermissions)
            ->postJson(route('api.v2.admin.factory-system-schemas.store'), $attributes)
            ->assertCreated();

        $this->assertDatabaseHas((new FactorySystemSchema())->getTable(), $attributes);
    }

    public function test_user_without_permission_cannot_create_factory_system_schemas(): void
    {
        $attributes = factory(FactorySystemSchema::class)->raw([
            'integration_id' => null,
            'factory_system_id' => $this->factorySystem->id
        ]);

        $this
            ->passportAs($this->withoutPermissions)
            ->postJson(route('api.v2.admin.factory-system-schemas.store'), $attributes)
            ->assertForbidden();
    }

    public function test_user_with_permission_can_update_factory_system_schemas(): void
    {
        $attributes = factory(FactorySystemSchema::class)->raw(['schema' => 'Test Schema']);

        $this
            ->passportAs($this->withPermissions)
            ->putJson(route('api.v2.admin.factory-system-schemas.update', $this->factorySystemSchema), $attributes)
            ->assertOk();

        $this->assertSame($attributes['schema'], $this->factorySystemSchema->fresh()->schema);
    }

    public function test_user_without_permission_cannot_update_factory_system_schemas(): void
    {
        $attributes = factory(FactorySystemSchema::class)->raw(['schema' => 'Test Schema']);

        $this
            ->passportAs($this->withoutPermissions)
            ->putJson(route('api.v2.admin.factory-system-schemas.update', $this->factorySystemSchema), $attributes)
            ->assertForbidden();
    }

    public function test_user_with_permission_can_delete_a_factory_system_schema(): void
    {
        $this
            ->passportAs($this->withPermissions)
            ->deleteJson(route('api.v2.admin.factory-system-schemas.destroy', $this->factorySystemSchema))
            ->assertOk()
            ->assertJsonPath('message', 'Factory system schema deleted successfully.');

        $this->assertDatabaseMissing($this->factorySystemSchema->getTable(), $this->factorySystemSchema->only('id'));
    }

    public function test_user_without_permission_cannot_delete_a_factory_system_schema(): void
    {
        $this
            ->passportAs($this->withoutPermissions)
            ->deleteJson(route('api.v2.admin.factory-system-schemas.destroy', $this->factorySystemSchema))
            ->assertForbidden();
    }
}
