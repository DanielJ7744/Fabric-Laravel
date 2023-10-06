<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Fabric\Company;
use App\Models\Fabric\FilterOperator;
use App\Models\Fabric\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\LaravelTestCase;

class FilterOperatorControllerTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();
        $this->withoutPermissions = $this->company->users()->save(factory(User::class)->make());
        $this->withPermissions = $this->company->users()->save(factory(User::class)->states('client user')->make());
        $this->filterOperator = factory(FilterOperator::class)->create();
    }

    public function test_user_with_permission_can_retrieve_filter_operators(): void
    {
        $this
            ->passportAs($this->withPermissions)
            ->getJson(route('api.v2.filter-operators.index'))
            ->assertOk();
    }

    public function test_user_without_permission_cannot_retrieve_filter_operators(): void
    {
        $this
            ->passportAs($this->withoutPermissions)
            ->getJson(route('api.v2.filter-operators.index'))
            ->assertForbidden();
    }

    public function test_user_with_permission_can_retrieve_a_filter_operator(): void
    {
        $this
            ->passportAs($this->withPermissions)
            ->getJson(route('api.v2.filter-operators.show', $this->filterOperator))
            ->assertOk()
            ->assertJsonPath('data.id', $this->filterOperator->id);
    }

    public function test_user_without_permission_cannot_retrieve_a_filter_operator(): void
    {
        $this
            ->passportAs($this->withoutPermissions)
            ->getJson(route('api.v2.filter-operators.show', $this->filterOperator))
            ->assertForbidden();
    }
}
