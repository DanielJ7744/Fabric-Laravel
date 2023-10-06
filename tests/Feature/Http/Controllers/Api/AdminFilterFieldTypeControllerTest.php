<?php

namespace Tests\Feature\Http\Controllers\Api;

use Tests\LaravelTestCase;
use App\Models\Fabric\User;
use App\Models\Fabric\Company;
use App\Models\Fabric\FilterType;
use App\Models\Fabric\FilterField;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminFilterFieldTypeControllerTest extends LaravelTestCase
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
        $this->filterType = factory(FilterType::class)->create();
    }

    public function test_user_with_permission_can_add_filter_type(): void
    {
        $this
            ->passportAs($this->withPermission)
            ->put(route('api.v2.admin.filter-fields.types.update', [$this->filterField, $this->filterType]))
            ->assertOk();
    }

    public function test_user_without_permission_cannot_add_filter_type(): void
    {
        $this
            ->passportAs($this->withoutPermission)
            ->put(route('api.v2.admin.filter-fields.types.update', [$this->filterField, $this->filterType]))
            ->assertForbidden();
    }

    public function test_user_with_permission_can_remove_filter_type(): void
    {
        $this->filterField->filterType()->syncWithoutDetaching($this->filterType);

        $this
            ->passportAs($this->withPermission)
            ->delete(route('api.v2.admin.filter-fields.types.destroy', [$this->filterField, $this->filterType]))
            ->assertOk();
    }

    public function test_user_without_permission_cannot_remove_filter_type(): void
    {
        $this->filterField->filterType()->syncWithoutDetaching($this->filterType);

        $this
            ->passportAs($this->withoutPermission)
            ->delete(route('api.v2.admin.filter-fields.types.destroy', [$this->filterField, $this->filterType]))
            ->assertForbidden();
    }
}
