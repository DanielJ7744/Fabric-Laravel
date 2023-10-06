<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Http\Helpers\CompanySettingsHelper;
use App\Models\Fabric\Company;
use App\Models\Fabric\Integration;
use App\Models\Fabric\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\LaravelTestCase;

class AdminIntegrationControllerTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();
        $this->integration = $this->company->integrations()->save(factory(Integration::class)->make());
        $this->withoutPermissions = $this->company->users()->save(factory(User::class)->states('client user')->make());
        $this->withPermissions = $this->company->users()->save(factory(User::class)->states('patchworks admin')->make());
    }

    public function test_user_with_permission_can_create_an_integration(): void
    {
        $attributes = factory(Integration::class)->raw([
            'company_id' => $this->company->getKey(),
            'name' => 'Integration'
        ]);

        $this
            ->passportAs($this->withPermissions)
            ->postJson(route('api.v2.admin.integrations.store'), $attributes)
            ->assertCreated();

        $this->assertDatabaseHas((new Integration)->getTable(), $attributes);
    }

    public function test_user_without_permission_cannot_create_an_integration(): void
    {
        $attributes = factory(Integration::class)->raw([
            'company_id' => $this->company->getKey(),
            'name' => 'Integration'
        ]);

        $this
            ->passportAs($this->withoutPermissions)
            ->postJson(route('api.v2.admin.integrations.store'), $attributes)
            ->assertForbidden();
    }

    public function test_user_without_permission_cannot_retrieve_an_integration(): void
    {
        $this
            ->passportAs($this->withoutPermissions)
            ->getJson(route('api.v2.admin.integrations.index'))
            ->assertForbidden();
    }

    public function test_user_with_permission_can_retrieve_company_integrations(): void
    {
        $this
            ->passportAs($this->withPermissions)
            ->getJson(route('api.v2.admin.integrations.index'))
            ->assertOk()
            ->assertJsonPath('data.0.id', $this->integration->id);
    }

    public function test_user_with_permission_can_retrieve_an_integration(): void
    {
        $this
            ->passportAs($this->withPermissions)
            ->getJson(route('api.v2.admin.integrations.show', $this->integration))
            ->assertOk()
            ->assertJsonPath('data.id', $this->integration->getKey());
    }

    public function test_user_with_permission_can_update_integrations(): void
    {
        $integration = $this->company->integrations()->save(factory(Integration::class)->make());
        $attributes = factory(Integration::class)->raw(['name' => 'Integration']);

        $this
            ->passportAs($this->withPermissions)
            ->putJson(route('api.v2.integrations.update', $integration), $attributes)
            ->assertOk();

        $this->assertSame($attributes['name'], $integration->fresh()->name);
    }

    public function test_user_without_permission_cannot_update_integrations(): void
    {
        $integration = $this->company->integrations()->save(factory(Integration::class)->make());
        $attributes = factory(Integration::class)->raw(['name' => 'Integration']);

        $this
            ->passportAs($this->withoutPermissions)
            ->putJson(route('api.v2.integrations.update', $integration), $attributes)
            ->assertForbidden();
    }

    public function test_user_with_permission_can_delete_an_integration(): void
    {
        $this
            ->passportAs($this->withPermissions)
            ->deleteJson(route('api.v2.integrations.destroy', $this->integration))
            ->assertOk()
            ->assertJsonPath('message', 'Integration deleted successfully.');

        $this->assertDeleted($this->integration);
    }

    public function test_user_without_permission_cannot_delete_an_integration(): void
    {
        $this
            ->passportAs($this->withoutPermissions)
            ->deleteJson(route('api.v2.integrations.destroy', $this->integration))
            ->assertForbidden();
    }
}
