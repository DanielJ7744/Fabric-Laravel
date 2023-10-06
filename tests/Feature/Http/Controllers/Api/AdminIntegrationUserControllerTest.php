<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Fabric\Company;
use App\Models\Fabric\Integration;
use App\Models\Fabric\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\LaravelTestCase;

/**
 * @group users
 */
class AdminIntegrationUserControllerTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();
        $this->withoutPermissions = $this->company->users()->save(factory(User::class)->make());
        $this->withPermissions = $this->company->users()->save(factory(User::class)->states('patchworks admin')->make());
        $this->integration = $this->company->integrations()->save(factory(Integration::class)->make());
        $this->attachedUser = $this->company->users()->save(factory(User::class)->make());
        $this->integration->users()->attach($this->attachedUser->id);
    }

    public function test_user_with_permission_can_create_integration_users(): void
    {
        $attributes = ['user_id' => $this->company->users()->save(factory(User::class)->make())->id];

        $this
            ->passportAs($this->withPermissions)
            ->postJson(route('api.v2.admin.integrations.users.store', $this->integration), $attributes)
            ->assertOk()
            ->assertJsonPath('message', 'Integration user created successfully');
    }

    public function test_user_without_permission_cannot_create_integration_users(): void
    {
        $attributes = ['user_id' => $this->company->users()->save(factory(User::class)->make())->id];

        $this
            ->passportAs($this->withoutPermissions)
            ->postJson(route('api.v2.admin.integrations.users.store', $this->integration), $attributes)
            ->assertForbidden();
    }

    public function test_user_with_permission_can_delete_an_integration_user(): void
    {
        $this
            ->passportAs($this->withPermissions)
            ->deleteJson(route('api.v2.admin.integrations.users.destroy', [$this->integration, $this->attachedUser]))
            ->assertOk()
            ->assertJsonPath('message', 'Integration user deleted successfully.');
    }

    public function test_user_without_permission_cannot_delete_an_integration_user(): void
    {
        $this
            ->passportAs($this->withoutPermissions)
            ->deleteJson(route('api.v2.admin.integrations.users.destroy', [$this->integration, $this->attachedUser]))
            ->assertForbidden();
    }
}
