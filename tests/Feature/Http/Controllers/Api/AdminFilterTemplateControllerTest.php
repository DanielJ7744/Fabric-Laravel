<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Fabric\Company;
use App\Models\Fabric\Entity;
use App\Models\Fabric\Factory;
use App\Models\Fabric\FactorySystem;
use App\Models\Fabric\FilterTemplate;
use App\Models\Fabric\System;
use App\Models\Fabric\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\LaravelTestCase;

class AdminFilterTemplateControllerTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();
        $this->withoutPermissions = $this->company->users()->save(factory(User::class)->make());
        $this->withPermissions = $this->company->users()->save(factory(User::class)->states('patchworks admin')->make());

        $this->system = factory(System::class)->create();
        $this->entity = factory(Entity::class)->create();
        $this->factory = factory(Factory::class)->create();
        $this->factorySystem = factory(FactorySystem::class)->create(['system_id' => $this->system->getKey(),  'entity_id' => $this->entity->getKey(),  'factory_id' => $this->factory->getKey()]);
        $this->filterTemplate = $this->factorySystem->filterTemplate()->save(factory(FilterTemplate::class)->make());
    }

    public function test_user_with_permission_can_create_filter_templates(): void
    {
        $attributes = $this->factorySystem->filterTemplate()->make(factory(FilterTemplate::class)->raw())->toArray();

        $this
            ->passportAs($this->withPermissions)
            ->postJson(route('api.v2.admin.filter-templates.store'), $attributes)
            ->assertCreated();

        $this->assertDatabaseHas((new FilterTemplate())->getTable(), $attributes);
    }

    public function test_user_without_permission_cannot_create_filter_templates(): void
    {
        $attributes = $this->factorySystem->filterTemplate()->make(factory(FilterTemplate::class)->raw())->toArray();

        $this
            ->passportAs($this->withoutPermissions)
            ->postJson(route('api.v2.admin.filter-templates.store'), $attributes)
            ->assertForbidden();
    }

    public function test_user_with_permission_can_update_filter_templates(): void
    {
        $filterTemplate = $this->factorySystem->filterTemplate()->save(factory(FilterTemplate::class)->make());
        $attributes = factory(FilterTemplate::class)->raw(['name' => 'Updated Name']);

        $this
            ->passportAs($this->withPermissions)
            ->putJson(route('api.v2.admin.filter-templates.update', $filterTemplate), $attributes)
            ->assertOk();

        $this->assertSame($attributes['name'], $filterTemplate->fresh()->name);
    }

    public function test_user_without_permission_cannot_update_filter_templates(): void
    {
        $filterTemplate = $this->factorySystem->filterTemplate()->save(factory(FilterTemplate::class)->make());
        $attributes = factory(FilterTemplate::class)->raw(['name' => 'Updated Name']);

        $this
            ->passportAs($this->withoutPermissions)
            ->putJson(route('api.v2.admin.filter-templates.update', $filterTemplate), $attributes)
            ->assertForbidden();
    }

    public function test_user_with_permission_can_delete_a_filter_template(): void
    {
        $this
            ->passportAs($this->withPermissions)
            ->delete(route('api.v2.admin.filter-templates.destroy', $this->filterTemplate))
            ->assertOk()
            ->assertJsonPath('message', 'Filter template deleted successfully.');

        $this->assertDatabaseMissing($this->filterTemplate->getTable(), $this->filterTemplate->only('id'));
    }

    public function test_user_without_permission_cannot_delete_a_filter_template(): void
    {
        $this
            ->passportAs($this->withoutPermissions)
            ->delete(route('api.v2.admin.filter-templates.destroy', $this->filterTemplate))
            ->assertForbidden();
    }
}
