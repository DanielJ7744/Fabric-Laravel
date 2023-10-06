<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Fabric\Company;
use App\Models\Fabric\FilterType;
use App\Models\Fabric\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\LaravelTestCase;

class FilterTypeControllerTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();
        $this->withoutPermissions = $this->company->users()->save(factory(User::class)->make());
        $this->withPermissions = $this->company->users()->save(factory(User::class)->states('client user')->make());
        $this->filterType = factory(FilterType::class)->create();
    }

    public function test_user_with_permissions_can_retrieve_filter_types(): void
    {
        $this
            ->passportAs($this->withPermissions)
            ->getJson(route('api.v2.filter-types.index'))
            ->assertOk();
    }

    public function test_user_without_permissions_cannot_retrieve_filter_types(): void
    {
        $this
            ->passportAs($this->withoutPermissions)
            ->getJson(route('api.v2.filter-types.index'))
            ->assertForbidden();
    }

    public function test_user_with_permissions_can_retrieve_a_filter_type(): void
    {
        $this
            ->passportAs($this->withPermissions)
            ->getJson(route('api.v2.filter-types.show', $this->filterType))
            ->assertOk()
            ->assertJsonPath('data.id', $this->filterType->id);
    }

    public function test_user_without_permissions_cannot_retrieve_a_filter_type(): void
    {
        $this
            ->passportAs($this->withoutPermissions)
            ->getJson(route('api.v2.filter-types.show', $this->filterType))
            ->assertForbidden();
    }
}
