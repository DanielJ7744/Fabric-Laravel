<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Fabric\Company;
use App\Models\Fabric\FilterOperator;
use App\Models\Fabric\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\LaravelTestCase;

class AdminFilterOperatorControllerTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();
        $this->withoutPermissions = $this->company->users()->save(factory(User::class)->states('patchworks user')->make());
        $this->withPermissions = $this->company->users()->save(factory(User::class)->states('patchworks admin')->make());
        $this->filterOperator = factory(FilterOperator::class)->create();
    }

    public function test_user_with_permission_can_create_filter_operators(): void
    {
        $attributes = factory(FilterOperator::class)->raw();

        $this
            ->passportAs($this->withPermissions)
            ->postJson(route('api.v2.admin.filter-operators.store'), $attributes)
            ->assertCreated();

        $this->assertDatabaseHas((new FilterOperator())->getTable(), $attributes);
    }

    public function test_user_without_permission_cannot_create_filter_operators(): void
    {
        $attributes = factory(FilterOperator::class)->raw();

        $this
            ->passportAs($this->withoutPermissions)
            ->postJson(route('api.v2.admin.filter-operators.store'), $attributes)
            ->assertForbidden();
    }

    public function test_user_with_permission_can_update_filter_operators(): void
    {
        $attributes = factory(FilterOperator::class)->raw(['name' => 'Updated Name']);

        $this
            ->passportAs($this->withPermissions)
            ->putJson(route('api.v2.admin.filter-operators.update', $this->filterOperator), $attributes)
            ->assertOk();

        $this->assertSame($attributes['name'], $this->filterOperator->fresh()->name);
    }

    public function test_user_without_permission_cannot_update_filter_operators(): void
    {
        $attributes = factory(FilterOperator::class)->raw(['name' => 'Updated Name']);

        $this
            ->passportAs($this->withoutPermissions)
            ->putJson(route('api.v2.admin.filter-operators.update', $this->filterOperator), $attributes)
            ->assertForbidden();
    }

    public function test_user_with_permission_can_delete_a_filter_operator(): void
    {
        $this
            ->passportAs($this->withPermissions)
            ->delete(route('api.v2.admin.filter-operators.destroy', $this->filterOperator))
            ->assertOk()
            ->assertJsonPath('message', 'Filter operator deleted successfully.');

        $this->assertDatabaseMissing($this->filterOperator->getTable(), $this->filterOperator->only('id'));
    }

    public function test_user_without_permission_cannot_delete_a_filter_operator(): void
    {
        $this
            ->passportAs($this->withoutPermissions)
            ->delete(route('api.v2.admin.filter-operators.destroy', $this->filterOperator))
            ->assertForbidden();
    }
}
