<?php

namespace Tests\Feature;

use App\Models\Fabric\Company;
use App\Models\Fabric\ReportSyncResult;
use App\Models\Fabric\User;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ReportSyncResultsTest extends TestCase
{
    use RefreshDatabase;

    protected $resourceType = 'report-sync-results';

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
        $permission = Permission::where('name', 'search report-sync-results')->first();
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
        $expectedResults = new ReportSyncResult($testData);
        $response = $this->doSearch();
        $response->assertFetchedOne($expectedResults);
    }

    private function getTestData(): array
    {
        return [
            'integrations_no_entities' => collect([]),
            'entities' => collect([
                [
                    'id' => 1,
                    'type' => 'Credentials',
                    'system_chain' => 'Shopify',
                    'common_ref' => 'live',
                    'source_id' => null,
                    'status' => null,
                    'message' => null,
                    'first_service_id' => null,
                    'last_run_id' => null,
                    'created_at' => null,
                    'error' => null,
                    'resync_column' => null,
                    'filter_values' => null,
                    'filter_template_id' => null,
                    'integration' => [
                        'id' => 1,
                        'name' => 'test_name',
                        'username' => 'test_integration'
                    ]
                ],
                [
                    'id' => 2,
                    'type' => 'Credentials',
                    'system_chain' => 'Shopify',
                    'common_ref' => 'pending',
                    'source_id' => null,
                    'status' => null,
                    'message' => null,
                    'first_service_id' => null,
                    'last_run_id' => null,
                    'created_at' => null,
                    'error' => null,
                    'resync_column' => null,
                    'filter_values' => null,
                    'filter_template_id' => null,
                    'integration' => [
                        'id' => 3,
                        'name' => 'test_name',
                        'username' => 'test_integration'
                    ]
                ],
            ]),
            'results_offset' => 0,
            'total_results' => 2,
            'counts_per_integration' => collect([
                [
                    'integration_id' => 1,
                    'integration_username' => 'test_integration',
                    'integration_name' => 'test_name',
                    'entity_counts' => [
                        [
                            'type' => 'Credentials',
                            'system_chain' => 'Shopify',
                            'count' => 1
                        ]
                    ],
                    'entity_count_total' => 1
                ],
                [
                    'integration_id' => 3,
                    'integration_username' => 'test_integration',
                    'integration_name' => 'test_name',
                    'entity_counts' => [
                        [
                            'type' => 'Credentials',
                            'system_chain' => 'Shopify',
                            'count' => 1
                        ]
                    ],
                    'entity_count_total' => 1
                ]
            ]),
            'pages_per_integration' => [
                [
                    'integration_id' => 1,
                    'total_pages' => 1,
                    'current_page' => 1,
                    'next_page' => null,
                    'previous_page' => null,
                    'page_size' => 50
                ]
            ],
            'available_entities' => []
        ];
    }
}
