<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Fabric\Company;
use App\Models\Fabric\DefaultPayload;
use App\Models\Fabric\Entity;
use App\Models\Fabric\Factory;
use App\Models\Fabric\FactorySystem;
use App\Models\Fabric\FactorySystemSchema;
use App\Models\Fabric\System;
use App\Models\Fabric\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\LaravelTestCase;

class AdminDefaultPayloadControllerTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();
        $this->withoutPermissions = $this->company->users()->save(factory(User::class)->states('patchworks user')->make());
        $this->withPermissions = $this->company->users()->save(factory(User::class)->states('patchworks admin')->make());

        $system = factory(System::class)->create();
        $entity = factory(Entity::class)->create();
        $factory = factory(Factory::class)->create();
        $factorySystem = factory(FactorySystem::class)->create([
            'system_id' => $system->getKey(),
            'entity_id' => $entity->getKey(),
            'factory_id' => $factory->getKey(),
            'integration_id' => null
        ]);
        $this->factorySystemSchema = factory(FactorySystemSchema::class)->create([
            'factory_system_id' => $factorySystem->id,
            'integration_id' => null
        ]);
    }

    public function test_user_with_permission_can_create_a_default_payload(): void
    {
        $attributes = factory(DefaultPayload::class)->raw([
            'factory_system_schema_id' => $this->factorySystemSchema->id,
        ]);

        $this
            ->passportAs($this->withPermissions)
            ->postJson(route('api.v2.admin.default-payloads.store'), $attributes)
            ->assertCreated();

        $this->assertDatabaseHas((new DefaultPayload())->getTable(), $attributes);
    }

    public function test_user_without_permission_cannot_create_a_default_payload(): void
    {
        $attributes = factory(DefaultPayload::class)->raw([
            'factory_system_schema_id' => $this->factorySystemSchema->id,
        ]);

        $this
            ->passportAs($this->withoutPermissions)
            ->postJson(route('api.v2.admin.default-payloads.store'), $attributes)
            ->assertForbidden();
    }

    public function test_user_with_permission_can_update_a_default_payload(): void
    {
        $defaultPayload = factory(DefaultPayload::class)->create([
            'factory_system_schema_id' => $this->factorySystemSchema->id
        ]);

        $attributes = factory(DefaultPayload::class)->raw([
            'payload' => json_encode(['test' => 'test']),
        ]);

        $this
            ->passportAs($this->withPermissions)
            ->putJson(route('api.v2.admin.default-payloads.update', $defaultPayload), $attributes)
            ->assertOk();

        $this->assertSame($attributes['payload'], $defaultPayload->fresh()->payload);
    }

    public function test_user_without_permission_cannot_update_a_default_payload(): void
    {
        $defaultPayload = factory(DefaultPayload::class)->create([
            'factory_system_schema_id' => $this->factorySystemSchema->id
        ]);

        $attributes = factory(DefaultPayload::class)->raw([
            'payload' => json_encode(['test' => 'test']),
        ]);

        $this
            ->passportAs($this->withoutPermissions)
            ->putJson(route('api.v2.admin.default-payloads.update', $defaultPayload), $attributes)
            ->assertForbidden();
    }

    public function test_user_with_permission_can_delete_a_default_payload(): void
    {
        $defaultPayload = factory(DefaultPayload::class)->create([
            'factory_system_schema_id' => $this->factorySystemSchema->id
        ]);

        $this
            ->passportAs($this->withPermissions)
            ->deleteJson(route('api.v2.admin.default-payloads.destroy', $defaultPayload))
            ->assertOk()
            ->assertJsonPath('message', 'Default payload deleted successfully.');

        $this->assertDatabaseMissing($defaultPayload->getTable(), $defaultPayload->only('id'));
    }

    public function test_user_without_permission_cannot_delete_a_default_payload(): void
    {
        $defaultPayload = factory(DefaultPayload::class)->create([
            'factory_system_schema_id' => $this->factorySystemSchema->id
        ]);

        $this
            ->passportAs($this->withoutPermissions)
            ->deleteJson(route('api.v2.admin.default-payloads.destroy', $defaultPayload))
            ->assertForbidden();
    }
}
