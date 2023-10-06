<?php

namespace Tests\Feature\Http\Api\v1\ServiceLogs;

use App\Events\ServiceScheduled;
use App\Http\Helpers\IntegrationHelper;
use App\Models\Fabric\Company;
use App\Models\Fabric\Entity;
use App\Models\Fabric\Factory;
use App\Models\Fabric\FactorySystem;
use App\Models\Fabric\FilterTemplate;
use App\Models\Fabric\Integration;
use App\Models\Fabric\System;
use App\Models\Fabric\User;
use App\Models\Tapestry\Service;
use App\Models\Tapestry\ServiceLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\LaravelTestCase;

/*
|--------------------------------------------------------------------------
| Why does this exist?
|--------------------------------------------------------------------------
|
| For one reason or another, clients were told that they could use our internal
| api to schedule services. We will be trashing the JSON:API package as soon
| as possible and theses tests ensure functionality doesn't change when
| we replicate the controllers.
|
*/

/**
 * @group v1
 */
class AuthorizerTest extends LaravelTestCase
{
    use RefreshDatabase;

    /**
     * The headers specified by the json api package 💩
     *
     * @var array
     */
    private array $jsonApiHeaders = [
        'Accept' => 'application/vnd.api+json',
        'Content-Type' => 'application/vnd.api+json',
    ];

    public function setUp(): void
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();
        $this->user = $this->company->users()->save(factory(User::class)->states('client user')->make());
        $this->integration = $this->company->integrations()->save(factory(Integration::class)->make());
        $this->service = $this->integration->services()->save(factory(Service::class)->make(['from_factory' => 'Shopify\\Pull\\Products']));
        $this->system = factory(System::class)->create(['name' => 'Shopify', 'factory_name' => 'Shopify']);
        $this->entity = factory(Entity::class)->create(['name' => 'Products']);
        $this->factory = factory(Factory::class)->create(['name' => 'Products']);
        $this->factorySystem = factory(FactorySystem::class)->create([
            'system_id' => $this->system->getKey(),
            'entity_id' => $this->entity->getKey(),
            'factory_id' => $this->factory->getKey(),
            'direction' => 'pull'
        ]);
        $this->filterTemplate = $this->factorySystem->filterTemplate()->save(factory(FilterTemplate::class)->make(['template' => json_encode(['STOCK_CODE' => '%s'])]));

        // TODO: index returns correct null value and 200
        // show returns correct return value and 404
    }

    public function test_users_can_retrieve_service_logs(): void
    {
        $filters = [
            'created_at' => 6
        ];

        $serviceLogs = factory(ServiceLog::class, 20)->create();

        $mock = $this->mock(IntegrationHelper::class, fn ($mock) => $mock
            ->shouldReceive('getFilteredServiceLog')
            ->with($this->integration->server, $this->integration->username, $filters)
            ->andReturn(collect($serviceLogs->toArray())));

        $this
            ->passportAs($this->user)
            ->getJson(sprintf('/api/v1/service-logs?%s', http_build_query(['integration_id' => $this->integration->id] + $filters)), $this->jsonApiHeaders)
            ->assertOk()
            ->assertJsonFragment(['data' => $serviceLogs->map(fn ($log) => $this->convertLogToV1Format($log))->toArray()]);
    }

    public function test_users_can_retrieve_a_service_log(): void
    {
        $serviceLog = factory(ServiceLog::class)->create(['id' => 101]);

        $this
            ->mock(IntegrationHelper::class, fn ($mock) => $mock
                ->shouldReceive('getServiceLog')
                ->with($this->integration->server, $this->integration->username, ['query' => ['id' => 101]])
                ->andReturn($serviceLog->toArray()));

        $this
            ->passportAs($this->user)
            ->getJson(sprintf('/api/v1/service-logs/%s%s', $this->integration->username, '|101'), $this->jsonApiHeaders)
            ->assertOk()
            ->assertJsonFragment(['data' => $this->convertLogToV1Format($serviceLog)]);
    }

    public function test_users_can_create_a_service_log_with_filter_values(): void
    {
        $serviceLog = factory(ServiceLog::class)->create(['id' => 101]);

        $attributes = [
            'data' => [
                'type' => 'service-logs',
                'attributes' => [
                    'integrationUsername' => $this->integration->username,
                    'serviceId' => $this->service->id,
                    'filterTemplateId' => null,
                    'filterValues' => ['test'],
                ],
            ],
        ];

        $this->expectsEvents(ServiceScheduled::class);

        $mock = $this
            ->partialMock(IntegrationHelper::class, fn ($mock) => $mock
                ->shouldReceive('getService')
                ->with($this->integration->server, $this->integration->username, $this->service->id)
                ->andReturn($this->service->toArray())
                /* */
                ->shouldReceive('scheduleService')
                ->with($this->integration->server, $this->integration->username, $this->service->id, ['STOCK_CODE' => 'test'])
                ->andReturn(collect(['run_id' => 102, 'scheduled_start' => now()->toDateTimeString()]))
                /* */
                ->shouldReceive('getServiceLog')
                ->with($this->integration->server, $this->integration->username, ['query' => ['id' => 102]])
                ->andReturn($serviceLog->toArray()));

        app()->bind(IntegrationHelper::class, fn () => $mock);

        $this
            ->passportAs($this->user)
            ->postJson(url('/api/v1/service-logs'), $attributes, $this->jsonApiHeaders)
            ->assertCreated()
            ->assertJsonFragment([
                'data' => $this->convertLogToV1Format($serviceLog)
            ]);
    }

    public function test_users_can_create_a_service_log_with_filter_template_id(): void
    {
        $attributes = [
            'data' => [
                'type' => 'service-logs',
                'attributes' => [
                    'integrationUsername' => $this->integration->username,
                    'serviceId' => $this->service->id,
                    'filterTemplateId' => $this->filterTemplate->id
                ],
            ],
        ];

        $this->expectsEvents(ServiceScheduled::class);

        $mock = $this
            ->partialMock(IntegrationHelper::class, fn ($mock) => $mock
                ->shouldReceive('getService')
                ->with($this->integration->server, $this->integration->username, $this->service->id)
                ->andReturn($this->service->toArray())
                /* */
                ->shouldReceive('scheduleService')
                ->with($this->integration->server, $this->integration->username, $this->service->id, [])
                ->andReturn(collect(['run_id' => 102, 'scheduled_start' => now()->toDateTimeString()]))
                /* */
                ->shouldReceive('getServiceLog')
                ->with($this->integration->server, $this->integration->username, ['query' => ['id' => 102]])
                ->andReturn(factory(ServiceLog::class)->raw(['id' => 102])));

        app()->bind(IntegrationHelper::class, fn () => $mock);

        $this
            ->passportAs($this->user)
            ->postJson(url('/api/v1/service-logs'), $attributes, $this->jsonApiHeaders)
            ->assertCreated();
    }

    private function convertLogToV1Format(ServiceLog $log): array
    {
        return [
            'id' => "$log->id",
            'type' => 'service-logs',
            'attributes' => [
                'runId' => $log->id,
                'serviceId' => $log->service_id,
                "fromFactory" => $log->from_factory,
                "fromEnvironment" => $log->from_environment,
                "toFactory" => $log->to_factory,
                "toEnvironment" => $log->to_environment,
                "username" => $log->username,
                "requestedBy" => $log->requested_by,
                "status" => $log->status,
                "runtime" => "$log->runtime",
                "errorCount" => $log->error,
                "filters" => $log->filters,
                "dueAt" => $log->due_at,
                "startedAt" => $log->started_at,
                "finishedAt" => $log->finished_at
            ],
            'links' => [
                'self' => url('/api/v1/service-logs', $log->id),
            ],
        ];
    }
}
