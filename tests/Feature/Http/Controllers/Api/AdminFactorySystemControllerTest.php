<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Fabric\Company;
use App\Models\Fabric\Entity;
use App\Models\Fabric\Factory;
use App\Models\Fabric\FactorySystem;
use App\Models\Fabric\System;
use App\Models\Fabric\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\LaravelTestCase;

/**
 * @group company
 */
class AdminFactorySystemControllerTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();
        $this->factory = factory(Factory::class)->create();
        $this->system = factory(System::class)->create();
        $this->entity = factory(Entity::class)->create();
        $this->factorySystem = factory(FactorySystem::class)->create([
            'factory_id' => $this->factory->id,
            'system_id' => $this->system->id,
            'entity_id' => $this->entity->id
        ]);
        $this->withoutPermissions = $this->company->users()->save(factory(User::class)->states('client user')->make());
        $this->withPermissions = $this->company->users()->save(factory(User::class)->states('patchworks admin')->make());
    }

    public function test_user_with_permission_can_create_factory_systems(): void
    {
        $testFactory = factory(Factory::class)->create([
            'name' => 'Test'
        ]);
        $attributes = factory(FactorySystem::class)->raw([
            'factory_id' => $testFactory->id,
            'system_id' => $this->system->id,
            'entity_id' => $this->entity->id
        ]);

        $this
            ->passportAs($this->withPermissions)
            ->postJson(route('api.v2.admin.factory-systems.store'), $attributes)
            ->assertCreated();

        $this->assertDatabaseHas((new FactorySystem())->getTable(), $attributes);
    }

    public function test_user_without_permission_cannot_create_factory_systems(): void
    {
        $testFactory = factory(Factory::class)->create([
            'name' => 'Test'
        ]);
        $attributes = factory(FactorySystem::class)->raw([
            'factory_id' => $testFactory->id,
            'system_id' => $this->system->id,
            'entity_id' => $this->entity->id
        ]);

        $this
            ->passportAs($this->withoutPermissions)
            ->postJson(route('api.v2.admin.factory-systems.store'), $attributes)
            ->assertForbidden();
    }

    public function test_user_with_permission_can_update_factory_system(): void
    {
        $testFactory = factory(Factory::class)->create([
            'name' => 'Test'
        ]);
        $factorySystem = factory(FactorySystem::class)->create([
            'factory_id' => $testFactory->id,
            'system_id' => $this->system->id,
            'entity_id' => $this->entity->id
        ]);
        $attributes = factory(FactorySystem::class)->raw(['direction' => 'push']);

        $this
            ->passportAs($this->withPermissions)
            ->putJson(route('api.v2.admin.factory-systems.update', $factorySystem), $attributes)
            ->assertOk();

        $this->assertSame($attributes['direction'], $factorySystem->fresh()->direction);
    }

    public function test_user_without_permission_cannot_update_factory_system(): void
    {
        $testFactory = factory(Factory::class)->create([
            'name' => 'Test'
        ]);
        $factorySystem = factory(FactorySystem::class)->create([
            'factory_id' => $testFactory->id,
            'system_id' => $this->system->id,
            'entity_id' => $this->entity->id
        ]);
        $attributes = factory(FactorySystem::class)->raw(['direction' => 'push']);

        $this
            ->passportAs($this->withoutPermissions)
            ->putJson(route('api.v2.admin.factory-systems.update', $factorySystem), $attributes)
            ->assertForbidden();
    }

    public function test_user_with_permission_can_delete_a_factory_system(): void
    {
        $this
            ->passportAs($this->withPermissions)
            ->deleteJson(route('api.v2.admin.factory-systems.destroy', $this->factorySystem))
            ->assertOk()
            ->assertJsonPath('message', 'Factory system deleted successfully.');

        $this->assertDatabaseMissing($this->factorySystem->getTable(), $this->factorySystem->only('id'));
    }

    public function test_user_without_permission_cannot_delete_a_factory_system(): void
    {
        $this
            ->passportAs($this->withoutPermissions)
            ->deleteJson(route('api.v2.admin.factory-systems.destroy', $this->factorySystem))
            ->assertForbidden();
    }
}
