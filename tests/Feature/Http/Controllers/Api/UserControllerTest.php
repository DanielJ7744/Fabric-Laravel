<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Fabric\Company;
use App\Models\Fabric\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\LaravelTestCase;

/**
 * @group users
 */
class UserControllerTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();

        $this->user = $this->company->users()->save(factory(User::class)->make());
        $this->clientUser = $this->company->users()->save(factory(User::class)->states('client user')->make());
        $this->clientAdmin = $this->company->users()->save(factory(User::class)->states('client admin')->make());
    }

    public function test_user_with_permission_can_retrieve_users(): void
    {
        $this
            ->passportAs($this->clientUser)
            ->getJson(route('api.v2.users.index'))
            ->assertOk();
    }

    public function test_user_with_permission_can_retrieve_an_user(): void
    {
        $this
            ->passportAs($this->clientUser)
            ->getJson(route('api.v2.users.show', $this->user))
            ->assertOk()
            ->assertJsonPath('data.id', $this->user->getKey());
    }

    public function test_user_with_permission_can_create_users(): void
    {
        $subscription = $this->company->subscriptions()->first();
        $subscription->users = 3;
        $subscription->save();

        $this
            ->passportAs($this->clientAdmin)
            ->postJson(route('api.v2.users.store'), factory(User::class)->raw())
            ->assertCreated();
    }

    public function test_user_without_permission_cannot_create_users(): void
    {
        $this
            ->passportAs($this->clientUser)
            ->postJson(route('api.v2.users.store'), factory(User::class)->raw())
            ->assertForbidden();
    }

    public function test_user_cannot_create_more_users_than_their_subscription_allows(): void
    {
        $userLimit = $this->company->subscriptionAllowance()->users;
        $this->company->users()->saveMany(factory(User::class, $userLimit)->make());
        $attributes = factory(User::class)->raw(['role_id' => Role::whereName('patchworks admin')->value('id')]);

        $this
            ->passportAs($this->clientAdmin)
            ->postJson(route('api.v2.users.store'), $attributes)
            ->assertForbidden();
    }

    public function test_user_with_permission_can_update_users(): void
    {
        $user = $this->company->users()->save(factory(User::class)->make());
        $attributes = factory(User::class)->raw(['name' => 'Test Name']);

        $this
            ->passportAs($this->clientAdmin)
            ->putJson(route('api.v2.users.update', $user), $attributes)
            ->assertOk();

        $this->assertSame($attributes['name'], $user->fresh()->name);
    }

    public function test_user_without_permission_cannot_update_users(): void
    {
        $user = $this->company->users()->save(factory(User::class)->make());

        $this
            ->passportAs($this->clientUser)
            ->putJson(route('api.v2.users.update', $user), factory(User::class)->raw(['name' => 'Test Name']))
            ->assertForbidden();
    }

    public function test_user_with_permission_can_delete_an_user(): void
    {
        $this->passportAs($this->clientAdmin)
            ->deleteJson(route('api.v2.users.destroy', $this->user))
            ->assertOk()
            ->assertJsonPath('message', 'User deleted successfully.');

        $this->assertSoftDeleted($this->user->getTable(), $this->user->only('id'));
    }

    public function test_user_without_permission_cannot_delete_an_user(): void
    {
        $this->passportAs($this->clientUser)
            ->deleteJson(route('api.v2.users.destroy', $this->user))
            ->assertForbidden();
    }
}
