<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Fabric\Company;
use App\Models\Fabric\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\LaravelTestCase;

class MyCompanyControllerTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->company = factory(Company::class)->create(['name' => 'Test Company Limited']);
        $this->clientUser = $this->company->users()->save(factory(User::class)->make());
        $clientUserRole = Role::where('name', 'client user')->first();
        $this->clientUser->assignRole($clientUserRole);

        Permission::whereIn('name', ['update companies'])
            ->get()
            ->each(fn ($permission) => $clientUserRole->givePermissionTo($permission));
    }

    public function test_client_admins_can_update_their_company(): void
    {
        $this
            ->passportAs($this->clientUser)
            ->putJson(route('api.v2.my.company.update'), ['name' => 'Test Company Incorporated',])
            ->assertOk()
            ->assertJsonPath('data.name', 'Test Company Incorporated');
    }

    public function test_user_can_show_their_company(): void
    {
        $this
            ->passportAs($this->clientUser)
            ->get(route('api.v2.my.company.show'))
            ->assertOk()
            ->assertJsonPath('data.id', $this->company->id);
    }
}
