<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Fabric\Company;
use App\Models\Fabric\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\LaravelTestCase;

class AdminRoleControllerTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();
        $this->withoutPermissions = $this->company->users()->save(factory(User::class)->states('client user')->make());
        $this->withPermissions = $this->company->users()->save(factory(User::class)->states('patchworks admin')->make());
    }

    public function test_user_with_permission_can_create_roles(): void
    {
        $attributes = factory(Role::class)->raw([
            'name' => 'test role',
            'guard_name' => 'web',
            'patchworks_role' => false
        ]);

        $this
            ->passportAs($this->withPermissions)
            ->postJson(route('api.v2.admin.roles.store'), $attributes)
            ->assertCreated();

        $this->assertDatabaseHas((new Role())->getTable(), $attributes);
    }

    public function test_user_without_permission_cannot_create_roles(): void
    {
        $attributes = factory(Role::class)->raw([
            'name' => 'test role',
            'guard_name' => 'web',
            'patchworks_role' => false
        ]);

        $this
            ->passportAs($this->withoutPermissions)
            ->postJson(route('api.v2.admin.roles.store'), $attributes)
            ->assertForbidden();
    }

    public function test_user_with_permission_can_update_roles(): void
    {
        $role = factory(Role::class)->create(['name' => 'test role', 'patchworks_role' => false]);
        $attributes = factory(Role::class)->raw(['name' => 'updated role']);

        $this
            ->passportAs($this->withPermissions)
            ->putJson(route('api.v2.admin.roles.update', $role), $attributes)
            ->assertOk();

        $this->assertSame($attributes['name'], $role->fresh()->name);
    }

    public function test_user_without_permission_cannot_update_roles(): void
    {
        $role = factory(Role::class)->create(['name' => 'test role', 'patchworks_role' => false]);
        $attributes = factory(Role::class)->raw(['name' => 'updated role']);

        $this
            ->passportAs($this->withoutPermissions)
            ->putJson(route('api.v2.admin.roles.update', $role), $attributes)
            ->assertForbidden();
    }

    public function test_user_with_permission_can_delete_a_role(): void
    {
        $role = factory(Role::class)->create(['name' => 'test role', 'patchworks_role' => false]);

        $this
            ->passportAs($this->withPermissions)
            ->deleteJson(route('api.v2.admin.roles.destroy', $role))
            ->assertOk()
            ->assertJsonPath('message', 'Role deleted successfully.');

        $this->assertDatabaseMissing($role->getTable(), $role->only('id'));
    }

    public function test_user_without_permission_cannot_delete_a_role(): void
    {
        $role = factory(Role::class)->create(['name' => 'test role', 'patchworks_role' => false]);

        $this
            ->passportAs($this->withoutPermissions)
            ->deleteJson(route('api.v2.admin.roles.destroy', $role))
            ->assertForbidden();
    }
}
