<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Fabric\Company;
use App\Models\Fabric\FilterField;
use App\Models\Fabric\FilterOperator;
use App\Models\Fabric\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\LaravelTestCase;

class AdminFilterFieldOperatorControllerTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();
        $this->user = $this->company->users()->save(factory(User::class)->make());

        $this->withPermission = $this->company->users()->save(factory(User::class)->states('patchworks admin')->make());
        $this->withoutPermission = $this->company->users()->save(factory(User::class)->states('patchworks user')->make());

        $this->filterField = factory(FilterField::class)->create();
        $this->filterOperator = factory(FilterOperator::class)->create();
    }

    public function test_user_with_permission_can_add_filter_operator(): void
    {
        $this
            ->passportAs($this->withPermission)
            ->put(route('api.v2.admin.filter-fields.operators.update', [$this->filterField, $this->filterOperator]))
            ->assertOk();
    }

    public function test_user_without_permission_cannot_add_filter_operator(): void
    {
        $this
            ->passportAs($this->withoutPermission)
            ->put(route('api.v2.admin.filter-fields.operators.update', [$this->filterField, $this->filterOperator]))
            ->assertForbidden();
    }

    public function test_user_with_permission_can_remove_filter_operator(): void
    {
        $this->filterField->filterType()->syncWithoutDetaching($this->filterOperator);

        $this
            ->passportAs($this->withPermission)
            ->delete(route('api.v2.admin.filter-fields.operators.destroy', [$this->filterField, $this->filterOperator]))
            ->assertOk();
    }

    public function test_user_without_permission_cannot_remove_filter_operator(): void
    {
        $this->filterField->filterType()->syncWithoutDetaching($this->filterOperator);

        $this
            ->passportAs($this->withoutPermission)
            ->delete(route('api.v2.admin.filter-fields.operators.destroy', [$this->filterField, $this->filterOperator]))
            ->assertForbidden();
    }
}
