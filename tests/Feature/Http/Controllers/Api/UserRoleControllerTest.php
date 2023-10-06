<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Fabric\Company;
use App\Models\Fabric\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\LaravelTestCase;

class UserRoleControllerTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();
        $this->user = $this->company->users()->save(factory(User::class)->make());

        $this->patchworksAdmin = $this->company->users()->save(factory(User::class)->states('patchworks admin')->make());
        $this->patchworksUser = $this->company->users()->save(factory(User::class)->states('patchworks user')->make());
        $this->clientAdmin = $this->company->users()->save(factory(User::class)->states('client admin')->make());
        $this->clientUser = $this->company->users()->save(factory(User::class)->states('client user')->make());

        $this->patchworksAdminRole = Role::whereName('patchworks admin')->firstOrFail();
        $this->clientAdminRole = Role::whereName('client admin')->firstOrFail();
        $this->clientUserRole = Role::whereName('client user')->firstOrFail();
    }

    public function test_admin_user_with_permission_can_add_patchworks_role(): void
    {
        $this
            ->passportAs($this->patchworksAdmin)
            ->put(route('api.v2.users.roles.update', [$this->user, $this->patchworksAdminRole]))
            ->assertOk();
    }

    public function test_user_without_permission_cannot_add_patchworks_role(): void
    {
        $this
            ->passportAs($this->patchworksUser)
            ->put(route('api.v2.users.roles.update', [$this->user, $this->patchworksAdminRole]))
            ->assertForbidden();
    }

    public function test_client_user_with_permission_can_add_client_role(): void
    {
        $this
            ->passportAs($this->clientAdmin)
            ->put(route('api.v2.users.roles.update', [$this->user, $this->clientAdminRole]))
            ->assertOk();
    }

    public function test_user_without_permission_cannot_add_client_role(): void
    {
        $this
            ->passportAs($this->clientUser)
            ->put(route('api.v2.users.roles.update', [$this->user, $this->clientAdminRole]))
            ->assertForbidden();
    }

    public function test_admin_user_with_permission_can_remove_role(): void
    {
        $this->user->assignRole($this->patchworksAdminRole);

        $this
            ->passportAs($this->patchworksAdmin)
            ->delete(route('api.v2.users.roles.destroy', [$this->user, $this->patchworksAdminRole]))
            ->assertOk();
    }

    public function test_user_without_permission_cannot_remove_role(): void
    {
        $this->user->assignRole($this->patchworksAdminRole);

        $this
            ->passportAs($this->patchworksUser)
            ->delete(route('api.v2.users.roles.destroy', [$this->user, $this->patchworksAdminRole]))
            ->assertForbidden();
    }

    public function test_client_user_with_permission_can_remove_client_role(): void
    {
        $this->user->assignRole($this->clientUserRole);

        $this
            ->passportAs($this->clientAdmin)
            ->delete(route('api.v2.users.roles.destroy', [$this->user, $this->clientUserRole]))
            ->assertOk();
    }

    public function test_user_without_permission_cannot_remove_client_role(): void
    {
        $this->user->assignRole($this->clientUserRole);

        $this
            ->passportAs($this->clientUser)
            ->delete(route('api.v2.users.roles.destroy', [$this->user, $this->clientUserRole]))
            ->assertForbidden();
    }

    public function test_current_user_cannot_remove_own_role(): void
    {
        $this
            ->passportAs($this->patchworksAdmin)
            ->delete(route('api.v2.users.roles.destroy', [$this->patchworksAdmin, $this->patchworksAdminRole]))
            ->assertForbidden();
    }
}
