<?php

namespace Tests\Feature\Http\Services\Webhook;

use App\Facades\SystemAuth;
use App\Facades\SystemWebhook;
use App\Http\Services\Auth\PeoplevoxService as PeoplevoxAuthService;
use App\Http\Services\Webhook\PeoplevoxService as PeoplevoxWebhookService;
use App\Models\Fabric\Company;
use App\Models\Fabric\EventType;
use App\Models\Fabric\Integration;
use App\Models\Fabric\System;
use App\Models\Fabric\User;
use App\Models\Fabric\Webhook;
use App\Models\Tapestry\Connector;
use App\Models\Tapestry\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

use Tests\LaravelTestCase;

class PeoplevoxWebhookTest extends LaravelTestCase
{
    use RefreshDatabase;

    protected string $systemFactoryName = 'Peoplevox';

    protected array $credentials = [
        'client_id' => 'test_client_id',
        'username' => 'test_username',
        'password' => 'test_password',
        'url' => 'https://www.pwk.co',
        'connector_name' => 'test',
        'timezone' => 'UTC',
        'date_format' => 'd/m/Y',
    ];

    protected Mockery\MockInterface $authMock;

    public function setUp(): void
    {
        parent::setUp();
        $this->system = factory(System::class)->create(['factory_name' => $this->systemFactoryName]);
        $company = factory(Company::class)->create();
        $this->clientAdmin = $company->users()->save(factory(User::class)->states('client admin')->make());
        $this->integration = $company->integrations()->save(factory(Integration::class)->make(['username' => 'table']));
        $this->connector = tap(factory(Connector::class)->make([
            'system_chain' => $this->systemFactoryName,
            'common_ref' => 'test',
            'extra' => $this->credentials
        ])
            ->setIdxTable($this->integration->username))->save();
        $this->service = $this->integration->services()->save(factory(Service::class)->make([
            'username' => $this->integration->username,
            'from_factory' => "$this->systemFactoryName\\Pull\\Orders",
            'to_factory' => "$this->systemFactoryName\\Push\\Orders",
            'from_environment' => 'test'
        ]));
        $this->eventType = factory(EventType::class)->create([
            'system_id' => $this->system->id
        ]);
        $this->webhook = factory(Webhook::class)->create([
            'integration_id' => $this->integration->id,
            'service_id' => $this->service->id,
            'event_type_id' => $this->eventType
        ]);

        $this->authMock = $this->partialMock(PeoplevoxAuthService::class, fn ($mock) => $mock
            ->shouldReceive('authenticate')
            ->with()
            ->andReturn(['AuthenticateResult' => ['ResponseId' => 0]]));

        SystemAuth::partialMock()
            ->shouldReceive('driver')
            ->with('Peoplevox', $this->credentials)
            ->andReturn($this->authMock);

        $webhookMock = $this->partialMock(PeoplevoxWebhookService::class, fn ($mock) => $mock
            ->shouldReceive('unsubscribe')
            ->andReturn(true));

        SystemWebhook::partialMock()
            ->shouldReceive('driver')
            ->with('Peoplevox', [], $this->authMock)
            ->andReturn($webhookMock);
    }

    /**
     * @group only2
     */
    public function test_can_create_with_permission(): void
    {
        $webhook = Webhook::make([
            'active' => 1,
            'integration_id' => $this->integration->id,
            'service_id' => $this->service->id,
            'event_type_id' => $this->eventType->id,
        ]);

        $webhookMock = $this->partialMock(PeoplevoxWebhookService::class, fn ($mock) => $mock
            ->shouldReceive('subscribe')
            ->andReturn(1));

        SystemWebhook::partialMock()
            ->shouldReceive('driver')
            ->with(
                'Peoplevox',
                ['eventType' => $this->eventType->key, 'callbackUrl' => $webhook->getCallbackUrl(), ''],
                $this->authMock
            )
            ->andReturn($webhookMock);

        $this
            ->passportAs($this->clientAdmin)
            ->postJson(route('api.v2.webhooks.store'), [
                'service_id' => $this->service->id,
                'event_type_id' => $this->eventType->id,
                'payload' => ['']
            ])
            ->assertCreated();
    }

    public function test_can_delete_with_permission(): void
    {
        $this
            ->passportAs($this->clientAdmin)
            ->deleteJson(route('api.v2.webhooks.destroy', $this->webhook->id))
            ->assertOk();

        $this->assertDeleted($this->webhook);
    }

    public function test_on_integration_delete_cascades(): void
    {
        $this
            ->passportAs($this->clientAdmin)
            ->deleteJson(route('api.v2.integrations.destroy', $this->integration->id))
            ->assertOk();

        $this->assertDeleted($this->integration);
        $this->assertDeleted($this->webhook);
    }

    public function test_on_service_delete_cascades(): void
    {
        $this
            ->passportAs($this->clientAdmin)
            ->deleteJson(route('api.v2.services.destroy', $this->service->id))
            ->assertOk();

        $this->assertSoftDeleted($this->service);
        $this->assertDeleted($this->webhook);
    }
}
