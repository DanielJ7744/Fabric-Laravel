<?php

namespace Tests\Feature;

use App\Models\Fabric\Company;
use App\Models\Fabric\ReportSyncFilterOption;
use App\Models\Fabric\User;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ReportSyncFilterOptionsTest extends TestCase
{
    use RefreshDatabase;

    protected $resourceType = 'report-sync-filter-options';

    public function testUnauthenticatedUserCannotSearch(): void
    {
        $response = $this->doSearch();
        $response->assertStatus(401);
    }

    public function testWithoutPermissionsCannotSearch(): void
    {
        $user = factory(User::class)->create();
        $this->passportAs($user);
        $this->assertAuthenticated();

        $role = factory(Role::class)->create(['name' => 'Test Role']);
        $user->assignRole($role);

        $response = $this->doSearch();
        $response->assertStatus(403);
    }

    public function testWithSearchReportSyncCountsPermissionCanSearch(): void
    {
        $user = factory(User::class)->create();
        $this->passportAs($user);
        $this->assertAuthenticated();

        $role = factory(Role::class)->create(['name' => 'Test Role']);
        $user->assignRole($role);
        $permission = Permission::where('name', 'search report-sync-filter-options')->first();
        $role->givePermissionTo($permission);

        $company = factory(Company::class)->create();
        $user->company()->associate($company);
        $testData = $this->getTestData();

        $mockData = [
            'status' => 'success',
            'message' => '',
            'data' => $testData
        ];

        $mockHandler = new MockHandler([new Response(200, [], json_encode($mockData))]);
        $handlerStack = HandlerStack::create($mockHandler);
        $client = new Client(['handler' => $handlerStack]);
        $this->app->instance(Client::class, $client);

        $expectedFilterOptions = new ReportSyncFilterOption($testData);

        $response = $this->doSearch();
        $response->assertFetchedOne($expectedFilterOptions);
    }

    private function getTestData(): array
    {
        return [
            'integrations' => collect([
                [
                    'id' => 1,
                    'name' => 'test',
                    'company_id' => 1,
                    'username' => 'test',
                    'server' => 'test_server',
                ],
            ]),
            'system_chains' => collect([
                [
                    'name' => 'a_b',
                    'integration_id' => 1
                ],
                [
                    'name' => 'a_c',
                    'integration_id' => 1
                ],
            ]),
            'statuses' => collect([
                'Ok',
                'Sent',
                'Failed',
                'Pending',
            ]),
            'types' => collect([
                [
                    'id' => 1,
                    'database_name' => 'Order',
                    'factory_name' => 'Orders',
                ],
                [
                    'id' => 1,
                    'database_name' => 'Fulfilment',
                    'factory_name' => 'Fulfilments',
                ],
            ]),
        ];
    }
}
