<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Fabric\Company;
use App\Models\Fabric\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\LaravelTestCase;

/**
 * @group company
 */
class AdminCompanyControllerTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();
        $this->withoutPermissions = $this->company->users()->save(factory(User::class)->states('client user')->make());
        $this->withPermissions = $this->company->users()->save(factory(User::class)->states('patchworks admin')->make());
    }

    public function test_user_with_permission_can_create_companies(): void
    {
        $attributes = factory(Company::class)->raw([
            'name' => 'Test Company Ltd',
            'active' => true
        ]);

        $this
            ->passportAs($this->withPermissions)
            ->postJson(route('api.v2.admin.companies.store'), $attributes)
            ->assertCreated();

        $this->assertDatabaseHas((new Company())->getTable(), $attributes);
    }

    public function test_user_without_permission_cannot_create_companies(): void
    {
        $attributes = factory(Company::class)->raw([
            'name' => 'Test Company Ltd',
            'active' => true
        ]);

        $this
            ->passportAs($this->withoutPermissions)
            ->postJson(route('api.v2.admin.companies.store'), $attributes)
            ->assertForbidden();
    }

    public function test_user_with_permission_can_update_companies(): void
    {
        $company = factory(Company::class)->create(['name' => 'Test Company Ltd']);
        $attributes = factory(Company::class)->raw(['name' => 'Test Company Incorporated']);

        $this
            ->passportAs($this->withPermissions)
            ->putJson(route('api.v2.admin.companies.update', $company), $attributes)
            ->assertOk();

        $this->assertSame($attributes['name'], $company->fresh()->name);
    }

    public function test_user_without_permission_cannot_update_integrations(): void
    {
        $company = factory(Company::class)->create(['name' => 'Test Company Ltd']);
        $attributes = factory(Company::class)->raw(['name' => 'Test Company Incorporated']);

        $this
            ->passportAs($this->withoutPermissions)
            ->putJson(route('api.v2.admin.companies.update', $company), $attributes)
            ->assertForbidden();
    }

    public function test_user_with_permission_can_delete_a_company(): void
    {
        $this
            ->passportAs($this->withPermissions)
            ->deleteJson(route('api.v2.admin.companies.destroy', $this->company))
            ->assertOk()
            ->assertJsonPath('message', 'Company deleted successfully.');

        $this->assertDatabaseMissing($this->company->getTable(), $this->company->only('id'));
    }

    public function test_user_without_permission_cannot_delete_a_company(): void
    {
        $this
            ->passportAs($this->withoutPermissions)
            ->deleteJson(route('api.v2.admin.companies.destroy', $this->company))
            ->assertForbidden();
    }
}
