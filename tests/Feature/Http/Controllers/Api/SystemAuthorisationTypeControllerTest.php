<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Fabric\Company;
use App\Models\Fabric\SystemAuthorisationType;
use App\Models\Fabric\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\LaravelTestCase;

class SystemAuthorisationTypeControllerTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();
        $this->withoutPermissions = factory(User::class)->create(['company_id' => $this->company->id]);
        $this->withPermissions = factory(User::class)->create(['company_id' => $this->company->id]);
        $this->systemAuthorisationType = factory(SystemAuthorisationType::class)->create();
        $clientUserRole = Role::where('name', 'client user')->first();
        $testRole = factory(Role::class)->create(['name' => 'test user']);
        $this->withPermissions->assignRole($clientUserRole);
        $this->withoutPermissions->assignRole($testRole);
    }

    public function test_user_with_permission_can_retrieve_authorisation_types(): void
    {
        $this
            ->passportAs($this->withPermissions)
            ->getJson(route('api.v2.system-authorisation-types.index'))
            ->assertOk();
    }

    public function test_user_without_permission_cannot_retrieve_authorisation_types(): void
    {
        $this
            ->passportAs($this->withoutPermissions)
            ->getJson(route('api.v2.system-authorisation-types.index'))
            ->assertForbidden();
    }

    public function test_user_with_permission_can_retrieve_an_authorisation_type(): void
    {
        $this
            ->passportAs($this->withPermissions)
            ->getJson(route('api.v2.system-authorisation-types.show', $this->systemAuthorisationType))
            ->assertOk()
            ->assertJsonPath('data.id', $this->systemAuthorisationType->id);
    }

    public function test_user_without_permission_cannot_retrieve_an_authorisation_type(): void
    {
        $this
            ->passportAs($this->withoutPermissions)
            ->getJson(route('api.v2.system-authorisation-types.show', $this->systemAuthorisationType))
            ->assertForbidden();
    }
}
