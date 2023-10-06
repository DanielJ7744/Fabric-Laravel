<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Fabric\Company;
use App\Models\Fabric\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\LaravelTestCase;

/**
 * @group users
 */
class AdminCompanyUserControllerTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();
        $this->withoutPermissions = $this->company->users()->save(factory(User::class)->states('client user')->make());
        $this->withPermissions = $this->company->users()->save(factory(User::class)->states('patchworks admin')->make());
    }

    public function test_user_with_permission_can_create_users(): void
    {
        $subscription = $this->company->subscriptions()->first();
        $subscription->users = 3;
        $subscription->save();

        $this
            ->passportAs($this->withPermissions)
            ->postJson(route('api.v2.admin.company.users.store', $this->company->id), factory(User::class)->raw())
            ->assertCreated();
    }

    public function test_user_without_permission_cannot_create_users(): void
    {
        $this
            ->passportAs($this->withoutPermissions)
            ->postJson(route('api.v2.admin.company.users.store', $this->company->id), factory(User::class)->raw())
            ->assertForbidden();
    }
}
