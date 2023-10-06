<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Fabric\Company;
use App\Models\Fabric\Entity;
use App\Models\Fabric\Integration;
use App\Models\Fabric\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\LaravelTestCase;

class AdminEntityControllerTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();
        $this->withPermission = $this->company->users()->save(factory(User::class)->states('patchworks admin')->make());
        $this->withoutPermissions = $this->company->users()->save(factory(User::class)->make());
        $this->entity = factory(Entity::class)->create(['name' => 'Global Entity', 'integration_id' => null]);
    }

    public function test_users_with_permissions_can_retrieve_entities(): void
    {
        $this
            ->passportAs($this->withPermission)
            ->getJson(route('api.v2.admin.entities.index'))
            ->assertOk();
    }

    public function test_user_without_permission_cannot_retrieve_entities(): void
    {
        $this
            ->passportAs($this->withoutPermissions)
            ->getJson(route('api.v2.admin.entities.index'))
            ->assertForbidden();
    }

    public function test_users_with_permissions_can_retrieve_an_entity(): void
    {
        $this
            ->passportAs($this->withPermission)
            ->getJson(route('api.v2.admin.entities.show', $this->entity))
            ->assertOk();
    }

    public function test_user_without_permission_cannot_retrieve_an_entity(): void
    {
        $this
            ->passportAs($this->withoutPermissions)
            ->getJson(route('api.v2.admin.entities.show', $this->entity))
            ->assertForbidden();
    }

    public function test_user_with_permission_can_create_entities(): void
    {
        $attributes = factory(Entity::class)->raw([
            'name' => 'Test Entity',
            'integration_id' => null
        ]);

        $this->passportAs($this->withPermission)
            ->postJson(route('api.v2.admin.entities.store'), $attributes)
            ->assertCreated();
    }

    public function test_user_without_permission_cannot_create_entities(): void
    {
        $attributes = factory(Entity::class)->raw([
            'name' => 'Test Entity',
            'integration_id' => null
        ]);

        $this->passportAs($this->withoutPermissions)
            ->postJson(route('api.v2.admin.entities.store'), $attributes)
            ->assertForbidden();
    }

    public function test_user_with_permission_can_update_an_entity(): void
    {
        $attributes = factory(Entity::class)->raw([
            'name' => 'Updated Entity'
        ]);

        $this
            ->passportAs($this->withPermission)
            ->putJson(route('api.v2.admin.entities.update', $this->entity), $attributes)
            ->assertOk();

        $this->assertSame($attributes['name'], $this->entity->fresh()->name);
    }

    public function test_user_without_permission_cannot_update_an_entity(): void
    {
        $attributes = factory(Entity::class)->raw([
            'name' => 'Updated Entity'
        ]);

        $this
            ->passportAs($this->withoutPermissions)
            ->putJson(route('api.v2.admin.entities.update', $this->entity), $attributes)
            ->assertForbidden();
    }

    public function test_user_with_permission_can_delete_entities(): void
    {
        $this
            ->passportAs($this->withPermission)
            ->deleteJson(route('api.v2.admin.entities.destroy', $this->entity))
            ->assertOk()
            ->assertJsonPath('message', 'Entity deleted successfully.');

        $this->assertDatabaseMissing($this->entity->getTable(), $this->entity->only('id'));
    }

    public function test_user_without_permission_cannot_delete_entities(): void
    {
        $this
            ->passportAs($this->withoutPermissions)
            ->deleteJson(route('api.v2.admin.entities.destroy', $this->entity))
            ->assertForbidden();
    }
}
