<?php

namespace Tests\Feature\Alerts;

use App\Models\Alerting\AlertConfigs;
use App\Models\Fabric\Company;
use App\Models\Fabric\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AlertConfigsTest extends TestCase
{
    use RefreshDatabase;

    protected const TEST_ROLE = 'Test Role';

    protected $resourceType = 'alert-configs';

    public function testWithoutPermissionsCannotSearch(): void
    {
        $user = factory(User::class)->create();
        $this->passportAs($user);

        $response = $this->doSearch();
        $response->assertStatus(403);
    }

    public function testWithSearchAlertConfigsPermissionCanSearch(): void
    {
        $user = factory(User::class)->create();
        $this->passportAs($user);
        $this->assertAuthenticated();

        $role = factory(Role::class)->create(['name' => 'Test Role', 'patchworks_role' => true]);
        $user->assignRole($role);

        $permission = Permission::where('name', 'search alert-configs')->first();
        $role->givePermissionTo($permission);

        $response = $this->doSearch();
        $response->assertFetchedMany(AlertConfigs::all());
    }

    public function testUnauthenticatedUserCannotRead(): void
    {
        $company = factory(Company::class)->create();
        $alert = factory(AlertConfigs::class)->create([
            'company_id' => $company->id,
            'service_id' => 1337,
            'error_alert_threshold' => 123,
            'warning_alert_threshold' => 11,
            'frequency_alert_threshold' => 31,
            'alert_frequency' => 'off',
            'throttle_value' => 1,
            'alert_status' => 1
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

        $company = factory(Company::class)->create();
        $alert = factory(AlertConfigs::class)->create([
            'company_id' => $company->id,
            'service_id' => 1337,
            'error_alert_threshold' => 123,
            'warning_alert_threshold' => 11,
            'frequency_alert_threshold' => 31,
            'alert_frequency' => 'off',
            'throttle_value' => 1,
            'alert_status' => 1
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

        $permission = Permission::where('name', 'read alert-configs')->first();
        $role->givePermissionTo($permission);

        $company = factory(Company::class)->create();
        $alert = factory(AlertConfigs::class)->create([
            'company_id' => $company->id,
            'service_id' => 1337,
            'error_alert_threshold' => 123,
            'warning_alert_threshold' => 11,
            'frequency_alert_threshold' => 31,
            'alert_frequency' => 'off',
            'throttle_value' => 1,
            'alert_status' => 1
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
        $company = factory(Company::class)->create();
        $data = [
            'type' => $this->resourceType,
            'attributes' => [
                'company_id' => $company->id,
                'service_id' => 1337,
                'error_alert_threshold' => 123,
                'warning_alert_threshold' => 11,
                'frequency_alert_threshold' => 31,
                'alert_frequency' => 'off',
                'throttle_value' => 1,
                'alert_status' => 1
            ],
        ];
        $response = $this->doCreate($data);
        $response->assertStatus(403);
    }
}
