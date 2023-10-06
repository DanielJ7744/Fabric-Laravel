<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Fabric\Company;
use App\Models\Fabric\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\LaravelTestCase;

/**
 * @group users
 */
class AdminUserControllerTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();
        $this->user = $this->company->users()->save(factory(User::class)->make());
        $this->withoutPermissions = $this->company->users()->save(factory(User::class)->states('client user')->make());
        $this->withPermissions = $this->company->users()->save(factory(User::class)->states('patchworks admin')->make());
    }

    public function test_user_with_permission_can_retrieve_users(): void
    {
        $this
            ->passportAs($this->withPermissions)
            ->getJson(route('api.v2.admin.users.index'))
            ->assertOk();
    }

    public function test_user_without_permission_cannot_retrieve_users(): void
    {
        $this
            ->passportAs($this->withoutPermissions)
            ->getJson(route('api.v2.admin.users.index'))
            ->assertForbidden();
    }

    public function test_user_with_permission_can_retrieve_an_user(): void
    {
        $this
            ->passportAs($this->withPermissions)
            ->getJson(route('api.v2.admin.users.show', $this->user))
            ->assertOk()
            ->assertJsonPath('data.id', $this->user->getKey());
    }

    public function test_user_with_permission_can_update_users(): void
    {
        $user = $this->company->users()->save(factory(User::class)->make());

        $attributes = factory(User::class)->raw(['name' => 'Test Name']);

        $this
            ->passportAs($this->withPermissions)
            ->putJson(route('api.v2.admin.users.update', $user), $attributes)
            ->assertOk();

        $this->assertSame($attributes['name'], $user->fresh()->name);
    }

    public function test_user_without_permission_cannot_update_users(): void
    {
        $user = $this->company->users()->save(factory(User::class)->make());
        $attributes = factory(User::class)->raw(['name' => 'Test Name']);

        $this
            ->passportAs($this->withoutPermissions)
            ->putJson(route('api.v2.admin.users.update', $user), $attributes)
            ->assertForbidden();
    }

    public function test_user_with_permission_can_delete_an_user(): void
    {
        $this
            ->passportAs($this->withPermissions)
            ->deleteJson(route('api.v2.admin.users.destroy', $this->user))
            ->assertOk()
            ->assertJsonPath('message', 'User deleted successfully.');

        $this->assertSoftDeleted($this->user->getTable(), $this->user->only('id'));
    }

    public function test_user_without_permission_cannot_delete_an_user(): void
    {
        $this
            ->passportAs($this->withoutPermissions)
            ->deleteJson(route('api.v2.admin.users.destroy', $this->user))
            ->assertForbidden();
    }
}
