<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Fabric\Company;
use App\Models\Fabric\InboundEndpoint;
use App\Models\Fabric\Integration;
use App\Models\Fabric\OauthClient;
use App\Models\Fabric\System;
use App\Models\Fabric\User;
use App\Models\Tapestry\Connector;
use App\Models\Tapestry\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\LaravelTestCase;

/**
 * @group inbound
 */
class ConnectorOauthClientControllerTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();
        $this->withoutPermissions = $this->company->users()->save(factory(User::class)->make());
        $this->withPermissions = $this->company->users()->save(factory(User::class)->states('patchworks admin')->make());
        $this->integration = $this->company->integrations()->save(factory(Integration::class)->make(['username' => 'table']));
        $this->system = factory(System::class)->create(['factory_name' => 'InboundAPI']);
        $this->service = $this->integration->services()->save(factory(Service::class)->make(['from_factory' => 'InboundAPI\\Pull\\JsonPayload', 'username' => 'table']));
        $this->connector = tap(factory(Connector::class)->make(['system_chain' => 'InboundAPI', 'common_ref' => $this->service->from_environment])->setIdxTable($this->integration->username))->save();
        $this->endpoint = $this->integration->endpoints()->save(factory(InboundEndpoint::class)->make(['service_id' => $this->service->id]));
        $this->client = $this->connector->clients()->save(factory(OauthClient::class)->make(), ['safe_secret' => 'secret']);
    }

    public function test_user_with_permission_can_retrieve_connector_clients(): void
    {
        $this
            ->passportAs($this->withPermissions)
            ->getJson(route('api.v2.connectors.oauth-clients.index', $this->connector))
            ->assertOk()
            ->assertJsonPath('data.0.id', $this->client->id)
            ->assertJsonStructure(['data' => ['*' => ['safe_secret']]]);
    }

    public function test_user_without_permission_cannot_retrieve_connector_clients(): void
    {
        $this
            ->passportAs($this->withoutPermissions)
            ->getJson(route('api.v2.connectors.oauth-clients.index', $this->connector))
            ->assertForbidden();
    }

    public function test_user_with_permission_can_create_a_connector_client(): void
    {
        $attributes = [
            'name' => 'Test Client',
        ];

        $response = $this
            ->passportAs($this->withPermissions)
            ->postJson(route('api.v2.connectors.oauth-clients.store', $this->connector), $attributes);

        $response
            ->assertCreated()
            ->assertJsonStructure(['data' => ['secret']]);

        $this->assertDatabaseHas((new OauthClient)->getTable(), [
            'name' => $attributes['name'],
        ]);

        $this->assertDatabaseHas('connector_oauth_client', [
            'connector_id' => $this->connector->id,
            'oauth_client_id'           => $response->json('data.id'),
        ]);
    }

    public function test_user_without_permission_cannot_create_a_connector_client(): void
    {
        $this
            ->passportAs($this->withoutPermissions)
            ->postJson(route('api.v2.connectors.oauth-clients.store', $this->connector))
            ->assertForbidden();
    }

    public function test_user_with_permission_can_delete_a_connector_client(): void
    {
        $this
            ->passportAs($this->withPermissions)
            ->deleteJson(route(
                'api.v2.connectors.oauth-clients.destroy',
                [$this->connector, $this->client]
            ), ['confirmation' => $this->client->name])
            ->assertOk()
            ->assertJsonPath('message', 'Client deleted successfully.');

        $this->assertDeleted($this->client);
    }

    public function test_user_with_permission_cannot_delete_other_companies_connector_clients(): void
    {
        $company = factory(Company::class)->create();
        $integration = $company->integrations()->save(factory(Integration::class)->make(['username' => 'table']));
        $service = $integration->services()->save(factory(Service::class)->make());
        $connector = tap(factory(Connector::class)->make(['system_chain' => 'InboundAPI', 'common_ref' => $this->service->from_environment])->setIdxTable($this->integration->username))->save();
        $endpoint = $integration->endpoints()->save(factory(InboundEndpoint::class)->make(['service_id' => $service->id]));
        $client = $connector->clients()->save(factory(OauthClient::class)->make(), ['safe_secret' => 'secret']);

        $this
            ->passportAs($this->withPermissions)
            ->deleteJson(route(
                'api.v2.connectors.oauth-clients.destroy',
                [$this->connector, $client]
            ), ['confirmation' => $client->name])
            ->assertForbidden();
    }

    public function test_user_without_permission_cannot_delete_a_connector_client(): void
    {
        $this
            ->passportAs($this->withoutPermissions)
            ->deleteJson(route('api.v2.connectors.oauth-clients.destroy', [$this->connector, $this->client]))
            ->assertForbidden();
    }
}
