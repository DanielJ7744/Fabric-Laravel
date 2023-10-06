<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Fabric\Company;
use App\Models\Fabric\Entity;
use App\Models\Fabric\Factory;
use App\Models\Fabric\FactorySystem;
use App\Models\Fabric\FilterField;
use App\Models\Fabric\System;
use App\Models\Fabric\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\LaravelTestCase;

class AdminFilterFieldControllerTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();
        $this->withoutPermissions = $this->company->users()->save(factory(User::class)->states('patchworks user')->make());
        $this->withPermissions = $this->company->users()->save(factory(User::class)->states('patchworks admin')->make());

        $this->system = factory(System::class)->create();
        $this->entity = factory(Entity::class)->create();
        $this->factory = factory(Factory::class)->create();
        $this->factorySystem = factory(FactorySystem::class)->create(['system_id' => $this->system->getKey(), 'entity_id' => $this->entity->getKey(), 'factory_id' => $this->factory->getKey()]);
        $this->filterField = $this->factorySystem->filterField()->save(factory(FilterField::class)->make());
    }

    public function test_user_with_permission_can_create_filter_fields(): void
    {
        $attributes = $this->factorySystem->filterField()->make(factory(FilterField::class)->raw())->toArray();

        $this
            ->passportAs($this->withPermissions)
            ->postJson(route('api.v2.admin.filter-fields.store'), $attributes)
            ->assertCreated();

        $this->assertDatabaseHas((new FilterField())->getTable(), $attributes);
    }

    public function test_user_without_permission_cannot_create_filter_fields(): void
    {
        $attributes = $this->factorySystem->filterField()->make(factory(FilterField::class)->raw())->toArray();

        $this
            ->passportAs($this->withoutPermissions)
            ->postJson(route('api.v2.admin.filter-fields.store'), $attributes)
            ->assertForbidden();
    }

    public function test_user_with_permission_can_update_filter_fields(): void
    {
        $filterField = $this->factorySystem->filterField()->save(factory(FilterField::class)->make());
        $attributes = factory(FilterField::class)->raw(['name' => 'Updated Name']);

        $this
            ->passportAs($this->withPermissions)
            ->putJson(route('api.v2.admin.filter-fields.update', $filterField), $attributes)
            ->assertOk();

        $this->assertSame($attributes['name'], $filterField->fresh()->name);
    }

    public function test_user_without_permission_cannot_update_filter_fields(): void
    {
        $filterField = $this->factorySystem->filterField()->save(factory(FilterField::class)->make());
        $attributes = factory(FilterField::class)->raw(['name' => 'Updated Name']);

        $this
            ->passportAs($this->withoutPermissions)
            ->putJson(route('api.v2.admin.filter-fields.update', $filterField), $attributes)
            ->assertForbidden();
    }

    public function test_user_with_permission_can_delete_a_filter_field(): void
    {
        $this
            ->passportAs($this->withPermissions)
            ->delete(route('api.v2.admin.filter-fields.destroy', $this->filterField))
            ->assertOk()
            ->assertJsonPath('message', 'Filter field deleted successfully.');

        $this->assertDatabaseMissing($this->filterField->getTable(), $this->filterField->only('id'));
    }

    public function test_user_without_permission_cannot_delete_a_filter_field(): void
    {
        $this
            ->passportAs($this->withoutPermissions)
            ->delete(route('api.v2.admin.filter-fields.destroy', $this->filterField))
            ->assertForbidden();
    }
}
