<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Fabric\Company;
use App\Models\Fabric\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\LaravelTestCase;

class RoleControllerTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();

        $this->patchworksAdmin = $this->company->users()->save(factory(User::class)->states('patchworks admin')->make());
        $this->clientAdmin = $this->company->users()->save(factory(User::class)->states('client admin')->make());
        $this->clientUser = $this->company->users()->save(factory(User::class)->states('client user')->make());

        $this->patchworksAdminRole = Role::whereName('patchworks admin')->firstOrFail();
        $this->clientUserRole = Role::whereName('client user')->firstOrFail();
    }

    public function test_user_without_permission_cannot_retrieve_roles(): void
    {
        $this
            ->passportAs($this->clientUser)
            ->getJson(route('api.v2.roles.index'))
            ->assertForbidden();
    }

    public function test_user_without_permission_cannot_retrieve_a_role(): void
    {
        $this
            ->passportAs($this->clientUser)
            ->getJson(route('api.v2.roles.show', 1))
            ->assertForbidden();
    }

    public function test_user_with_permission_can_retrieve_any_role(): void
    {
        $this
            ->passportAs($this->patchworksAdmin)
            ->getJson(route('api.v2.roles.show', $this->patchworksAdminRole))
            ->assertOk()
            ->assertJsonPath('data.id', $this->patchworksAdminRole->getKey());
    }

    public function test_user_with_permission_can_retrieve_all_roles(): void
    {
        $this
            ->passportAs($this->patchworksAdmin)
            ->getJson(route('api.v2.roles.index'))
            ->assertOk()
            ->assertJsonFragment(['id' => $this->patchworksAdminRole->getKey()]);
    }

    public function test_client_user_can_only_retrieve_client_roles(): void
    {
        $this
            ->passportAs($this->clientAdmin)
            ->getJson(route('api.v2.roles.index'))
            ->assertOk()
            ->assertJsonMissing(['id' => $this->patchworksAdminRole->getKey()]);
    }

    public function test_client_user_can_retrieve_a_client_role(): void
    {
        $this
            ->passportAs($this->clientAdmin)
            ->getJson(route('api.v2.roles.show', $this->clientUserRole))
            ->assertOk();
    }

    public function test_client_user_cannot_retrieve_a_patchworks_role(): void
    {
        $this
            ->passportAs($this->clientAdmin)
            ->getJson(route('api.v2.roles.show', $this->patchworksAdminRole))
            ->assertNotFound();
    }
}
