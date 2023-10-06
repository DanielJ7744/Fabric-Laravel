<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Fabric\Company;
use App\Models\Fabric\Subscription;
use App\Models\Fabric\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\LaravelTestCase;

class AdminCompanySubscriptionControllerTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();
        $this->subscription = factory(Subscription::class)->create();
        $this->user = $this->company->users()->save(factory(User::class)->make());

        $this->withPermissions = $this->company->users()->save(factory(User::class)->states('patchworks admin')->make());
        $this->withoutPermission = $this->company->users()->save(factory(User::class)->states('client admin')->make());
    }

    public function test_patchworks_admin_can_add_subscription(): void
    {
        $this
            ->passportAs($this->withPermissions)
            ->put(route('api.v2.admin.company.subscription.update', [$this->company, $this->subscription]))
            ->assertOk()
            ->assertJsonPath('message', 'Subscription assigned to company successfully.');
    }

    public function test_non_patchworks_admin_cannot_add_subscription(): void
    {
        $this
            ->passportAs($this->withoutPermission)
            ->put(route('api.v2.admin.company.subscription.update', [$this->company, $this->subscription]))
            ->assertForbidden();
    }

    public function test_patchworks_admin_can_remove_subscription(): void
    {
        $this
            ->passportAs($this->withPermissions)
            ->delete(route('api.v2.admin.company.subscription.destroy', [$this->company, $this->subscription]))
            ->assertOk()
            ->assertJsonPath('message', 'Subscription removed from company successfully.');
    }

    public function test_non_patchworks_admin_cannot_remove_subscription(): void
    {
        $this
            ->passportAs($this->withoutPermission)
            ->delete(route('api.v2.admin.company.subscription.destroy', [$this->company, $this->subscription]))
            ->assertForbidden();
    }
}
