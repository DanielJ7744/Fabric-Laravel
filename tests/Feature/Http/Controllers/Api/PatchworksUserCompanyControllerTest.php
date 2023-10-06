<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Fabric\Company;
use App\Models\Fabric\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\LaravelTestCase;

/**
 * @group company
 */
class PatchworksUserCompanyControllerTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();
        $this->clientAdmin = $this->company->users()->save(factory(User::class)->states('client admin')->make());
        $this->patchworksUser = $this->company->users()->save(factory(User::class)->states('patchworks user')->make());
        $this->patchworksAdmin = $this->company->users()->save(factory(User::class)->states('patchworks admin')->make());
        $this->user = $this->company->users()->save(factory(User::class)->make());
    }

    public function test_admin_user_with_permission_can_change_user_company(): void
    {
        $newCompany = factory(Company::class)->create();

        $this
            ->passportAs($this->patchworksAdmin)
            ->put(route('api.v2.patchworks.user.company.update', [$this->user, $newCompany]))
            ->assertOk();
    }

    public function test_patchworks_user_with_permission_can_change_user_company(): void
    {
        $newCompany = factory(Company::class)->create();

        $this
            ->passportAs($this->patchworksUser)
            ->put(route('api.v2.patchworks.user.company.update', [$this->user, $newCompany]))
            ->assertOk();
    }

    public function test_user_without_permission_cannot_change_user_company(): void
    {
        $newCompany = factory(Company::class)->create();

        $this
            ->passportAs($this->clientAdmin)
            ->put(route('api.v2.patchworks.user.company.update', [$this->user, $newCompany]))
            ->assertForbidden();
    }
}
