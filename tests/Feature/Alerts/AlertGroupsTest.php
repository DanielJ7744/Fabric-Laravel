<?php

namespace Tests\Feature\Alerts;

use App\Models\Alerting\AlertGroups;
use App\Models\Fabric\Company;
use App\Models\Fabric\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AlertGroupsTest extends TestCase
{
    use RefreshDatabase;
    protected const TEST_ROLE = 'Test Role';

    protected $resourceType = 'alert-groups';

    public function testWithoutPermissionsCannotSearch(): void
    {
        $user = factory(User::class)->create();
        $this->passportAs($user);
        $this->assertAuthenticated();

        $role = factory(Role::class)->create(['name' => 'Test Role', 'patchworks_role' => true]);
        $user->assignRole($role);

        $response = $this->doSearch();
        $response->assertStatus(403);
    }

    public function testWithSearchAlertRecipientsPermissionCanSearch(): void
    {
        $user = factory(User::class)->create();
        $this->passportAs($user);
        $this->assertAuthenticated();

        $role = factory(Role::class)->create(['name' => 'Test Role', 'patchworks_role' => true]);
        $user->assignRole($role);

        $permission = Permission::where('name', 'search alert-groups')->first();
        $role->givePermissionTo($permission);

        $response = $this->doSearch();
        $response->assertFetchedMany(AlertGroups::all());
    }

    public function testUnauthenticatedUserCannotRead(): void
    {
        $company = factory(Company::class)->create();
        $alert = factory(AlertGroups::class)->create([
            'name' => 'Test-group',
            'company_id' => $company->id
        ]);

        $response = $this->doRead($alert);
        $response->assertStatus(401);
    }

    public function testWithoutPermissionsCannotRead(): void
    {
        $user = factory(User::class)->create();
        $this->passportAs($user);
        $this->assertAuthenticated();

        $role = factory(Role::class)->create(['name' => self::TEST_ROLE, 'patchworks_role' => true]);
        $user->assignRole($role);

        $alert = factory(AlertGroups::class)->create([
            'name' => 'Test-group',
            'company_id' => factory(Company::class)->create()
        ]);

        $response = $this->doRead($alert);
        $response->assertStatus(403);
    }

    public function testWithPermissionCanRead(): void
    {
        $user = factory(User::class)->create();
        $this->passportAs($user);
        $this->assertAuthenticated();

        $role = factory(Role::class)->create(['name' => self::TEST_ROLE, 'patchworks_role' => true]);
        $user->assignRole($role);

        $permission = Permission::where('name', 'read alert-groups')->first();
        $role->givePermissionTo($permission);

        $company = factory(Company::class)->create();
        $alert = factory(AlertGroups::class)->create([
            'name' => 'Test-group',
            'company_id' => $company->id
        ]);

        $response = $this->doRead($alert);
        $response->assertFetchedOne($alert);
    }

    public function testUnauthenticatedUserCannotCreate(): void
    {
        $response = $this->doCreate([]);
        $response->assertStatus(401);
    }

    public function testWithoutPermissionsCannotCreate(): void
    {
        $user = factory(User::class)->create();
        $this->passportAs($user);
        $this->assertAuthenticated();

        $role = factory(Role::class)->create(['name' => self::TEST_ROLE, 'patchworks_role' => true]);
        $user->assignRole($role);

        $data = [
            'type' => $this->resourceType,
            'attributes' => [
                'name' => 'test-group',
                'group_id' => 1
            ],
        ];

        $response = $this->doCreate($data);
        $response->assertStatus(403);
    }
}
