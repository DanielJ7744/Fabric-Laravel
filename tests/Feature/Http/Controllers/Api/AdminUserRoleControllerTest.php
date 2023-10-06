<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Fabric\Company;
use App\Models\Fabric\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\LaravelTestCase;

class AdminUserRoleControllerTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();
        $this->user = $this->company->users()->save(factory(User::class)->make());

        $this->withPermission = $this->company->users()->save(factory(User::class)->states('patchworks admin')->make());
        $this->withoutPermission = $this->company->users()->save(factory(User::class)->states('patchworks user')->make());

        $this->testRole = factory(Role::class)->create();
    }

    public function test_user_with_permission_can_add_role(): void
    {
        $this
            ->passportAs($this->withPermission)
            ->put(route('api.v2.admin.users.roles.update', [$this->user, $this->testRole]))
            ->assertOk();
    }

    public function test_user_without_permission_cannot_add_role(): void
    {
        $this
            ->passportAs($this->withoutPermission)
            ->put(route('api.v2.admin.users.roles.update', [$this->user, $this->testRole]))
            ->assertForbidden();
    }

    public function test_user_with_permission_can_remove_role(): void
    {
        $this->user->assignRole($this->testRole);

        $this
            ->passportAs($this->withPermission)
            ->delete(route('api.v2.admin.users.roles.destroy', [$this->user, $this->testRole]))
            ->assertOk();
    }

    public function test_user_without_permission_cannot_remove_role(): void
    {
        $this->user->assignRole($this->testRole);

        $this
            ->passportAs($this->withoutPermission)
            ->delete(route('api.v2.admin.users.roles.destroy', [$this->user, $this->testRole]))
            ->assertForbidden();
    }
}
