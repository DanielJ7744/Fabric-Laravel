<?php

namespace Tests\Feature\Alerts;

use App\Models\Alerting\AlertManager;
use App\Models\Fabric\Company;
use App\Models\Fabric\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AlertManagerTest extends TestCase
{
    use RefreshDatabase;
    protected const TEST_ROLE = 'Test Role';

    protected $resourceType = 'alert-manager';

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

    public function testWithSearchAlertManagerPermissionCanSearch(): void
    {
        $user = factory(User::class)->create();
        $this->passportAs($user);
        $this->assertAuthenticated();

        $role = factory(Role::class)->create(['name' => 'Test Role', 'patchworks_role' => true]);
        $user->assignRole($role);

        $permission = Permission::where('name', 'search alert-manager')->first();
        $role->givePermissionTo($permission);

        $response = $this->doSearch();
        $response->assertFetchedMany(AlertManager::all());
    }

    public function testUnauthenticatedUserCannotRead(): void
    {
        $company = factory(Company::class)->create();
        $alertManager = factory(AlertManager::class)->create([
            'company_id' => $company->id,
            'service_id' => 1,
            'config_id' => 1,
            'recipient_id' => 1,
            'alert_type' => 'error',
            'send_from' => Carbon::now()->toDateTimeString(),
            'seen_on_dashboard' => 0
        ]);

        $response = $this->doRead($alertManager);
        $response->assertStatus(401);
    }

    public function testWithoutPermissionsCannotRead(): void
    {
        $user = factory(User::class)->create();
        $this->passportAs($user);
        $this->assertAuthenticated();

        $role = factory(Role::class)->create(['name' => self::TEST_ROLE, 'patchworks_role' => true]);
        $user->assignRole($role);

        $company = factory(Company::class)->create();
        $alertManager = factory(AlertManager::class)->create([
            'company_id' => $company->id,
            'service_id' => 1,
            'config_id' => 1,
            'recipient_id' => 1,
            'alert_type' => 'error',
            'send_from' => Carbon::now()->toDateTimeString(),
            'seen_on_dashboard' => 0
        ]);

        $response = $this->doRead($alertManager);
        $response->assertStatus(403);
    }

    public function testWithPermissionCanRead(): void
    {
        $user = factory(User::class)->create();
        $this->passportAs($user);
        $this->assertAuthenticated();

        $role = factory(Role::class)->create(['name' => self::TEST_ROLE, 'patchworks_role' => true]);
        $user->assignRole($role);

        $permission = Permission::where('name', 'read alert-manager')->first();
        $role->givePermissionTo($permission);

        $company = factory(Company::class)->create();
        $alertManager = factory(AlertManager::class)->create([
            'company_id' => $company->id,
            'service_id' => 1,
            'config_id' => 1,
            'recipient_id' => 1,
            'alert_type' => 'error',
            'send_from' => Carbon::now()->toDateTimeString(),
            'seen_on_dashboard' => 0
        ]);

        $response = $this->doRead($alertManager);
        $response->assertFetchedOne($alertManager);
    }

    public function testUnauthenticatedUserCannotUpdate(): void
    {
        $alertManager = factory(AlertManager::class)->create();
        $data = [
            'type' => $this->resourceType,
            'id' => (string)$alertManager->id,
            'attributes' => [
                'seen_on_dashboard' => 1
            ]
        ];
        $response = $this->doUpdate($data);
        $response->assertStatus(401);
    }

    public function testWithoutPermissionsCannotUpdate(): void
    {
        $user = factory(User::class)->create();
        $this->passportAs($user);
        $this->assertAuthenticated();

        $role = factory(Role::class)->create(['name' => 'Test Role', 'patchworks_role' => true]);
        $user->assignRole($role);

        $alertManager = factory(AlertManager::class)->create();
        $data = [
            'type' => $this->resourceType,
            'id' => (string)$alertManager->id,
            'attributes' => [
                'seen_on_dashboard' => 1
            ]
        ];

        $response = $this->doUpdate($data);
        $response->assertStatus(403);
    }

    public function testWithUpdateEntitiesPermissionCanUpdate(): void
    {
        $user = factory(User::class)->create();
        $this->passportAs($user);
        $this->assertAuthenticated();

        $role = factory(Role::class)->create(['name' => 'Test Role', 'patchworks_role' => true]);
        $user->assignRole($role);

        $permission = Permission::where('name', 'update alert-manager')->first();
        $role->givePermissionTo($permission);

        $company = factory(Company::class)->create();
        $alertManager = factory(AlertManager::class)->create([
            'company_id' => $company->id,
            'service_id' => 1,
            'config_id' => 1,
            'recipient_id' => 1,
            'alert_type' => 'error',
            'send_from' => Carbon::now()->toDateTimeString(),
            'seen_on_dashboard' => 0
        ]);
        $data = [
            'type' => $this->resourceType,
            'id' => (string)$alertManager->id,
            'attributes' => [
                'seen_on_dashboard' => 1,
            ],
        ];

        $response = $this->doUpdate($data);
        $response->assertUpdated($data);
    }
}
