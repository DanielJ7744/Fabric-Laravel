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

class DefaultPayloadControllerTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();
        $this->withoutPermissions = $this->company->users()->save(factory(User::class)->make());
        $this->withPermissions = $this->company->users()->save(factory(User::class)->states('client user')->make());

        $system = factory(System::class)->create();
        $entity = factory(Entity::class)->create();
        $factory = factory(Factory::class)->create();
        $factorySystem = factory(FactorySystem::class)->create([
            'system_id' => $system->getKey(),
            'entity_id' => $entity->getKey(),
            'factory_id' => $factory->getKey(),
            'integration_id' => null
        ]);
        $factorySystemSchema = factory(FactorySystemSchema::class)->create([
            'factory_system_id' => $factorySystem->id,
            'integration_id' => null
        ]);
        $this->defaultPayload = factory(DefaultPayload::class)->create(['factory_system_schema_id' => $factorySystemSchema->id]);
    }

    public function test_user_with_permission_can_retrieve_default_payloads(): void
    {
        $this
            ->passportAs($this->withPermissions)
            ->getJson(route('api.v2.default-payloads.index'))
            ->assertOk();
    }

    public function test_user_without_permission_cannot_retrieve_default_payloads(): void
    {
        $this
            ->passportAs($this->withoutPermissions)
            ->getJson(route('api.v2.default-payloads.index'))
            ->assertForbidden();
    }

    public function test_user_with_permission_can_retrieve_a_default_payload(): void
    {
        $this
            ->passportAs($this->withPermissions)
            ->getJson(route('api.v2.default-payloads.show', $this->defaultPayload))
            ->assertOk()
            ->assertJsonPath('data.id', $this->defaultPayload->id);
    }

    public function test_user_without_permission_cannot_retrieve_a_default_payload(): void
    {
        $this
            ->passportAs($this->withoutPermissions)
            ->getJson(route('api.v2.default-payloads.show', $this->defaultPayload))
            ->assertForbidden();
    }
}
