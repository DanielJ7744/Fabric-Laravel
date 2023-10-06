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

class FilterFieldControllerTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();
        $this->withoutPermissions = $this->company->users()->save(factory(User::class)->make());
        $this->withPermissions = $this->company->users()->save(factory(User::class)->states('client user')->make());

        $this->system = factory(System::class)->create();
        $this->entity = factory(Entity::class)->create();
        $this->factory = factory(Factory::class)->create();
        $this->factorySystem = factory(FactorySystem::class)->create(['system_id' => $this->system->getKey(),  'entity_id' => $this->entity->getKey(),  'factory_id' => $this->factory->getKey()]);
        $this->filterField = $this->factorySystem->filterField()->save(factory(FilterField::class)->make());
    }

    public function test_user_with_permission_can_retrieve_filter_fields(): void
    {
        $this
            ->passportAs($this->withPermissions)
            ->getJson(route('api.v2.filter-fields.index'))
            ->assertOk();
    }

    public function test_user_without_permission_cannot_retrieve_filter_fields(): void
    {
        $this
            ->passportAs($this->withoutPermissions)
            ->getJson(route('api.v2.filter-fields.index'))
            ->assertForbidden();
    }

    public function test_user_with_permission_can_retrieve_a_filter_field(): void
    {
        $this
            ->passportAs($this->withPermissions)
            ->getJson(route('api.v2.filter-fields.show', $this->filterField))
            ->assertOk()
            ->assertJsonPath('data.id', $this->filterField->id);
    }

    public function test_user_without_permission_cannot_retrieve_a_filter_field(): void
    {
        $this
            ->passportAs($this->withoutPermissions)
            ->getJson(route('api.v2.filter-fields.show', $this->filterField))
            ->assertForbidden();
    }
}
