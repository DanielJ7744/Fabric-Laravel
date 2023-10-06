<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Fabric\Company;
use App\Models\Fabric\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\LaravelTestCase;

/**
 * @group company
 */
class PatchworksCompanyControllerTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();
        $this->withoutPermissions = $this->company->users()->save(factory(User::class)->states('client user')->make());
        $this->withPermissions = $this->company->users()->save(factory(User::class)->states('patchworks admin')->make());
    }

    public function test_user_with_permission_can_retrieve_companies(): void
    {
        $this
            ->passportAs($this->withPermissions)
            ->getJson(route('api.v2.patchworks.companies.index'))
            ->assertOk()
            ->assertJsonPath('data.0.id', $this->company->id);
    }

    public function test_user_without_permission_cannot_retrieve_companies(): void
    {
        $this
            ->passportAs($this->withoutPermissions)
            ->getJson(route('api.v2.patchworks.companies.index'))
            ->assertForbidden();
    }

    public function test_user_with_permission_can_retrieve_a_company(): void
    {
        $this
            ->passportAs($this->withPermissions)
            ->getJson(route('api.v2.patchworks.companies.show', $this->company))
            ->assertOk()
            ->assertJsonPath('data.id', $this->company->getKey());
    }

    public function test_user_without_permission_cannot_retrieve_a_company(): void
    {
        $this
            ->passportAs($this->withoutPermissions)
            ->getJson(route('api.v2.patchworks.companies.show', $this->company))
            ->assertForbidden();
    }
}
